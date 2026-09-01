<?php

$servername = "";
$username = "";
$password = "";
$dbname = "eventsdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error){
    die('connection failed!'  . $conn->connect_error);
}else{
    echo <script>console.log("connection successful!");</script>;
}

?>