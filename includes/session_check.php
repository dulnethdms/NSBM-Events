<?php
// Login/role gate. Every protected page includes this after db_connect
// and functions, then calls require_role() with whatever it needs.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Bounce to login if nobody's signed in
function require_login() {
    if (!is_logged_in()) {
        $_SESSION['flash_message'] = [
            'type' => 'warning',
            'message' => 'Please log in to access this page.'
        ];
        header("Location: ../auth/login.php");
        exit();
    }
}

// Same as above but also checks the role matches (admin vs student).
// Wrong role gets sent back to their own dashboard instead of a 403 page.
function require_role($role) {
    require_login();
    if ($_SESSION['user_role'] !== $role) {
        $_SESSION['flash_message'] = [
            'type' => 'danger',
            'message' => 'Access denied: You do not have permission to view that resource.'
        ];

        if ($_SESSION['user_role'] === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../student/dashboard.php");
        }
        exit();
    }
}
