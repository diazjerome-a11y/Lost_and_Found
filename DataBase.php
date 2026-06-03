<?php
// DataBase.php
// This file connects PHP to the MySQL database

$host = "localhost";     // database server
$username = "root";     // default MySQL username in XAMPP
$password = "";         // default MySQL password in XAMPP (empty)
$database = "lostfound_db";  // database name

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check if connection failed
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8");


?>