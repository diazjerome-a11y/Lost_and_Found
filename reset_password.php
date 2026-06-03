<?php
// Connect to the database
require_once "database.php";

// Get the email from the URL
$email = $_GET['email'] ?? "";

// Variable to store error message
$message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get email from hidden input field
    $email = $_POST['email'];

    // Get new password entered by the user
    $newPassword = $_POST['new_password'];

    // Get confirm password entered by the user
    $confirmPassword = $_POST['confirm_password'];

    // Check if new password and confirm password are the same
    if ($newPassword !== $confirmPassword) {

        // If passwords do not match, show error
        $message = "Passwords do not match.";

    } else {

        // Hash the new password before saving to database
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the user's password in the database
        mysqli_query($conn, "UPDATE users SET password = '$hashedPassword' WHERE email = '$email'");

        // After successful reset, go back to login page
        header("Location: login.php?success=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <!-- Use the same CSS design as login page -->
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Main box for reset password form -->
<div class="login-card">

  <!-- Page title -->
  <h2 class="login-title">Reset Password</h2>

  <!-- Short instruction -->
  <p>Enter your new password.</p>

  <!-- Show error message if passwords do not match -->
  <?php if ($message): ?>
    <div class="alert error"><?php echo $message; ?></div>
  <?php endif; ?>

  <!-- Form to reset password -->
  <form method="POST">

    <!-- Hidden email field to know which user to update -->
    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

    <!-- New password label -->
    <label>New Password</label>

    <!-- New password input field -->
    <input type="password" name="new_password" placeholder="Enter new password" required>

    <!-- Confirm password label -->
    <label>Confirm Password</label>

    <!-- Confirm password input field -->
    <input type="password" name="confirm_password" placeholder="Confirm new password" required>

    <!-- Button to submit new password -->
    <button type="submit" class="login-btn">Reset Password</button>
  </form>

  <!-- Link to go back to login page -->
  <div class="options">
    <a href="login.php">Back to Login</a>
  </div>

</div>

</body>
</html>
