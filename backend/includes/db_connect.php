<!--Hirun-->


<?php
/**
 * NSBM EventHub - Database Connection Script
 * Uses PHP Data Objects (PDO) for secure, prepared-statement MySQL database access.
 */

$host     = '127.0.0.1';
$db       = 'nsbm_eventhub';
$user     = 'root';
$pass     = '';
$charset  = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$db};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Return associative arrays by default
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // In production, log error details securely instead of showing raw error
    die("<div style='font-family:sans-serif; padding:20px; text-align:center;'>
            <h2>Database Connection Failed</h2>
            <p>Please check your MySQL server settings in <code>includes/db_connect.php</code> or ensure MySQL is running.</p>
            <p><strong>Error details:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
         </div>");
}
?>
