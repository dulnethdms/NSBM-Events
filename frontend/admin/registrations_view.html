<?php
/**
 * NSBM EventHub - Admin Registrations Overview
 */
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/session_check.php';

require_role('admin');

$page_title = "Event Registrations Overview";

try {
    // Query events along with total registration counts
    $stmt = $pdo->query("
        SELECT e.*, c.name AS category_name, COUNT(r.id) AS registered_students
        FROM events e
        JOIN categories c ON e.category_id = c.id
        LEFT JOIN registrations r ON e.id = r.event_id
        GROUP BY e.id
        ORDER BY e.event_date DESC
    ");
    $events_registration_list = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database error loading registrations: " . $e->getMessage());
}

require_once '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-people-fill text-primary me-2"></i>Event Registrations Overview</h2>
        <p class="text-muted small mb-0">Monitor seat utilization and inspect student participant rosters per event</p>
    </div>
</div>

<div class="glass-card p-4">
    <?php if (empty($events_registration_list)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-person-x display-4 mb-2"></i>
            <h5>No events found</h5>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr class="text-muted small">
                        <th>Event Title</th>
                        <th>Category</th>
                        <th>Date & Time</th>
                        <th>Capacity</th>
                        <th>Occupancy Rate</th>
                        <th>Status</th>
                        <th class="text-end">Roster Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events_registration_list as $item): 
                        $occupancy_pct = $item['capacity'] > 0 ? round(($item['registered_students'] / $item['capacity']) * 100) : 0;
                    ?>
                        <tr>
                            <td class="fw-bold text-dark"><?php echo htmlspecialchars($item['title']); ?></td>
                            <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($item['category_name']); ?></span></td>
                            <td class="small text-muted">
                                <?php echo date('M d, Y', strtotime($item['event_date'])); ?><br>
                                <?php echo date('h:i A', strtotime($item['event_time'])); ?>
                            </td>
                            <td class="fw-bold">
                                <?php echo $item['registered_students']; ?> / <?php echo $item['capacity']; ?>
                            </td>
                            <td style="min-width: 140px;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress progress-seat flex-fill">
                                        <div class="progress-bar <?php echo $occupancy_pct >= 100 ? 'bg-danger' : ($occupancy_pct > 75 ? 'bg-warning' : 'bg-success'); ?>" role="progressbar" style="width: <?php echo min(100, $occupancy_pct); ?>%"></div>
                                    </div>
                                    <span class="small fw-semibold text-muted"><?php echo $occupancy_pct; ?>%</span>
                                </div>
                            </td>
                            <td><?php echo get_status_badge($item['status']); ?></td>
                            <td class="text-end">
                                <a href="participants_report.php?event_id=<?php echo $item['id']; ?>" class="btn btn-sm btn-nsbm">
                                    <i class="bi bi-card-checklist me-1"></i> View Participants List
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
