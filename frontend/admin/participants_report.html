<?php
/**
 * NSBM EventHub - Admin Participant Roster Report
 * Generates a clean, printable roster of all registered students for a given event.
 */
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/session_check.php';

require_role('admin');

$event_id = (int)($_GET['event_id'] ?? 0);

if ($event_id <= 0) {
    set_flash_message('warning', 'Please select an event to view its participant report.');
    header("Location: registrations_view.php");
    exit();
}

try {
    // Fetch event details
    $stmt_evt = $pdo->prepare("
        SELECT e.*, c.name AS category_name, u.full_name AS creator_name
        FROM events e
        JOIN categories c ON e.category_id = c.id
        JOIN users u ON e.created_by = u.id
        WHERE e.id = ?
    ");
    $stmt_evt->execute([$event_id]);
    $event = $stmt_evt->fetch();

    if (!$event) {
        set_flash_message('danger', 'Event not found.');
        header("Location: registrations_view.php");
        exit();
    }

    // Fetch registered participants using PDO JOIN
    $stmt_participants = $pdo->prepare("
        SELECT u.id, u.full_name, u.email, r.registered_at
        FROM registrations r
        JOIN users u ON r.student_id = u.id
        WHERE r.event_id = ?
        ORDER BY r.registered_at ASC
    ");
    $stmt_participants->execute([$event_id]);
    $participants = $stmt_participants->fetchAll();

} catch (PDOException $e) {
    die("Database error generating report: " . $e->getMessage());
}

$page_title = "Participant Roster - " . htmlspecialchars($event['title']);
require_once '../includes/header.php';
?>

<!-- Print-only CSS -->
<style>
@media print {
    .navbar, footer, .btn-no-print, .alert {
        display: none !important;
    }
    body {
        background-color: #ffffff !important;
        color: #000000 !important;
    }
    .glass-card {
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
    }
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4 btn-no-print">
    <div>
        <a href="registrations_view.php" class="btn btn-outline-secondary rounded-pill me-2">
            <i class="bi bi-arrow-left me-1"></i> Back to Overview
        </a>
    </div>
    <div>
        <button onclick="window.print()" class="btn btn-nsbm shadow-sm">
            <i class="bi bi-printer me-1"></i> Print Participant List
        </button>
    </div>
</div>

<div class="glass-card p-4 p-md-5">
    <!-- Header Block -->
    <div class="border-bottom pb-4 mb-4">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 mb-2"><?php echo htmlspecialchars($event['category_name']); ?></span>
                <h2 class="fw-bold mb-1 text-dark"><?php echo htmlspecialchars($event['title']); ?></h2>
                <p class="text-muted small mb-0"><i class="bi bi-geo-alt me-1"></i>Venue: <?php echo htmlspecialchars($event['venue']); ?></p>
            </div>
            <div class="text-end">
                <span class="badge bg-dark rounded-pill px-3 py-2 fs-6 mb-1">
                    <?php echo count($participants); ?> / <?php echo $event['capacity']; ?> Registered
                </span>
                <div class="small text-muted">Status: <?php echo $event['status']; ?></div>
            </div>
        </div>
        <div class="row g-2 mt-3 small text-muted">
            <div class="col-sm-4"><i class="bi bi-calendar3 me-1"></i><strong>Date:</strong> <?php echo date('F d, Y', strtotime($event['event_date'])); ?></div>
            <div class="col-sm-4"><i class="bi bi-clock me-1"></i><strong>Time:</strong> <?php echo date('h:i A', strtotime($event['event_time'])); ?></div>
            <div class="col-sm-4"><i class="bi bi-person me-1"></i><strong>Organizer:</strong> <?php echo htmlspecialchars($event['creator_name']); ?></div>
        </div>
    </div>

    <!-- Participant List Table -->
    <h5 class="fw-bold mb-3"><i class="bi bi-person-check-fill text-success me-2"></i>Registered Student Roster</h5>

    <?php if (empty($participants)): ?>
        <div class="alert alert-light text-center py-4 border rounded-3 text-muted">
            <i class="bi bi-info-circle display-6 mb-2 d-block"></i>
            No students have registered for this event yet.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Student Name</th>
                        <th>Email Address</th>
                        <th>Registration Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $index = 1;
                    foreach ($participants as $p): 
                    ?>
                        <tr>
                            <td class="fw-bold text-center"><?php echo $index++; ?></td>
                            <td class="fw-semibold text-dark"><?php echo htmlspecialchars($p['full_name']); ?></td>
                            <td><code><?php echo htmlspecialchars($p['email']); ?></code></td>
                            <td class="small text-muted"><?php echo date('Y-m-d h:i A', strtotime($p['registered_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3 text-muted small">
            Report generated on <?php echo date('F d, Y - h:i A'); ?> via NSBM EventHub.
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
