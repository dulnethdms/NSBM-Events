<!--Dulneth, create this in php-->




<?php
/**
 * NSBM EventHub - Shared Navigation Header
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - NSBM EventHub' : 'NSBM EventHub'; ?></title>
    
    <!-- Bootstrap 5 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-nsbm sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="../index.php">
            <span class="navbar-brand-badge"><i class="bi bi-calendar2-event-fill me-1"></i> NSBM</span>
            <span class="fw-bold tracking-wide">EventHub</span>
        </a>
        
        <div class="d-flex align-items-center gap-2 d-lg-none">
            <!-- Mobile Theme Toggle -->
            <button class="theme-toggle-btn me-1" id="mobileThemeToggleBtn" title="Toggle Theme">
                <i class="bi bi-moon-stars-fill" id="mobileThemeIcon"></i>
            </button>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <!-- ADMIN NAV LINKS -->
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/dashboard.php"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/events_manage.php"><i class="bi bi-calendar-plus me-1"></i> Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/categories_manage.php"><i class="bi bi-tags me-1"></i> Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/registrations_view.php"><i class="bi bi-people me-1"></i> Registrations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/announcements_manage.php"><i class="bi bi-megaphone me-1"></i> Announcements</a>
                    </li>
                <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'student'): ?>
                    <!-- STUDENT NAV LINKS -->
                    <li class="nav-item">
                        <a class="nav-link" href="../student/dashboard.php"><i class="bi bi-house-door me-1"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../student/events_browse.php"><i class="bi bi-compass me-1"></i> Browse Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../student/my_schedule.php"><i class="bi bi-calendar-check me-1"></i> My Schedule</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../student/announcements_view.php"><i class="bi bi-bell me-1"></i> Announcements</a>
                    </li>
                <?php else: ?>
                    <!-- GUEST NAV LINKS -->
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php"><i class="bi bi-house me-1"></i> Home</a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- USER PROFILE / THEME TOGGLE / AUTH ACTIONS -->
            <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                <!-- Theme Switcher Toggle -->
                <li class="nav-item d-none d-lg-block">
                    <button class="theme-toggle-btn" id="themeToggleBtn" title="Toggle Light/Dark Theme">
                        <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                    </button>
                </li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 bg-white bg-opacity-10 px-3 py-2 rounded-pill" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5 text-success"></i>
                            <span><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
                            <span class="badge bg-success bg-opacity-75 ms-1"><?php echo ucfirst($_SESSION['user_role']); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2" aria-labelledby="userDropdown">
                            <li><span class="dropdown-item-text text-muted small"><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger d-flex align-items-center gap-2" href="../auth/logout.php">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-light rounded-pill px-3 py-1 me-1" href="../auth/login.php"><i class="bi bi-box-arrow-in-right me-1"></i> Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-nsbm rounded-pill px-3 py-1" href="../auth/register.php"><i class="bi bi-person-plus me-1"></i> Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="main-wrapper py-4">
    <div class="container">
        <?php 
        if (function_exists('display_flash_message')) {
            display_flash_message();
        }
        ?>
