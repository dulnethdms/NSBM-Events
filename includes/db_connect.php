<?php
$server_name = $_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost';
$is_local = in_array(strtolower(explode(':', $server_name)[0]), ['localhost', '127.0.0.1', '::1'])
            || (php_sapi_name() === 'cli');

if ($is_local) {
    $host     = 'localhost';
    $db       = 'nsbm_eventhub';
    $user     = 'root';
    $pass     = '';
} else {
    $host     = 'sql200.infinityfree.com';
    $db       = 'if0_42791114_nsbm_eventhub';
    $user     = 'if0_42791114';
    $pass     = 'webgroup123ai';
}
$charset  = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$db};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("<div style='font-family:sans-serif; padding:20px; text-align:center;'>
            <h2>Database Connection Failed</h2>
            <p>Please check your MySQL server settings in <code>includes/db_connect.php</code> or ensure MySQL is running.</p>
            <p><strong>Error details:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
         </div>");
}
