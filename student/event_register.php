<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';
require_once '../includes/session_check.php';

require_role('student');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: events_browse.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$event_id   = (int) ($_POST['event_id'] ?? 0);
$action     = sanitize($_POST['action'] ?? 'register');

if ($event_id <= 0) {
    set_flash_message('danger', 'Invalid request.');
    header("Location: events_browse.php");
    exit();
}

try {
    $stmt_evt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt_evt->execute([$event_id]);
    $event = $stmt_evt->fetch();

    if (!$event) {
        set_flash_message('danger', 'Event not found.');
        header("Location: events_browse.php");
        exit();
    }

    if ($action === 'register') {
        if (!in_array($event['status'], ['Upcoming', 'Ongoing'], true)) {
            set_flash_message('warning', 'Registrations are closed for this event.');
            header("Location: event_details.php?id=" . $event_id);
            exit();
        }

        if (is_student_registered($pdo, $event_id, $student_id)) {
            set_flash_message('info', 'You are already registered for this event!');
            header("Location: event_details.php?id=" . $event_id);
            exit();
        }

        $current_registered = get_event_registration_count($pdo, $event_id);
        if ($current_registered >= $event['capacity']) {
            set_flash_message('danger', 'Sorry! This event has reached maximum capacity.');
            header("Location: event_details.php?id=" . $event_id);
            exit();
        }

        $insert_stmt = $pdo->prepare("INSERT INTO registrations (event_id, student_id) VALUES (?, ?)");
        $insert_stmt->execute([$event_id, $student_id]);

        set_flash_message('success', 'Registration successful! The event has been added to your schedule.');
        header("Location: my_schedule.php");
        exit();

    } elseif ($action === 'cancel') {
        $delete_stmt = $pdo->prepare("DELETE FROM registrations WHERE event_id = ? AND student_id = ?");
        $delete_stmt->execute([$event_id, $student_id]);

        set_flash_message('info', 'Your seat registration for this event has been cancelled.');
        header("Location: my_schedule.php");
        exit();

    } else {
        set_flash_message('danger', 'Unknown action requested.');
        header("Location: events_browse.php");
        exit();
    }

} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        set_flash_message('warning', 'You are already registered for this event.');
    } else {
        set_flash_message('danger', 'Registration error: ' . $e->getMessage());
    }
    header("Location: event_details.php?id=" . $event_id);
    exit();
}
