<?php
/**
 * NSBM EventHub - Logout Handler
 * Clears session data and redirects user to login.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Unset all session variables
$_SESSION = array();

// Destroy session cookie if present
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destroy session
session_destroy();

// Start fresh session to pass logout flash message
session_start();
$_SESSION['flash_message'] = [
    'type'    => 'info',
    'message' => 'You have been successfully logged out.'
];

header("Location: login.php");
exit();
?>
