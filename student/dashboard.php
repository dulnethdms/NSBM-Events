<?php
// First page a student sees after logging in - quick stats plus a
// preview of what's coming up campus-wide.
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/session_check.php';

require_role('student');

$student_id = $_SESSION['user_id'];
$page_title = "Student Dashboard";

try {
    // total events this student has signed up for, ever
    $stmt_reg = $pdo->prepare("SELECT COUNT(*) FROM registrations WHERE student_id = ?");
    $stmt_reg->execute([$student_id]);
    $my_registrations_count = (int) $stmt_reg->fetchColumn();

    // and how many of those are still ahead of us
    $stmt_up = $pdo->prepare("
        SELECT COUNT(*)
        FROM registrations r
        JOIN events e ON r.event_id = e.id
        WHERE r.student_id = ? AND e.event_date >= CURDATE()
    ");
    $stmt_up->execute([$student_id]);
    $upcoming_registered_count = (int) $stmt_up->fetchColumn();

    // everything open campus-wide right now, not just this student's
    $total_campus_events = (int) $pdo->query("SELECT COUNT(*) FROM events WHERE status = 'Upcoming'")->fetchColumn();

    // feature the next few so the dashboard isn't just numbers
    $stmt_featured = $pdo->query("
        SELECT e.*, c.name AS category_name
        FROM events e
        JOIN categories c ON e.category_id = c.id
        WHERE e.status = 'Upcoming' AND e.event_date >= CURDATE()
        ORDER BY e.event_date ASC, e.event_time ASC
        LIMIT 3
    ");
    $featured_events = $stmt_featured->fetchAll();

} catch (PDOException $e) {
    die("Database error loading student dashboard: " . $e->getMessage());
}

require_once '../includes/header.php';
?>

<div class="hero-gradient p-4 p-md-5 mb-4 shadow">
    <div class="row align-items-center">
        <div class="col-md-8">
            <span class="badge bg-light bg-opacity-25 text-white border border-light border-opacity-50 px-3 py-1 rounded-pill mb-2">Student Portal</span>
            <h2 class="fw-bold mb-2 text-white">Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! 👋</h2>
            <p class="lead mb-0">Discover campus events, reserve your seat, and keep track of everything from one place.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="events_browse.php" class="btn btn-nsbm btn-lg px-4 shadow-sm">
                <i class="bi bi-compass me-1"></i> Browse Events
            </a>
        </div>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-md-4 col-sm-6">
        <div class="glass-card p-3 stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">My Registered Events</span>
                    <h3 class="fw-bold mb-0"><?php echo $my_registrations_count; ?></h3>
                </div>
                <div class="stat-icon"><i class="bi bi-calendar-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="glass-card p-3 stat-card" style="border-left-color:#3b82f6;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Upcoming In My Timetable</span>
                    <h3 class="fw-bold mb-0"><?php echo $upcoming_registered_count; ?></h3>
                </div>
                <div class="stat-icon" style="background: rgba(59,130,246,0.12); color:#3b82f6;"><i class="bi bi-clock-history"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="glass-card p-3 stat-card" style="border-left-color:#f59e0b;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-semibold text-uppercase">Open Campus Events</span>
                    <h3 class="fw-bold mb-0"><?php echo $total_campus_events; ?></h3>
                </div>
                <div class="stat-icon" style="background: rgba(245,158,11,0.12); color:#f59e0b;"><i class="bi bi-stars"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Events -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0"><i class="bi bi-fire text-danger me-2"></i>Upcoming Campus Highlights</h4>
        <a href="events_browse.php" class="btn btn-sm btn-outline-light rounded-pill">View All Events</a>
    </div>

    <?php if (empty($featured_events)): ?>
        <div class="glass-card p-4 text-center text-muted">
            <i class="bi bi-calendar-x display-6 mb-2 d-block"></i>
            <p class="mb-0">No upcoming events scheduled at the moment. Check back soon!</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($featured_events as $evt):
                $reg_count     = get_event_registration_count($pdo, $evt['id']);
                $is_registered = is_student_registered($pdo, $evt['id'], $student_id);
                $is_full       = ($reg_count >= $evt['capacity']);
            ?>
                <div class="col-md-4">
                    <div class="glass-card p-4 h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25"><?php echo htmlspecialchars($evt['category_name']); ?></span>
                            <?php if ($is_registered): ?>
                                <span class="badge bg-primary rounded-pill"><i class="bi bi-check-circle me-1"></i>Registered</span>
                            <?php elseif ($is_full): ?>
                                <span class="badge bg-danger rounded-pill">Full Capacity</span>
                            <?php endif; ?>
                        </div>

                        <h5 class="fw-bold mb-2"><?php echo htmlspecialchars($evt['title']); ?></h5>
                        <p class="small text-muted mb-3 flex-grow-1 text-truncate-2">
                            <?php echo htmlspecialchars($evt['description']); ?>
                        </p>

                        <div class="border-top pt-3 mt-auto" style="border-color: var(--border-soft) !important;">
                            <div class="d-flex justify-content-between small text-muted mb-2">
                                <span><i class="bi bi-calendar3 me-1"></i><?php echo date('M d, Y', strtotime($evt['event_date'])); ?></span>
                                <span><i class="bi bi-clock me-1"></i><?php echo date('h:i A', strtotime($evt['event_time'])); ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-muted"><i class="bi bi-person me-1"></i><?php echo $reg_count; ?>/<?php echo $evt['capacity']; ?> seats</span>
                                <a href="event_details.php?id=<?php echo $evt['id']; ?>" class="btn btn-sm btn-nsbm-outline">
                                    Details <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
