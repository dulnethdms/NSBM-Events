<?php
/**
 * NSBM EventHub - Admin Categories Management (CRUD)
 */
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/session_check.php';

require_role('admin');

$page_title = "Manage Categories";
$errors = [];
$edit_category = null;

// 1. Process Actions (Delete, Create, Edit)
$action = $_GET['action'] ?? '';
$id     = (int)($_GET['id'] ?? 0);

// DELETE CATEGORY
if ($action === 'delete' && $id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        set_flash_message('success', 'Category deleted successfully.');
        header("Location: categories_manage.php");
        exit();
    } catch (PDOException $e) {
        set_flash_message('danger', 'Failed to delete category: ' . $e->getMessage());
        header("Location: categories_manage.php");
        exit();
    }
}

// FETCH FOR EDIT
if ($action === 'edit' && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $edit_category = $stmt->fetch();
    if (!$edit_category) {
        set_flash_message('warning', 'Category not found.');
        header("Location: categories_manage.php");
        exit();
    }
}

// POST FORM HANDLER (ADD / UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = sanitize($_POST['name'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $cat_id      = (int)($_POST['category_id'] ?? 0);

    if (empty($name)) {
        $errors[] = "Category name is required.";
    }

    if (empty($errors)) {
        try {
            if ($cat_id > 0) {
                // UPDATE
                $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
                $stmt->execute([$name, $description, $cat_id]);
                set_flash_message('success', 'Category updated successfully.');
            } else {
                // INSERT
                $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
                $stmt->execute([$name, $description]);
                set_flash_message('success', 'New category created successfully.');
            }
            header("Location: categories_manage.php");
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Unique violation
                $errors[] = "A category with the name '{$name}' already exists.";
            } else {
                $errors[] = "Database error: " . $e->getMessage();
            }
        }
    }
}

// Fetch all categories for listing
try {
    $categories = $pdo->query("SELECT c.*, COUNT(e.id) AS event_count FROM categories c LEFT JOIN events e ON c.id = e.category_id GROUP BY c.id ORDER BY c.name ASC")->fetchAll();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

require_once '../includes/header.php';
?>

<div class="row g-4 mb-4">
    <!-- Category Form (Add/Edit) -->
    <div class="col-md-5">
        <div class="glass-card p-4">
            <h5 class="fw-bold mb-3">
                <i class="bi <?php echo $edit_category ? 'bi-pencil-square text-warning' : 'bi-plus-circle-fill text-success'; ?> me-2"></i>
                <?php echo $edit_category ? 'Edit Category' : 'Create New Category'; ?>
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

            <form action="categories_manage.php" method="POST" class="needs-validation" novalidate>
                <input type="hidden" name="category_id" value="<?php echo $edit_category['id'] ?? 0; ?>">

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Category Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="e.g. IT & Software" value="<?php echo htmlspecialchars($_POST['name'] ?? $edit_category['name'] ?? ''); ?>" required>
                    <div class="invalid-feedback">Category name is required.</div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Description (Optional)</label>
                    <textarea name="description" id="description" rows="3" class="form-control" placeholder="Brief details about what events belong here..."><?php echo htmlspecialchars($_POST['description'] ?? $edit_category['description'] ?? ''); ?></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-nsbm flex-fill">
                        <i class="bi bi-save me-1"></i> <?php echo $edit_category ? 'Update Category' : 'Save Category'; ?>
                    </button>
                    <?php if ($edit_category): ?>
                        <a href="categories_manage.php" class="btn btn-light border">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Table List -->
    <div class="col-md-7">
        <div class="glass-card p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-tags text-primary me-2"></i>Existing Categories</h5>

            <?php if (empty($categories)): ?>
                <p class="text-muted text-center py-4">No categories defined yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr class="text-muted small">
                                <th>Name</th>
                                <th>Description</th>
                                <th>Total Events</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($cat['name']); ?></td>
                                    <td class="small text-muted"><?php echo htmlspecialchars($cat['description'] ?: 'No description'); ?></td>
                                    <td><span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1"><?php echo $cat['event_count']; ?> events</span></td>
                                    <td class="text-end">
                                        <a href="categories_manage.php?action=edit&id=<?php echo $cat['id']; ?>" class="btn btn-sm btn-light border me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="categories_manage.php?action=delete&id=<?php echo $cat['id']; ?>" class="btn btn-sm btn-light border text-danger btn-confirm-delete" data-confirm-msg="Are you sure you want to delete category '<?php echo htmlspecialchars($cat['name']); ?>'? All associated events may be affected.">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
