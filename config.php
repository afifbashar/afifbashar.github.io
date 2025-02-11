<?php
define('DB_SERVER', '0.0.0.0:3306');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'prescription_system');

// Attempt to connect to MySQL database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($conn === false){
die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Set character set to utf8mb4
mysqli_set_charset($conn, "utf8mb4");

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
session_start();
}

// Function to check if user is logged in
function isLoggedIn() {
return isset($_SESSION['user_id']);
}

// Function to redirect if not logged in
function requireLogin() {
if (!isLoggedIn()) {
header("Location: login.php");
exit;
}
}
?>