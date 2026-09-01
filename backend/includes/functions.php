<!--Bimsara-->



<?php
/**
 * NSBM EventHub - Global Helper Functions
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Clean user inputs against XSS vulnerabilities
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Set session flash alert (success, danger, warning, info)
 */
function set_flash_message($type, $message) {
    $_SESSION['flash_message'] = [
        'type'    => $type,
        'message' => $message
    ];
}

/**
 * Display session flash alert if set
 */
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

/**
 * Count active registrations for an event using prepared statement
 */
function get_event_registration_count($pdo, $event_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM registrations WHERE event_id = ?");
    $stmt->execute([$event_id]);
    return (int) $stmt->fetchColumn();
}

/**
 * Check if a specific student is already registered for an event
 */
function is_student_registered($pdo, $event_id, $student_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM registrations WHERE event_id = ? AND student_id = ?");
    $stmt->execute([$event_id, $student_id]);
    return $stmt->fetchColumn() > 0;
}

/**
 * Render color-coded Bootstrap status badge
 */
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
?>
