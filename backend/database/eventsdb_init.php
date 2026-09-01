<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eventsdb";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database
$sql = "CREATE DATABASE IF NOT EXISTS `$dbname`";

if (!$conn->query($sql)) {
    die("Error creating database: " . $conn->error);
}

// Select the database
if (!$conn->select_db($dbname)) {
    die("Error selecting database: " . $conn->error);
}

// Create event_details table
$sql = "CREATE TABLE IF NOT EXISTS event_details (
    eventId INT AUTO_INCREMENT PRIMARY KEY,
    eventName VARCHAR(100) NOT NULL,
    details VARCHAR(5000) NOT NULL,
    eventDate DATE,
    eventStartTime TIME,
    eventDuration INT,
    eventCategory VARCHAR(70)
)";

if (!$conn->query($sql)) {
    die("Error creating event_details table: " . $conn->error);
}

// Create event_photos table
$sql = "CREATE TABLE IF NOT EXISTS event_photos (
    photoID INT AUTO_INCREMENT PRIMARY KEY,
    eventId INT NOT NULL,
    photoPath VARCHAR(200),

    FOREIGN KEY (eventId)
        REFERENCES event_details(eventId)
        ON DELETE CASCADE
)";

if (!$conn->query($sql)) {
    die("Error creating event_photos table: " . $conn->error);
}

echo "Database setup completed successfully.";

?>