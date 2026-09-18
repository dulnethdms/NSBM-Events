<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

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
