<?php
// Admin insertion script
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "e_research";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Admin credentials
$admin_email = "syahmi@gmail.com";
$admin_password = "67890"; // Plain password

// Hash the password before storing it
$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

// Insert admin credentials into the database
$sql = "INSERT INTO admin (admin_email, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $admin_email, $hashed_password);
$stmt->execute();

echo "Admin user added successfully!";
// Close the connection
$stmt->close();
$conn->close();
?>
