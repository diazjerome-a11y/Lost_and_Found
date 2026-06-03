<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$matchedId = (int) $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Prevent duplicate claim
    $check = mysqli_query($conn, "
        SELECT id FROM claims 
        WHERE matched_id='$matchedId'
        AND claimant_user_id='$userId'
    ");

    if (mysqli_num_rows($check) == 0) {

        mysqli_query($conn, "
            INSERT INTO claims (matched_id, claimant_user_id, message)
            VALUES ('$matchedId', '$userId', '$message')
        ");
    }

    header("Location: matched_items.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Request Claim</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div style="max-width:500px;margin:100px auto;background:white;padding:25px;border-radius:10px;">
<h2>🔐 Claim Request</h2>

<form method="POST">
<textarea name="message" required 
style="width:100%;height:120px;padding:10px;border-radius:6px;border:1px solid #ccc;"
placeholder="Explain why this item is yours. Include identifying details."></textarea>

<button type="submit"
style="margin-top:10px;background:#2563eb;color:white;padding:10px 14px;border:none;border-radius:6px;">
Submit Claim
</button>
</form>

</div>

</body>
</html>