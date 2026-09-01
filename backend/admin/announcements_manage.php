<?php
// Post/edit/delete announcements. Can be a general campus notice or tied
// to a specific event (event_id nullable in the table for that reason).
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/session_check.php';

require_role('admin');

$page_title = "Manage Announcements";
$errors = [];
$edit_announcement = null;

$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);

// delete
if ($action === 'delete' && $id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM announcements WHERE id = ?");
        $stmt->execute([$id]);
        set_flash_message('success', 'Announcement deleted successfully.');
        header("Location: announcements_manage.php");
        exit();
    } catch (PDOException $e) {
        set_flash_message('danger', 'Failed to delete announcement: ' . $e->getMessage());
        header("Location: announcements_manage.php");
        exit();
    }
}

// load the one we're editing
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM announcements WHERE id = ?");
    $stmt->execute([$id]);
    $edit_announcement = $stmt->fetch();
    if (!$edit_announcement) {
        set_flash_message('warning', 'Announcement not found.');
        header("Location: announcements_manage.php");
        exit();
    }
}

// handle the form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ann_id   = (int)($_POST['announcement_id'] ?? 0);
    $title    = sanitize($_POST['title'] ?? '');
    $content  = sanitize($_POST['content'] ?? '');
    $event_id = !empty($_POST['event_id']) ? (int)$_POST['event_id'] : null;

    if (empty($title)) $errors[] = "Title is required.";
    if (empty($content)) $errors[] = "Content body is required.";

    if (empty($errors)) {
        try {
            if ($ann_id > 0) {
                // editing
                $stmt = $pdo->prepare("UPDATE announcements SET title = ?, content = ?, event_id = ? WHERE id = ?");
                $stmt->execute([$title, $content, $event_id, $ann_id]);
                set_flash_message('success', 'Announcement updated successfully.');
            } else {
                // new post
                $stmt = $pdo->prepare("INSERT INTO announcements (title, content, event_id, created_by) VALUES (?, ?, ?, ?)");
                $stmt->execute([$title, $content, $event_id, $_SESSION['user_id']]);
                set_flash_message('success', 'Announcement published successfully.');
            }
            header("Location: announcements_manage.php");
            exit();
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

// so admin can optionally link this announcement to one event
try {
    $events = $pdo->query("SELECT id, title FROM events ORDER BY title ASC")->fetchAll();

    $stmt_ann = $pdo->query("
        SELECT a.*, e.title AS event_title, u.full_name AS author_name 
        FROM announcements a 
        LEFT JOIN events e ON a.event_id = e.id 
        JOIN users u ON a.created_by = u.id 
        ORDER BY a.created_at DESC
    ");
    $announcements = $stmt_ann->fetchAll();
} catch (PDOException $e) {
    die("Database error loading announcements: " . $e->getMessage());
}

require_once '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-megaphone text-warning me-2"></i>Campus Announcements</h2>
        <p class="text-muted small mb-0">Broadcast notices globally to all students or target specific campus events</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Form Card -->
    <div class="col-md-5">
        <div class="glass-card p-4">
            <h5 class="fw-bold mb-3">
                <i class="bi <?php echo $edit_announcement ? 'bi-pencil-square text-warning' : 'bi-plus-circle-fill text-success'; ?> me-2"></i>
                <?php echo $edit_announcement ? 'Edit Announcement' : 'Post New Notice'; ?>
            </h5>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger rounded-3 small mb-3">
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $err): ?>
                            <li><?php echo htmlspecialchars($err); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="announcements_manage.php" method="POST" class="needs-validation" novalidate>
                <input type="hidden" name="announcement_id" value="<?php echo $edit_announcement['id'] ?? 0; ?>">

                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Notice Title</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="e.g. Schedule change for Hackathon" value="<?php echo htmlspecialchars($_POST['title'] ?? $edit_announcement['title'] ?? ''); ?>" required>
                    <div class="invalid-feedback">Title is required.</div>
                </div>

                <div class="mb-3">
                    <label for="event_id" class="form-label fw-semibold">Link to Specific Event (Optional)</label>
                    <select name="event_id" id="event_id" class="form-select">
                        <option value="">-- General Campus Notice (All Students) --</option>
                        <?php foreach ($events as $evt): ?>
                            <option value="<?php echo $evt['id']; ?>" <?php echo (($_POST['event_id'] ?? $edit_announcement['event_id'] ?? '') == $evt['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($evt['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">If unselected, the notice will appear globally on the student feed.</div>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label fw-semibold">Announcement Content</label>
                    <textarea name="content" id="content" rows="4" class="form-control" placeholder="Detailed notification content..." required><?php echo htmlspecialchars($_POST['content'] ?? $edit_announcement['content'] ?? ''); ?></textarea>
                    <div class="invalid-feedback">Content body is required.</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-nsbm flex-fill">
                        <i class="bi bi-send me-1"></i> <?php echo $edit_announcement ? 'Update Notice' : 'Post Announcement'; ?>
                    </button>
                    <?php if ($edit_announcement): ?>
                        <a href="announcements_manage.php" class="btn btn-light border">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Announcement Table List -->
    <div class="col-md-7">
        <div class="glass-card p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-list-ul me-2 text-primary"></i>Posted Announcements</h5>

            <?php if (empty($announcements)): ?>
                <p class="text-muted text-center py-4">No announcements posted yet.</p>
            <?php else: ?>
                <div class="d-grid gap-3">
                    <?php foreach ($announcements as $ann): ?>
                        <div class="p-3 border rounded-3 bg-white shadow-sm">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($ann['title']); ?></h6>
                                <div class="btn-group">
                                    <a href="announcements_manage.php?action=edit&id=<?php echo $ann['id']; ?>" class="btn btn-sm btn-light border me-1" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="announcements_manage.php?action=delete&id=<?php echo $ann['id']; ?>" class="btn btn-sm btn-light border text-danger btn-confirm-delete" data-confirm-msg="Delete this announcement?" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>
                            
                            <p class="small text-secondary mb-2"><?php echo htmlspecialchars($ann['content']); ?></p>

                            <div class="d-flex justify-content-between align-items-center small text-muted border-top pt-2 mt-2">
                                <span>
                                    <?php if ($ann['event_id']): ?>
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">Event: <?php echo htmlspecialchars($ann['event_title']); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border">Global Campus Notice</span>
                                    <?php endif; ?>
                                </span>
                                <span><i class="bi bi-clock me-1"></i><?php echo date('M d, Y - h:i A', strtotime($ann['created_at'])); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
