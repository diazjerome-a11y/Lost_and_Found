
<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Count returned items
$returnedQuery = mysqli_query($conn,"
SELECT COUNT(*) AS total_returned
FROM reports
WHERE user_id='$userId'
AND type='found'
AND status='claimed'
");
$returnedData = mysqli_fetch_assoc($returnedQuery);
$returnedCount = $returnedData['total_returned'];

// Count lost reports
$lostQuery = mysqli_query($conn,"
SELECT COUNT(*) AS total_lost
FROM reports
WHERE user_id='$userId'
AND type='lost'
");
$lostData = mysqli_fetch_assoc($lostQuery);
$lostCount = $lostData['total_lost'];

// Count found reports
$foundQuery = mysqli_query($conn,"
SELECT COUNT(*) AS total_found
FROM reports
WHERE user_id='$userId'
AND type='found'
");
$foundData = mysqli_fetch_assoc($foundQuery);
$foundCount = $foundData['total_found'];

// Determine reputation
$reputation = "New Member";

if ($returnedCount >= 10) {
    $reputation = "Campus Hero 🏆";
} elseif ($returnedCount >= 5) {
    $reputation = "Trusted Finder ⭐";
} elseif ($returnedCount >= 1) {
    $reputation = "Helpful Student 👍";
}

$result = mysqli_query($conn,"
SELECT 
u.username,
u.email,
u.profile_picture,
u.first_name,
u.last_name,
u.contact_number,
c.college_name,
cr.course_name
FROM users u
LEFT JOIN colleges c ON u.college_id = c.id
LEFT JOIN courses cr ON u.course_id = cr.id
WHERE u.id='$userId'
");
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>My Profile | NISU Lost and Found Portal</title>
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

.profile-container{
display:flex;
justify-content:center;
margin-top:20px;
}

.profile-card{
background:#ffffff;
border-radius:16px;
padding:30px;
width:500px; /* Increased width for better layout */
text-align:center;
box-shadow:0 10px 25px rgba(0,0,0,0.08);
position:relative;
overflow:hidden;
}

.profile-card::before{
content:'';
position:absolute;
top:0;
left:0;
right:0;
height:100px;
background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);
border-radius:16px 16px 0 0;
}

.profile-pic{
width:130px;
height:130px;
border-radius:50%;
object-fit:cover;
border:5px solid #fff;
margin-bottom:15px;
position:relative;
z-index:1;
}

.profile-name{
font-size:24px; /* Larger font */
font-weight:700;
color:#0f172a;
margin-bottom:5px;
position:relative;
z-index:1;
}

.profile-email{
color:#64748b;
font-size:16px; /* Slightly larger */
margin-bottom:20px;
position:relative;
z-index:1;
}

.upload-input{
margin-top:10px;
}

.upload-btn{
background:#2563eb;
color:white;
border:none;
padding:12px 20px; /* Larger padding */
border-radius:8px;
font-weight:600;
cursor:pointer;
margin-top:10px;
transition:background 0.3s;
}

.upload-btn:hover{
background:#1d4ed8;
}

.stats-box{
margin-top:20px;
background:#f8fafc;
border-radius:10px;
padding:15px;
font-size:14px;
color:#334155;
}
/* Active link glow */
.sidebar a.active {
  background: linear-gradient(90deg, rgba(37,99,235,0.25), rgba(37,99,235,0.05));
  box-shadow: inset 0 0 0 1px rgba(37,99,235,.4);
}

.user-details{
margin-top:20px;
text-align:left;
background:#f9fafb;
padding:20px;
border-radius:12px;
box-shadow:0 4px 12px rgba(0,0,0,0.05);
position:relative;
z-index:1;
}

.user-details div{
margin-bottom:10px;
font-size:14px;
color:#475569;
}

.user-details strong{
color:#0f172a;
}

.stats-grid{
display:grid;
grid-template-columns:repeat(2,1fr);
gap:15px;
margin-top:20px;
position:relative;
z-index:1;
}

.stat-card{
background:#f8fafc;
padding:20px; /* More padding */
border-radius:12px;
text-align:center;
box-shadow:0 4px 12px rgba(0,0,0,0.05);
transition:transform 0.2s;
}

.stat-card:hover{
transform:translateY(-5px);
}

.stat-card h3{
margin:5px 0;
font-size:28px; /* Larger numbers */
color:#0f172a;
}

</style>
</head>

<body>

<div class="wrapper">

<div class="sidebar">

<h2>👤 My Account</h2>

<?php $current = basename($_SERVER['PHP_SELF']); ?>

<a href="user_dashboard.php">🏠 Dashboard</a>
<a href="profile.php" class="active">👤 My Profile</a>
<a href="my_reports.php">📋 My Reports</a>
<a href="public_feed.php">🌍 Public Feed</a>
<a href="matched_items.php">🔁 Matched Items</a>
<a href="claimed_items.php">📦 Claimed Items</a>
<a href="login.php">🚪 Logout</a>

</div>

<div class="main">

<h1>My Profile</h1>

<div class="profile-container">

<div class="profile-card">

<img class="profile-pic"
src="profile_pictures/<?php echo $user['profile_picture']; ?>">

<div class="profile-name">
<?php echo $user['first_name']." ".$user['last_name']; ?>
</div>

<div class="profile-email">
<?php echo $user['email']; ?>
</div>

<div class="user-details">

<div>
<strong>First Name:</strong>
<?php echo htmlspecialchars($user['first_name']); ?>
</div>

<div>
<strong>Last Name:</strong>
<?php echo htmlspecialchars($user['last_name']); ?>
</div>

<div>
<strong>Contact:</strong>
<?php echo htmlspecialchars($user['contact_number']); ?>
</div>

<div>
<strong>College:</strong>
<?php echo htmlspecialchars($user['college_name']); ?>
</div>

<div>
<strong>Course:</strong>
<?php echo htmlspecialchars($user['course_name']); ?>
</div>

</div>

<form action="upload_profile.php" method="POST" enctype="multipart/form-data">

<input class="upload-input" type="file" name="profile_picture" required>

<br>

<button class="upload-btn">Upload New Picture</button>

</form>

<div class="stats-grid">

  <div class="stat-card">
    📦 Returned Items  
    <h3><?php echo $returnedCount; ?></h3>
  </div>

  <div class="stat-card">
    🔎 Lost Reports  
    <h3><?php echo $lostCount; ?></h3>
  </div>

  <div class="stat-card">
    📍 Found Reports  
    <h3><?php echo $foundCount; ?></h3>
  </div>

  <div class="stat-card" style="background:#fef3c7;">
    🏆 Reputation  
    <h3><?php echo $reputation; ?></h3>
  </div>

</div>

</div>

</div>

</div>

</body>
</html>