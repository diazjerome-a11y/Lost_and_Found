<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

/* ADD ITEM */
if (isset($_POST['add_item'])) {
    $item = mysqli_real_escape_string($conn,$_POST['item_name']);
    mysqli_query($conn,"INSERT INTO item_categories (item_name) VALUES ('$item')");
}

/* DELETE ITEM */
if (isset($_GET['delete_item'])) {
    $id = (int)$_GET['delete_item'];
    mysqli_query($conn,"DELETE FROM item_categories WHERE id=$id");
}

/* ADD COLOR */
if (isset($_POST['add_color'])) {
    $color = mysqli_real_escape_string($conn,$_POST['color_name']);
    mysqli_query($conn,"INSERT INTO item_colors (color_name) VALUES ('$color')");
}

/* DELETE COLOR */
if (isset($_GET['delete_color'])) {
    $id = (int)$_GET['delete_color'];
    mysqli_query($conn,"DELETE FROM item_colors WHERE id=$id");
}

/* ADD LOCATION */
if (isset($_POST['add_location'])) {
    $location = mysqli_real_escape_string($conn,$_POST['location_name']);
    mysqli_query($conn,"INSERT INTO item_locations (location_name) VALUES ('$location')");
}

/* DELETE LOCATION */
if (isset($_GET['delete_location'])) {
    $id = (int)$_GET['delete_location'];
    mysqli_query($conn,"DELETE FROM item_locations WHERE id=$id");
}

$items = mysqli_query($conn,"SELECT * FROM item_categories");
$colors = mysqli_query($conn,"SELECT * FROM item_colors");
$locations = mysqli_query($conn,"SELECT * FROM item_locations");


/* ADD COLLEGE */
if (isset($_POST['add_college'])) {
    $college = mysqli_real_escape_string($conn,$_POST['college_name']);
    mysqli_query($conn,"INSERT INTO colleges (college_name) VALUES ('$college')");
}

/* DELETE COLLEGE */
if (isset($_GET['delete_college'])) {
    $id = (int)$_GET['delete_college'];
    mysqli_query($conn,"DELETE FROM colleges WHERE id=$id");
}

/* ADD COURSE */
if (isset($_POST['add_course'])) {
    $course = mysqli_real_escape_string($conn,$_POST['course_name']);
    $college_id = (int)$_POST['college_id'];

    mysqli_query($conn,"
        INSERT INTO courses (course_name, college_id)
        VALUES ('$course','$college_id')
    ");
}

/* DELETE COURSE */
if (isset($_GET['delete_course'])) {
    $id = (int)$_GET['delete_course'];
    mysqli_query($conn,"DELETE FROM courses WHERE id=$id");
}

$colleges = mysqli_query($conn,"SELECT * FROM colleges");

$courses = mysqli_query($conn,"
SELECT courses.id,
       courses.course_name,
       colleges.college_name
FROM courses
JOIN colleges ON courses.college_id = colleges.id
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>System Settings</title>
<link rel="stylesheet" href="style.css">

<style>

body{
display:block !important;
background:#f1f5f9;
margin:0;
}

.wrapper{
display:flex;
min-height:100vh;
}

.sidebar{
width:220px;
background:#0f172a;
color:#fff;
padding:20px;
position: sticky;
top: 0;
height: 100vh;
overflow-y: auto;
}

.sidebar a{
display:block;
color:#e5e7eb;
text-decoration:none;
padding:10px 12px;
border-radius:8px;
margin-bottom:6px;
}

.sidebar a:hover,
.sidebar a.active{
background:rgba(255,255,255,0.12);
}

.main{
flex:1;
padding:30px 40px;
}

.settings-card{
background:#fff;
border-radius:14px;
padding:20px;
box-shadow:0 10px 25px rgba(0,0,0,0.08);
margin-bottom:25px;
max-width:600px;
}

.settings-card h2{
margin-top:0;
}

.setting-row{
display:flex;
justify-content:space-between;
align-items:center;
padding:8px 0;
border-bottom:1px solid #eee;
}

.delete-btn{
color:#dc2626;
text-decoration:none;
font-weight:bold;
}

.add-form{
margin-top:10px;
display:flex;
gap:10px;
}

.add-form input{
flex:1;
padding:8px;
border-radius:6px;
border:1px solid #ccc;
}

.add-form button{
background:#2563eb;
color:white;
border:none;
padding:8px 12px;
border-radius:6px;
cursor:pointer;
}

.sidebar a.active {
  background: linear-gradient(90deg, rgba(37,99,235,0.25), rgba(37,99,235,0.05));
  box-shadow: inset 0 0 0 1px rgba(37,99,235,.4);
}

</style>
</head>

<body>

<div class="wrapper">

<div class="sidebar">

<h2>🛠 Admin Panel</h2>

<a href="admin_dashboard.php">🏠 Dashboard</a>
<a href="admin_review.php">📝 Review Reports</a>
<a href="admin_claims.php">🔐 Claim Requests</a>
<a href="public_feed.php">🌍 Public Feed</a>
<a href="admin_settings.php" class="active">⚙ System Settings</a>
<a href="monthly_report.php" class="action-btn">📄Monthly Report</a>
<a href="login.php">🚪 Logout</a>

</div>

<div class="main">

<h1>⚙ System Settings</h1>

<!-- ITEMS -->
<div class="settings-card">

<h2>Items</h2>

<?php while($row = mysqli_fetch_assoc($items)): ?>

<div class="setting-row">
<span><?= htmlspecialchars($row['item_name']) ?></span>

<a class="delete-btn"
href="?delete_item=<?= $row['id'] ?>">
❌ Delete
</a>

</div>

<?php endwhile; ?>

<form method="POST" class="add-form">

<input type="text" name="item_name" placeholder="Add new item" required>

<button type="submit" name="add_item">
+ Add Item
</button>

</form>

</div>


<!-- COLORS -->
<div class="settings-card">

<h2>Colors</h2>

<?php while($row = mysqli_fetch_assoc($colors)): ?>

<div class="setting-row">
<span><?= htmlspecialchars($row['color_name']) ?></span>

<a class="delete-btn"
href="?delete_color=<?= $row['id'] ?>">
❌ Delete
</a>

</div>

<?php endwhile; ?>

<form method="POST" class="add-form">

<input type="text" name="color_name" placeholder="Add new color" required>

<button type="submit" name="add_color">
+ Add Color
</button>

</form>

</div>


<!-- LOCATIONS -->
<div class="settings-card">

<h2>Locations</h2>

<?php while($row = mysqli_fetch_assoc($locations)): ?>

<div class="setting-row">
<span><?= htmlspecialchars($row['location_name']) ?></span>

<a class="delete-btn"
href="?delete_location=<?= $row['id'] ?>">
❌ Delete
</a>

</div>

<?php endwhile; ?>

<form method="POST" class="add-form">

<input type="text" name="location_name" placeholder="Add new location" required>

<button type="submit" name="add_location">
+ Add Location
</button>

</form>

</div>

<!-- COLLEGES -->
<div class="settings-card">

<h2>Colleges</h2>

<?php while($row = mysqli_fetch_assoc($colleges)): ?>

<div class="setting-row">

<span><?= htmlspecialchars($row['college_name']) ?></span>

<a class="delete-btn"
href="?delete_college=<?= $row['id'] ?>">
❌ Delete
</a>

</div>

<?php endwhile; ?>

<form method="POST" class="add-form">

<input type="text" name="college_name" placeholder="Add new college" required>

<button type="submit" name="add_college">
+ Add College
</button>

</form>

</div>

<!-- COURSES -->
<div class="settings-card">

<h2>Courses</h2>

<?php while($row = mysqli_fetch_assoc($courses)): ?>

<div class="setting-row">

<span>
<?= htmlspecialchars($row['course_name']) ?>
<small style="color:#64748b;">
(<?= htmlspecialchars($row['college_name']) ?>)
</small>
</span>

<a class="delete-btn"
href="?delete_course=<?= $row['id'] ?>">
❌ Delete
</a>

</div>

<?php endwhile; ?>

<form method="POST" class="add-form">

<select name="college_id" required>

<option value="">Select College</option>

<?php
$collegeList = mysqli_query($conn,"SELECT * FROM colleges");

while($c = mysqli_fetch_assoc($collegeList)){
?>

<option value="<?= $c['id'] ?>">
<?= htmlspecialchars($c['college_name']) ?>
</option>

<?php } ?>

</select>

<input type="text" name="course_name" placeholder="Add new course" required>

<button type="submit" name="add_course">
+ Add Course
</button>

</form>

</div>


</div>

</div>

</body>
</html>