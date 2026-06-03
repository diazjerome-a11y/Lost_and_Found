<?php
// Connect to the database
require_once "database.php";

// Get inputs from the registration form
$username = $_POST['username'];   
$email    = $_POST['email'];      
$password = $_POST['password'];   
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$contact_number = $_POST['contact_number'];
$college_id = $_POST['college_id'];
$course_id = $_POST['course_id'];

// Function to check password strength
function passwordStrength($password) {
    $strength = 0;

    if (strlen($password) >= 8) $strength++;
    if (preg_match('/[A-Z]/', $password)) $strength++;
    if (preg_match('/[a-z]/', $password)) $strength++;
    if (preg_match('/[0-9]/', $password)) $strength++;
    if (preg_match('/[\W]/', $password)) $strength++;

    return $strength;
}

// Check if password is too short
if (strlen($password) < 6) {
    header("Location: register.php?error=Password is too short (minimum 6 characters)");
    exit;
}

// Check if password is weak
if (passwordStrength($password) <= 2) {
    header("Location: register.php?error=Password is weak. Please make it stronger.");
    exit;
}

// Check if email or username already exists
$check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email' OR username = '$username'");
if (mysqli_num_rows($check) > 0) {
    header("Location: register.php?error=Email or Username already exists");
    exit;
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert new user  ✅ FIXED
$query = "INSERT INTO users 
(username, email, password, first_name, last_name, contact_number, college_id, course_id) 
VALUES 
('$username', '$email', '$hashedPassword', '$first_name', '$last_name', '$contact_number', '$college_id', '$course_id')";

$insertSuccess = mysqli_query($conn, $query);

if ($insertSuccess) {
    header("Location: register.php?success=1");
    exit;
} else {
    header("Location: register.php?error=Registration failed");
    exit;
}
?>