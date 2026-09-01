<?php
/**
 * NSBM EventHub - User Login
 * Authenticates users against hashed passwords in the MySQL database.
 */
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// Redirect logged-in users away from login page
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../student/dashboard.php");
    }
    exit();
}

$errors = [];
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation checks
    if (empty($email)) {
        $errors[] = "Email address is required.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {
        try {
            // Fetch user by email using prepared statement
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Password match! Initialize session data
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_name']  = $user['full_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role']  = $user['role'];

                set_flash_message('success', 'Welcome back, ' . htmlspecialchars($user['full_name']) . '!');

                // Redirect according to user role
                if ($user['role'] === 'admin') {
                    header("Location: ../admin/dashboard.php");
                } else {
                    header("Location: ../student/dashboard.php");
                }
                exit();
            } else {
                $errors[] = "Invalid email or password. Please try again.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

$page_title = "User Login";
require_once '../includes/header.php';
?>

<div class="row justify-content-center my-5">
    <div class="col-md-7 col-lg-5">
        <div class="glass-card p-4 p-md-5">
            <div class="text-center mb-4">
                <span class="navbar-brand-badge display-6 mb-2 d-inline-block"><i class="bi bi-box-arrow-in-right me-1"></i></span>
                <h3 class="fw-bold">Sign In to EventHub</h3>
                <p class="text-muted small">Enter your university credentials to continue</p>
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

            <form action="login.php" method="POST" class="needs-validation" novalidate>
                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" name="email" id="email" class="form-control" placeholder="name@nsbm.ac.lk" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="invalid-feedback">Please enter your email address.</div>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock text-muted"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                    <div class="invalid-feedback">Please enter your password.</div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-nsbm w-100 py-2 fs-6 shadow-sm mb-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Log In
                </button>

                <div class="text-center">
                    <span class="text-muted small">Don't have an account yet?</span>
                    <a href="register.php" class="small fw-bold text-success text-decoration-none ms-1">Register Now</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
