<?php
// Read-only feed for students - global notices and per-event updates
// mixed together, newest on top.
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/session_check.php';

require_role('student');

$page_title = "Campus Announcements";

try {
    $stmt = $pdo->query("
        SELECT a.*, e.title AS event_title, u.full_name AS author_name
        FROM announcements a
        LEFT JOIN events e ON a.event_id = e.id
        JOIN users u ON a.created_by = u.id
        ORDER BY a.created_at DESC
    ");
    $announcements = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database error loading announcements: " . $e->getMessage());
}

require_once '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-bell-fill text-warning me-2"></i>Campus Notices & Announcements</h2>
        <p class="text-muted small mb-0">Stay updated on important news, timetable changes, and event updates</p>
    </div>
</div>

<?php if (empty($announcements)): ?>
    <div class="glass-card p-5 text-center text-muted my-4">
        <i class="bi bi-bell-slash display-4 mb-3 d-block text-secondary"></i>
        <h5>No announcements posted yet</h5>
        <p class="small mb-0">Check back later for university updates and event news.</p>
    </div>
<?php else: ?>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-grid gap-4">
                <?php foreach ($announcements as $ann): ?>
                    <div class="glass-card p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                            <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($ann['title']); ?></h4>
                            <?php if ($ann['event_id']): ?>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1">
                                    <i class="bi bi-calendar-event me-1"></i>Event: <?php echo htmlspecialchars($ann['event_title']); ?>
                                </span>
                            <?php else: ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1">
                                    <i class="bi bi-globe me-1"></i>Global Notice
                                </span>
                            <?php endif; ?>
                        </div>

                        <p class="text-muted mb-3 fs-6" style="white-space: pre-line; line-height: 1.6;">
                            <?php echo htmlspecialchars($ann['content']); ?>
                        </p>

                        <div class="d-flex justify-content-between align-items-center pt-3 text-muted small" style="border-top: 1px solid var(--border-soft);">
                            <span><i class="bi bi-person me-1"></i>Posted by <strong><?php echo htmlspecialchars($ann['author_name']); ?></strong></span>
                            <span><i class="bi bi-clock me-1"></i><?php echo date('F d, Y - h:i A', strtotime($ann['created_at'])); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>
