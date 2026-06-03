<?php
$success = isset($_GET['success']);
$error = isset($_GET['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>NISU Lost & Found Login</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="style.css?v=2">


</head>

<body class="login-page">

<div class="login-wrapper">

<!-- LEFT SIDE -->
<div class="login-image">

<div class="login-text">
<h1>NISU Lost & Found</h1>
<p>Helping students recover their lost belongings quickly and securely.</p>
</div>

</div>


<!-- RIGHT SIDE -->
<div class="login-form">

<div class="login-card">

<div class="login-logo">
<img src="logo.png">
</div>

<?php if ($success): ?>
<div class="alert success">✅ Login successful!</div>
<?php elseif ($error): ?>
<div class="alert error">❌ Invalid email or password.</div>
<?php endif; ?>

<form method="POST" action="authenticate.php">

<label>Email or Username</label>
<input type="text" name="login" placeholder="Enter your email or username" required>

<label>Password</label>
<input type="password" name="password" placeholder="Enter your password" required>

<button type="submit" class="login-btn">Login</button>

</form>

<div class="options">
<a href="register.php" class="secondary-btn">Create an account</a>
</div>

<div class="forgot-password">
<a href="forgot_password.php">Forgot Password?</a>
</div>

<div class="divider">— or login with —</div>

<div class="social-buttons">

<button type="button" class="btn facebook">
<i class="fa-brands fa-facebook-f"></i>
</button>

<button type="button" class="btn google">
<i class="fa-brands fa-google"></i>
</button>

<button type="button" class="btn x">
<i class="fa-brands fa-twitter"></i>
</button>

<button type="button" class="btn whatsapp">
<i class="fa-brands fa-whatsapp"></i>
</button>

</div>

</div>
</div>

</div>

</body>
</html>