<?php


require_once "database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Lost and Found Register</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="style.css">

<style>
/* Enhanced form styles */
.login-card {
  width: 420px; /* Wider for better layout */
  background: rgba(255,255,255,0.98);
  padding: 50px 40px;
  border-radius: 24px;
  box-shadow: 0 30px 70px rgba(0,0,0,0.3);
  backdrop-filter: blur(15px);
  border: 1px solid rgba(255,255,255,0.3);
  animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.login-title {
  font-size: 32px;
  margin-bottom: 10px;
  background: linear-gradient(135deg, #667eea, #764ba2);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.subtitle {
  color: #64748b;
  margin-bottom: 30px;
  font-size: 16px;
}

label {
  display: block;
  margin-top: 20px;
  font-weight: 600;
  color: #374151;
  font-size: 14px;
  position: relative;
}

label i {
  margin-right: 8px;
  color: #667eea;
}

input, select {
  width: 100%;
  padding: 16px 18px;
  border-radius: 14px;
  border: 2px solid #e5e7eb;
  margin-top: 8px;
  font-size: 16px;
  transition: all 0.3s ease;
  background: #f9fafb;
}

input:focus, select:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 4px rgba(102,126,234,0.15);
  background: #fff;
}

select {
  cursor: pointer;
}

small {
  display: block;
  margin-top: 6px;
  color: #64748b;
  font-size: 12px;
}

.password-wrapper {
  position: relative;
}

.password-wrapper input {
  padding-right: 18px; /* Adjusted since no toggle */
}

.strength-container {
  width: 100%;
  height: 10px;
  background: #e5e7eb;
  border-radius: 8px;
  margin-top: 10px;
  overflow: hidden;
  box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
}

.strength-bar {
  height: 100%;
  width: 0%;
  border-radius: 8px;
  transition: all 0.3s ease;
  background: linear-gradient(90deg, #ef4444, #f97316, #facc15, #22c55e, #16a34a);
}

.strength-text {
  display: block;
  margin-top: 8px;
  font-weight: 600;
  font-size: 13px;
}

.login-btn {
  width: 100%;
  padding: 18px;
  margin-top: 30px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 14px;
  font-weight: 700;
  font-size: 16px;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(102,126,234,0.3);
}

.login-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 25px rgba(102,126,234,0.4);
}

.forgot-password {
  text-align: center;
  margin-top: 20px;
}

.forgot-password a {
  color: #667eea;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s;
}

.forgot-password a:hover {
  color: #764ba2;
  text-decoration: underline;
}

/* Form groups for better spacing */
.form-group {
  margin-bottom: 20px;
}

/* Success message styling */
.success-message {
  text-align: center;
  padding: 20px;
  background: linear-gradient(135deg, #d1fae5, #a7f3d0);
  border-radius: 14px;
  border-left: 4px solid #10b981;
  margin-bottom: 20px;
}

.success-message h2 {
  color: #065f46;
  margin-bottom: 10px;
}

.success-message p {
  color: #047857;
}
</style>

</head>
<body class="login-page">

<div class="container">
<div class="login-card">

<?php if (isset($_GET['success'])): ?>

<div class="success-message">
<h2 class="login-title">🎉 Registered Successfully!</h2>
<p>Your account has been created. You can now log in.</p>
</div>

<a href="login.php" class="login-btn">Go to Login</a>

<?php else: ?>

<h2 class="login-title"><i class="fas fa-user-plus"></i>Create Account</h2>
<p class="subtitle">Because every lost item deserves to be found.</p>

<form method="POST" action="register_process.php">

<div class="form-group">
<label><i class="fas fa-user"></i>First Name</label>
<input type="text" name="first_name" required>
</div>

<div class="form-group">
<label><i class="fas fa-user"></i>Last Name</label>
<input type="text" name="last_name" required>
</div>

<div class="form-group">
<label><i class="fas fa-at"></i>Username</label>
<input type="text" name="username" required>
</div>

<div class="form-group">
<label><i class="fas fa-envelope"></i>Email Address</label>
<input type="email" name="email" required>
</div>

<div class="form-group">
<label><i class="fas fa-phone"></i>Contact Number</label>
<input type="text" name="contact_number" maxlength="11" required>
<small>Mobile number must be 11 digits (example: 09123456789)</small>
</div>

<div class="form-group">
<label><i class="fas fa-university"></i>College</label>
<select name="college_id" id="collegeSelect" required>
<option value="">Select College</option>
<?php
$colleges = mysqli_query($conn,"SELECT * FROM colleges");
while($college = mysqli_fetch_assoc($colleges)){
?>
<option value="<?= $college['id'] ?>">
<?= htmlspecialchars($college['college_name']) ?>
</option>
<?php } ?>
</select>
</div>

<div class="form-group">
<label><i class="fas fa-graduation-cap"></i>Course</label>
<select name="course_id" id="courseSelect" required>
<option value="">Select Course</option>
</select>
</div>

<div class="form-group">
<label><i class="fas fa-lock"></i>Password</label>
<div class="password-wrapper">
<input type="password" name="password" id="password" required>
</div>
<div class="strength-container">
<div id="strengthBar" class="strength-bar"></div>
</div>
<small id="strengthText" class="strength-text"></small>
</div>

<button type="submit" class="login-btn">Register</button>

</form>

<div class="forgot-password">
<a href="login.php">Already have an account? Sign in</a>
</div>

<?php endif; ?>

</div>
</div>

<script>
/* PASSWORD STRENGTH */
document.addEventListener("DOMContentLoaded",function(){
const passwordInput=document.getElementById("password");
const strengthBar=document.getElementById("strengthBar");
const strengthText=document.getElementById("strengthText");

passwordInput.addEventListener("input",function(){
const val=passwordInput.value;
let strength=0;

if(val.length>=8) strength++;
if(/[A-Z]/.test(val)) strength++;
if(/[a-z]/.test(val)) strength++;
if(/[0-9]/.test(val)) strength++;
if(/[\W]/.test(val)) strength++;

let width=0;
let color="";
let text="";

if(val.length===0){
width=0;text="";
}
else if(strength<=1){
width=20;color="#ef4444";text="Very Weak";
}
else if(strength==2){
width=40;color="#f97316";text="Weak";
}
else if(strength==3){
width=60;color="#facc15";text="Medium";
}
else if(strength==4){
width=80;color="#22c55e";text="Strong";
}
else{
width=100;color="#16a34a";text="Very Strong";
}

strengthBar.style.width=width+"%";
strengthBar.style.background=color;
strengthText.textContent=text;
strengthText.style.color=color;
});
});

/* COLLEGE → COURSE LOADER */
document.getElementById("collegeSelect").addEventListener("change", function(){
const collegeId = this.value;

fetch("load_courses.php?college_id=" + collegeId)
.then(response => response.json())
.then(data => {
const courseSelect = document.getElementById("courseSelect");
courseSelect.innerHTML = '<option value="">Select Course</option>';

data.forEach(course => {
let option = document.createElement("option");
option.value = course.id;
option.textContent = course.course_name;
courseSelect.appendChild(option);
});
});
});
</script>
</body>
</html>