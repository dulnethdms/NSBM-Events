<?php
// Wipes the session and sends the user back to login.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// clear everything out
$_SESSION = array();

// kill the cookie too, otherwise the browser hangs onto it
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

session_destroy();

// need a session again just to carry the goodbye message to login.php
session_start();
$_SESSION['flash_message'] = [
    'type'    => 'info',
    'message' => 'You have been successfully logged out.'
];

header("Location: login.php");
exit();
?>
