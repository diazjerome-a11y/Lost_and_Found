<?php
// Connect to the database
require_once "database.php";

// Variable to store error message
$message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the email entered by the user
    $email = $_POST['email'];

    // Check if the email exists in the users table
    $result = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");

    // If email is found in database
    if (mysqli_num_rows($result) === 1) {

        // If email exists, go to reset password page
        header("Location: reset_password.php?email=" . urlencode($email));
        exit;

    } else {
        // If email is not found, show error message
        $message = "Email not found in the system.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <!-- Use the same CSS design as login page -->
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">   <!-- ✅ THIS FIXES CENTERING -->
  <div class="login-card">

    <!-- Page title -->
    <h2 class="login-title">Forgot Password</h2>

    <!-- Short instruction -->
    <p>Enter your email to reset your password.</p>

    <!-- Show error message if email is not found -->
    <?php if ($message): ?>
      <div class="alert error"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Form to enter email -->
    <form method="POST">

      <!-- Email label -->
      <label>Email</label>

      <!-- Email input field -->
      <input type="email" name="email" placeholder="Enter your email" required>

      <!-- Button to continue -->
      <button type="submit" class="login-btn">Continue</button>
    </form>

    <!-- Link to go back to login page -->
    <div class="options">
      <a href="login.php">Back to Login</a>
    </div>

  </div>
</div>

</body>
</html>
