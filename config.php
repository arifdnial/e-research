<?php
// Database configuration
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "e_research";

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
