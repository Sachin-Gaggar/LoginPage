<?php
// Database configuration
$hostname = 'localhost';
$username = root;
$password = 'root';
$database = 'login';
// Establish database connection
$conn = new mysqli($hostname, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
?>