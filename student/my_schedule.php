<?php
// "My Schedule" tab - just a table of everything this student signed up
// for, soonest first, with a cancel button per row.
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/session_check.php';

require_role('student');

$student_id = $_SESSION['user_id'];
$page_title = "My Event Schedule";

try {
    $stmt = $pdo->prepare("
        SELECT e.*, c.name AS category_name, r.registered_at
        FROM registrations r
        JOIN events e ON r.event_id = e.id
        JOIN categories c ON e.category_id = c.id
        WHERE r.student_id = ?
        ORDER BY e.event_date ASC, e.event_time ASC
    ");
    $stmt->execute([$student_id]);
    $my_schedule = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database error fetching schedule: " . $e->getMessage());
}

require_once '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-calendar-check text-primary me-2"></i>My Registered Schedule</h2>
        <p class="text-muted small mb-0">Your personal timetable of reserved campus events</p>
    </div>
    <a href="events_browse.php" class="btn btn-nsbm shadow-sm">
        <i class="bi bi-compass me-1"></i> Browse More Events
    </a>
</div>

<?php if (empty($my_schedule)): ?>
    <div class="glass-card p-5 text-center text-muted my-4">
        <i class="bi bi-calendar-x display-4 mb-3 d-block text-secondary"></i>
        <h5>Your schedule is empty!</h5>
        <p class="small mb-3">You haven't registered for any campus events yet.</p>
        <a href="events_browse.php" class="btn btn-nsbm rounded-pill px-4">Browse Campus Events</a>
    </div>
<?php else: ?>
    <div class="glass-card p-4">
        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr class="text-muted small">
                        <th>Event Title</th>
                        <th>Category</th>
                        <th>Date & Time</th>
                        <th>Venue</th>
                        <th>Registered On</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($my_schedule as $evt): ?>
                        <tr>
                            <td class="fw-bold fs-6"><?php echo htmlspecialchars($evt['title']); ?></td>
                            <td><span class="badge bg-secondary bg-opacity-25 border"><?php echo htmlspecialchars($evt['category_name']); ?></span></td>
                            <td class="small">
                                <i class="bi bi-calendar3 me-1 text-success"></i><?php echo date('M d, Y', strtotime($evt['event_date'])); ?><br>
                                <i class="bi bi-clock me-1 text-success"></i><?php echo date('h:i A', strtotime($evt['event_time'])); ?>
                            </td>
                            <td class="small"><i class="bi bi-geo-alt me-1 text-muted"></i><?php echo htmlspecialchars($evt['venue']); ?></td>
                            <td class="small text-muted"><?php echo date('M d, Y', strtotime($evt['registered_at'])); ?></td>
                            <td><?php echo get_status_badge($evt['status']); ?></td>
                            <td class="text-end">
                                <a href="event_details.php?id=<?php echo $evt['id']; ?>" class="btn btn-sm btn-outline-light border me-1" title="View Details">
                                    <i class="bi bi-eye"></i> Details
                                </a>
                                <form action="event_register.php" method="POST" class="d-inline confirm-form" data-confirm-msg="Cancel your seat for '<?php echo htmlspecialchars(addslashes($evt['title'])); ?>'?">
                                    <input type="hidden" name="event_id" value="<?php echo $evt['id']; ?>">
                                    <input type="hidden" name="action" value="cancel">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancel Seat">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<script>
// same confirm-before-cancel behavior as the event details page
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
