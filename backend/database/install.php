<?php

$servername = "localhost";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$dbname = "eventsdb";

// Check if the database already exists
$checkDatabase = $conn->query(
    "SHOW DATABASES LIKE '$dbname'"
);

if ($checkDatabase->num_rows > 0) {

    die("Database is already installed.");

}

// Run the initialization file
require_once "eventsdb_init.php";

echo "Database installed successfully!";

?>