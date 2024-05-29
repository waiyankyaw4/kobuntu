<?php
// MySQL database credentials
$host = "localhost"; // or your host name
$username = "wai"; // your MySQL username
$password = "Wyk"; // your MySQL password
$database = "yan"; // your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
