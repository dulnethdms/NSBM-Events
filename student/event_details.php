<?php
// Single event page - all the details plus the register/cancel button,
// which just posts to event_register.php.
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/session_check.php';

require_role('student');

$student_id = $_SESSION['user_id'];
$event_id   = (int) ($_GET['id'] ?? 0);

if ($event_id <= 0) {
    set_flash_message('danger', 'Invalid event selected.');
    header("Location: events_browse.php");
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT e.*, c.name AS category_name, u.full_name AS organizer_name, u.email AS organizer_email
        FROM events e
        JOIN categories c ON e.category_id = c.id
        JOIN users u ON e.created_by = u.id
        WHERE e.id = ?
    ");
    $stmt->execute([$event_id]);
    $event = $stmt->fetch();

    if (!$event) {
        set_flash_message('danger', 'Event not found.');
        header("Location: events_browse.php");
        exit();
    }

    $registered_count = get_event_registration_count($pdo, $event_id);
    $is_registered    = is_student_registered($pdo, $event_id, $student_id);
    $is_full          = ($registered_count >= $event['capacity']);
    $seats_left       = max(0, $event['capacity'] - $registered_count);

    $stmt_ann = $pdo->prepare("SELECT * FROM announcements WHERE event_id = ? ORDER BY created_at DESC");
    $stmt_ann->execute([$event_id]);
    $event_announcements = $stmt_ann->fetchAll();

} catch (PDOException $e) {
    die("Database error fetching event details: " . $e->getMessage());
}

$page_title = $event['title'];
require_once '../includes/header.php';
?>

<div class="mb-3">
    <a href="events_browse.php" class="btn btn-outline-light rounded-pill">
        <i class="bi bi-arrow-left me-1"></i> Back to Browse Events
    </a>
</div>

<div class="row g-4">
    <!-- Main Details -->
    <div class="col-lg-8">
        <div class="glass-card p-4 p-md-5">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 fs-6">
                    <i class="bi bi-tag me-1"></i><?php echo htmlspecialchars($event['category_name']); ?>
                </span>
                <?php echo get_status_badge($event['status']); ?>
            </div>

            <h2 class="fw-extrabold mb-3"><?php echo htmlspecialchars($event['title']); ?></h2>

            <div class="row g-3 p-3 rounded-4 mb-4" style="background: rgba(255,255,255,0.04);">
                <div class="col-sm-4">
                    <div class="text-muted small">Date</div>
                    <div class="fw-bold"><i class="bi bi-calendar3 me-1 text-success"></i><?php echo date('F d, Y', strtotime($event['event_date'])); ?></div>
                </div>
                <div class="col-sm-4">
                    <div class="text-muted small">Time</div>
                    <div class="fw-bold"><i class="bi bi-clock me-1 text-success"></i><?php echo date('h:i A', strtotime($event['event_time'])); ?></div>
                </div>
                <div class="col-sm-4">
                    <div class="text-muted small">Venue</div>
                    <div class="fw-bold"><i class="bi bi-geo-alt me-1 text-success"></i><?php echo htmlspecialchars($event['venue']); ?></div>
                </div>
            </div>

            <h5 class="fw-bold mb-2">About This Event</h5>
            <p class="text-muted mb-4" style="white-space: pre-line; line-height: 1.7;">
                <?php echo htmlspecialchars($event['description']); ?>
            </p>

            <?php if (!empty($event_announcements)): ?>
                <hr class="my-4" style="border-color: var(--border-soft);">
                <h5 class="fw-bold mb-3"><i class="bi bi-megaphone text-warning me-2"></i>Event Updates & Notices</h5>
                <div class="d-grid gap-3">
                    <?php foreach ($event_announcements as $ann): ?>
                        <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-0">
                            <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($ann['title']); ?></h6>
                            <p class="small text-dark mb-1"><?php echo htmlspecialchars($ann['content']); ?></p>
                            <span class="text-muted text-xs"><i class="bi bi-clock me-1"></i>Posted on <?php echo date('M d, Y', strtotime($ann['created_at'])); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Reservation Sidebar -->
    <div class="col-lg-4">
        <div class="glass-card p-4 sticky-top" style="top: 90px;">
            <h5 class="fw-bold mb-3"><i class="bi bi-ticket-detailed text-primary me-2"></i>Seat Reservation</h5>

            <div class="p-3 rounded-3 mb-4" style="background: rgba(255,255,255,0.04);">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-semibold text-muted">Availability</span>
                    <span class="small fw-bold"><?php echo $registered_count; ?> / <?php echo $event['capacity']; ?> Seats</span>
                </div>
                <div class="progress progress-seat mb-2">
                    <div class="progress-bar <?php echo $is_full ? 'bg-danger' : 'bg-success'; ?>" role="progressbar" style="width: <?php echo min(100, round(($registered_count / $event['capacity']) * 100)); ?>%"></div>
                </div>
                <div class="small text-center <?php echo $is_full ? 'text-danger fw-bold' : 'text-success fw-semibold'; ?>">
                    <?php echo $is_full ? 'No seats remaining' : "{$seats_left} seats remaining!"; ?>
                </div>
            </div>

            <?php if ($is_registered): ?>
                <div class="alert alert-success border-0 shadow-sm rounded-3 mb-3 text-center">
                    <i class="bi bi-check-circle-fill display-6 text-success mb-2 d-block"></i>
                    <h6 class="fw-bold mb-1">You're Registered!</h6>
                    <p class="small mb-0">This event is on your personal schedule.</p>
                </div>
                <form action="event_register.php" method="POST" class="confirm-form" data-confirm-msg="Cancel your seat reservation for this event?">
                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                    <input type="hidden" name="action" value="cancel">
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-x-circle me-1"></i> Cancel My Registration
                    </button>
                </form>
            <?php elseif ($is_full): ?>
                <div class="alert alert-danger border-0 shadow-sm rounded-3 text-center mb-0">
                    <i class="bi bi-exclamation-triangle display-6 mb-2 d-block"></i>
                    <h6 class="fw-bold mb-1">Registration Closed</h6>
                    <p class="small mb-0">This event has reached maximum seating capacity.</p>
                </div>
            <?php elseif ($event['status'] !== 'Upcoming'): ?>
                <div class="alert alert-secondary border-0 shadow-sm rounded-3 text-center mb-0">
                    <i class="bi bi-info-circle display-6 mb-2 d-block"></i>
                    <h6 class="fw-bold mb-1">Unavailable</h6>
                    <p class="small mb-0">Registrations are no longer accepted for <?php echo strtolower($event['status']); ?> events.</p>
                </div>
            <?php else: ?>
                <form action="event_register.php" method="POST">
                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                    <input type="hidden" name="action" value="register">
                    <button type="submit" class="btn btn-nsbm w-100 py-3 fs-6 fw-bold">
                        <i class="bi bi-person-plus-fill me-1"></i> Reserve My Seat Now
                    </button>
                </form>
            <?php endif; ?>

            <hr class="my-4" style="border-color: var(--border-soft);">

            <div class="small">
                <span class="text-muted fw-semibold d-block mb-1">Event Organizer:</span>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-circle fs-5 text-muted"></i>
                    <div>
                        <div class="fw-bold"><?php echo htmlspecialchars($event['organizer_name']); ?></div>
                        <div class="text-muted small"><?php echo htmlspecialchars($event['organizer_email']); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// stop accidental cancellations, ask first
document.querySelectorAll('.confirm-form').forEach(function (form) {
    form.addEventListener('submit', function (e) {
        const msg = form.dataset.confirmMsg || 'Are you sure?';
        if (!confirm(msg)) {
            e.preventDefault();
        }
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>
