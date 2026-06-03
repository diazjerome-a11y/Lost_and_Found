<?php

session_start();
require_once "database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$adminName = $_SESSION['username'];

/* Date range */
$start = $_GET['start'] ?? date("Y-m-01");
$end = $_GET['end'] ?? date("Y-m-t");

/* Fetch reports with course and profile picture */
$reports = mysqli_query($conn, "

SELECT 
users.username,
users.profile_picture,
courses.course_name AS course,
reports.item_name,
reports.type,
reports.status,
reports.created_at

FROM reports

JOIN users 
ON reports.user_id = users.id

LEFT JOIN courses
ON users.course_id = courses.id

WHERE DATE(reports.created_at)
BETWEEN '$start' AND '$end'

ORDER BY reports.created_at DESC

");

if(!$reports){
die('SQL ERROR: ' . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Monthly Report</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

body{
font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
margin:0;
padding:40px;
background:linear-gradient(135deg,#f1f5f9 0%,#e2e8f0 100%);
min-height:100vh;
color:#111;
}

/* TOPBAR */

.report-topbar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
}

/* BUTTONS */

.btn-back,
.btn-print{
display:inline-flex;
align-items:center;
gap:6px;
padding:12px 18px;
border-radius:10px;
font-size:14px;
font-weight:600;
text-decoration:none;
color:white;
transition:.3s;
}

.btn-back{
background:#2563eb;
box-shadow:0 4px 12px rgba(37,99,235,.3);
}

.btn-print{
background:#10b981;
border:none;
cursor:pointer;
box-shadow:0 4px 12px rgba(16,185,129,.3);
}

.btn-back:hover,
.btn-print:hover{
transform:translateY(-2px);
}

/* HEADER */

.report-header{
background:white;
padding:30px;
border-radius:16px;
text-align:center;
margin-bottom:30px;
box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.report-logo{
width:90px;
height:auto;
}

.report-header h1{
margin:15px 0 5px;
font-size:24px;
font-weight:700;
color:#0f172a;
}

.report-header h2{
margin:0;
font-size:20px;
color:#64748b;
}

.report-header p{
margin-top:10px;
font-size:15px;
color:#475569;
}

/* TABLE */

table{
width:100%;
border-collapse:collapse;
background:white;
border-radius:12px;
overflow:hidden;
box-shadow:0 10px 25px rgba(0,0,0,.08);
}

th,
td{
padding:14px;
border:1px solid #e5e7eb;
text-align:left;
}

th{
background:#f3f4f6;
font-size:12px;
text-transform:uppercase;
letter-spacing:.5px;
color:#374151;
}

tr:nth-child(even){
background:#f9fafb;
}

tr:hover{
background:#f1f5f9;
}

/* PROFILE PICTURE */

.profile-pic{
width:55px;
height:55px;
border-radius:50%;
object-fit:cover;
border:2px solid #d1d5db;
}

/* FOOTER */

.report-footer{
margin-top:40px;
background:white;
padding:20px;
border-radius:12px;
text-align:center;
box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.report-footer hr{
border:none;
border-top:1px solid #e5e7eb;
margin:20px 0;
}

.report-footer p{
color:#64748b;
}

.prepared{
font-weight:600;
color:#111827;
}

/* PRINT */

@media print{

.report-topbar{
display:none;
}

body{
background:white;
padding:20px;
}

}

</style>

</head>

<body>

<div class="report-topbar">

<a href="admin_dashboard.php" class="btn-back">
<i class="fas fa-arrow-left"></i>
Dashboard
</a>

<button onclick="window.print()" class="btn-print">
<i class="fas fa-print"></i>
Print Report
</button>

</div>

<div class="report-header">

<img src="logo.png" class="report-logo">

<h1>NISU LOST AND FOUND PORTAL</h1>

<h2>Monthly Report</h2>

<p>
<?= date("M d, Y", strtotime($start)) ?>
-
<?= date("M d, Y", strtotime($end)) ?>
</p>

</div>

<table>

<tr>
<th>Profile</th>
<th>User</th>
<th>Course</th>
<th>Item</th>
<th>Type</th>
<th>Status</th>
<th>Date</th>
</tr>

<?php if(mysqli_num_rows($reports) > 0): ?>

<?php while($row = mysqli_fetch_assoc($reports)): ?>

<tr>

<td>

<?php

if(!empty($row['profile_picture'])){

$img = "profile_pictures/" . $row['profile_picture'];

}else{

$img = "https://cdn-icons-png.flaticon.com/512/149/149071.png";

}

?>

<img src="<?= $img ?>" class="profile-pic">

</td>

<td>
<?= htmlspecialchars($row['username']) ?>
</td>

<td>
<?= !empty($row['course']) 
? htmlspecialchars($row['course']) 
: 'No Course' ?>
</td>

<td>
<?= htmlspecialchars($row['item_name']) ?>
</td>

<td>
<?= ucfirst($row['type']) ?>
</td>

<td>
<?= ucfirst($row['status']) ?>
</td>

<td>
<?= date("M d, Y", strtotime($row['created_at'])) ?>
</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="7" style="text-align:center; padding:30px;">
No reports found.
</td>

</tr>

<?php endif; ?>

</table>

<div class="report-footer">

<hr>

<p>This is a system generated report.</p>

<p class="prepared">
Prepared by:
<strong><?= $_SESSION['username'] ?></strong>
</p>

</div>

</body>
</html>