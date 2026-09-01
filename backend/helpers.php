<?php
function start_session_once(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}
function json_response(bool $success, string $message = '', $data = null, int $status = 200): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success'=>$success,'message'=>$message,'data'=>$data], JSON_UNESCAPED_UNICODE);
    exit;
}
function request_data(): array {
    $raw = file_get_contents('php://input');
    if ($raw !== '') {
        $json = json_decode($raw, true);
        if (is_array($json)) return $json;
    }
    return $_POST ?: $_GET;
}
function require_login_api(): void {
    if (empty($_SESSION['user_id'])) json_response(false, 'Please log in first.', null, 401);
}
function require_role_api(string $role): void {
    require_login_api();
    if (($_SESSION['user_role'] ?? '') !== $role) json_response(false, 'Access denied.', null, 403);
}
function clean_string($value): string { return trim((string)$value); }
function event_with_stats(PDO $pdo, int $eventId, ?int $studentId = null): ?array {
    $stmt = $pdo->prepare("SELECT e.*, c.name AS category_name, u.full_name AS organizer_name, u.email AS organizer_email FROM events e JOIN categories c ON c.id=e.category_id JOIN users u ON u.id=e.created_by WHERE e.id=?");
    $stmt->execute([$eventId]);
    $event=$stmt->fetch();
    if (!$event) return null;
    $count=$pdo->prepare('SELECT COUNT(*) FROM registrations WHERE event_id=?'); $count->execute([$eventId]);
    $event['registered_count']=(int)$count->fetchColumn();
    $event['seats_left']=max(0,(int)$event['capacity']-$event['registered_count']);
    $event['is_full']=$event['seats_left']<=0;
    $event['is_registered']=false;
    if ($studentId) { $s=$pdo->prepare('SELECT COUNT(*) FROM registrations WHERE event_id=? AND student_id=?'); $s->execute([$eventId,$studentId]); $event['is_registered']=(bool)$s->fetchColumn(); }
    return $event;
}
