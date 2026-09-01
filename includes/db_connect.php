<?php
// Sets up the PDO connection every page uses to talk to MySQL.
// Update the credentials below to match your local XAMPP/WAMP setup
// if they're different from the defaults.

$host     = 'sql200.infinityfree.com';
$db       = 'if0_42791114_nsbm_eventhub';
$user     = 'if0_42791114';
$pass     = 'webgroup123ai';
$charset  = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$db};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw on error instead of silently failing
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // we just want plain associative arrays back
    PDO::ATTR_EMULATE_PREPARES   => false,                  // let MySQL handle prepares, not PHP
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Not pretty, but good enough to tell us what's wrong during dev/testing
    die("<div style='font-family:sans-serif; padding:20px; text-align:center;'>
            <h2>Database Connection Failed</h2>
            <p>Please check your MySQL server settings in <code>includes/db_connect.php</code> or ensure MySQL is running.</p>
            <p><strong>Error details:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
         </div>");
}
