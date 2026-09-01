<?php
/**
 * NSBM EventHub - User Registration
 * Allows new users to create an Admin or Student account.
 */
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Redirect logged-in users away from register page
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$errors = [];
$full_name = '';
$email     = '';
$role      = 'student';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Sanitize user inputs
    $full_name        = sanitize($_POST['full_name'] ?? '');
    $email            = sanitize($_POST['email'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role             = sanitize($_POST['role'] ?? 'student');

    // 2. Server-side validations
    if (empty($full_name)) {
        $errors[] = "Full name is required.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email address is required.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (!in_array($role, ['admin', 'student'])) {
        $errors[] = "Invalid role selected.";
    }

    // 3. Check for existing email if no errors so far
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $errors[] = "An account with this email address already exists.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error during check: " . $e->getMessage();
        }
    }

    // 4. Insert user record if validation passes
    if (empty($errors)) {
        try {
            // Securely hash password using PHP's password_hash
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insert_stmt = $pdo->prepare("
                INSERT INTO users (full_name, email, password, role) 
                VALUES (?, ?, ?, ?)
            ");
            $insert_stmt->execute([$full_name, $email, $hashed_password, $role]);

            set_flash_message('success', 'Registration successful! You can now log in with your credentials.');
            header("Location: login.php");
            exit();
        } catch (PDOException $e) {
            $errors[] = "Failed to register account: " . $e->getMessage();
        }
    }
}

$page_title = "Create Account";
require_once '../includes/header.php';
?>

<div class="row justify-content-center my-4">
    <div class="col-md-8 col-lg-6">
        <div class="glass-card p-4 p-md-5">
            <div class="text-center mb-4">
                <span class="navbar-brand-badge display-6 mb-2 d-inline-block"><i class="bi bi-person-plus-fill me-1"></i></span>
                <h3 class="fw-bold">Create an Account</h3>
                <p class="text-muted small">Join NSBM EventHub to participate or host campus events</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger rounded-3 shadow-sm mb-4">
                    <ul class="mb-0 small ps-3">
                        <?php foreach ($errors as $err): ?>
                            <li><?php echo htmlspecialchars($err); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" class="needs-validation" novalidate>
                <!-- Full Name -->
                <div class="mb-3">
                    <label for="full_name" class="form-label fw-semibold">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-person text-muted"></i></span>
                        <input type="text" name="full_name" id="full_name" class="form-control" placeholder="e.g. Amal Perera" value="<?php echo htmlspecialchars($full_name); ?>" required>
                    </div>
                    <div class="invalid-feedback">Please enter your full name.</div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" name="email" id="email" class="form-control" placeholder="name@student.nsbm.ac.lk" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="invalid-feedback">Please provide a valid email address.</div>
                </div>

                <!-- Role Selection -->
                <div class="mb-3">
                    <label for="role" class="form-label fw-semibold">Register As</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-person-badge text-muted"></i></span>
                        <select name="role" id="role" class="form-select" required>
                            <option value="student" <?php echo $role === 'student' ? 'selected' : ''; ?>>Student</option>
                            <option value="admin" <?php echo $role === 'admin' ? 'selected' : ''; ?>>Administrator / Faculty</option>
                        </select>
                    </div>
                    <div class="form-text">Choose Student to browse/register for events, or Administrator to manage events.</div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock text-muted"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Minimum 6 characters" minlength="6" required>
                    </div>
                    <div class="invalid-feedback">Password must be at least 6 characters long.</div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="confirm_password" class="form-label fw-semibold">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-shield-lock text-muted"></i></span>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Re-enter password" required>
                    </div>
                    <div class="invalid-feedback">Please confirm your password.</div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-nsbm w-100 py-2 fs-6 shadow-sm mb-3">
                    <i class="bi bi-check-circle me-1"></i> Register Account
                </button>

                <div class="text-center">
                    <span class="text-muted small">Already have an account?</span>
                    <a href="login.php" class="small fw-bold text-success text-decoration-none ms-1">Log In Here</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
