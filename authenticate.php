<?php
session_start();
require_once "database.php";

$login = $_POST['login'];
$password = $_POST['password'];

$result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$login' OR username = '$login'");

if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);

    if (password_verify($password, $user['password'])) {

        // Reset session
        session_unset();

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // 'admin' or 'user'

        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit;

    } else {
        header("Location: login.php?error=1");
        exit;
    }
} else {
    header("Location: login.php?error=1");
    exit;
}
