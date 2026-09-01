<?php
// Helper functions used all over the site. Nothing fancy, just the stuff
// we kept copy-pasting into different pages so we pulled it out here.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Strip tags/encode special chars on anything coming from a form before we
// touch it - handles arrays too (checkboxes etc send arrays).
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Stashes a one-time message in the session so it survives the redirect
// after a form submit (login, register, event created, etc).
function set_flash_message($type, $message) {
    $_SESSION['flash_message'] = [
        'type'    => $type,
        'message' => $message
    ];
}

// Prints the flash message above and clears it so it doesn't show again
// on the next page load. Called from header.php on every page.
function display_flash_message() {
    if (isset($_SESSION['flash_message'])) {
        $type = $_SESSION['flash_message']['type'];
        $msg  = $_SESSION['flash_message']['message'];
        unset($_SESSION['flash_message']);

        echo "<div class='alert alert-{$type} alert-dismissible fade show rounded-3 shadow-sm my-3' role='alert'>
                <i class='bi bi-info-circle-fill me-2'></i>{$msg}
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }
}

// How many students are currently registered for this event
function get_event_registration_count($pdo, $event_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM registrations WHERE event_id = ?");
    $stmt->execute([$event_id]);
    return (int) $stmt->fetchColumn();
}

// Has this student already grabbed a seat for this event?
function is_student_registered($pdo, $event_id, $student_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM registrations WHERE event_id = ? AND student_id = ?");
    $stmt->execute([$event_id, $student_id]);
    return $stmt->fetchColumn() > 0;
}

// Small helper so we're not repeating the same badge markup on every page
// that lists events.
function get_status_badge($status) {
    switch ($status) {
        case 'Upcoming':
            return "<span class='badge bg-primary bg-gradient rounded-pill px-3 py-2'>Upcoming</span>";
        case 'Ongoing':
            return "<span class='badge bg-success bg-gradient rounded-pill px-3 py-2'>Ongoing</span>";
        case 'Completed':
            return "<span class='badge bg-secondary bg-gradient rounded-pill px-3 py-2'>Completed</span>";
        case 'Cancelled':
            return "<span class='badge bg-danger bg-gradient rounded-pill px-3 py-2'>Cancelled</span>";
        default:
            return "<span class='badge bg-dark rounded-pill px-3 py-2'>{$status}</span>";
    }
}
