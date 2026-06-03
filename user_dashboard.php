<?php

session_start();
require_once "database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Fetch counts (adjust queries if your table names differ)
$userId = $_SESSION['user_id'];

$lostCountRes  = mysqli_query($conn, "SELECT COUNT(*) AS total FROM reports WHERE user_id='$userId' AND type='lost'");
$foundCountRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM reports WHERE user_id='$userId' AND type='found'");
$matchCountRes = mysqli_query($conn, "SELECT COUNT(*) AS total FROM reports WHERE user_id='$userId' AND status='matched'");

$lostCount  = mysqli_fetch_assoc($lostCountRes)['total'] ?? 0;
$foundCount = mysqli_fetch_assoc($foundCountRes)['total'] ?? 0;
$matchCount = mysqli_fetch_assoc($matchCountRes)['total'] ?? 0;

$username = $_SESSION['username'] ?? 'User';

$recentReports = mysqli_query($conn, "
    SELECT item_name, type, created_at
    FROM reports
    WHERE user_id = '$userId'
    ORDER BY created_at DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard | NISU Lost and Found Portal</title>
<link rel="stylesheet" href="style.css">

<style>
/* Override login centering for app pages */
body {
  display: block !important;
  background: #f1f5f9;
  margin: 0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Layout */
.wrapper { display:flex; min-height:100vh; }
.sidebar {
  width:220px;
  background:#0f172a;
  color:#fff;
  padding:20px;
  position: sticky;
  top: 0;
  height: 100vh;
  overflow-y: auto;
}
.sidebar .brand {
  text-align:center;
  margin-bottom:16px;
}
.sidebar .brand img {
  width:64px; height:auto; display:block; margin:0 auto 6px;
}
.sidebar .brand h2 {
  font-size:16px; margin:0; color:#fff;
}
.sidebar a {
  display:block;
  color:#e5e7eb;
  text-decoration:none;
  padding:10px 12px;
  border-radius:8px;
  margin-bottom:6px;
}
.sidebar a:hover, .sidebar a.active {
  background: rgba(255,255,255,0.12);
  color:#fff;
}

.main {
  flex:1;
  padding:16px 32px; /* reduced top padding to remove big empty space */
}

/* Header */
.dashboard-header {
  display:flex;
  align-items:center;
  justify-content:space-between;
  margin-bottom:12px; /* tighter spacing */
}
.app-title {
  font-size:20px;
  font-weight:700;
  color:#0f172a;
}
.app-subtitle {
  font-size:12px;
  color:#64748b;
}
.welcome-text {
  font-weight:600;
  color:#334155;
}

/* Stats */
.stats-grid {
  display:grid;
  grid-template-columns: repeat(3, minmax(180px, 1fr));
  gap:14px;
  margin-bottom:14px;
}
.stat-card {
  background:#fff;
  border-radius:14px;
  padding:14px 16px;
  box-shadow:0 10px 22px rgba(0,0,0,0.06);
  transition: transform 0.2s, box-shadow 0.2s;
  border-left:4px solid #2563eb;
}
.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(0,0,0,0.12);
}
.stat-card h4 {
  margin:0;
  font-size:13px;
  color:#475569;
}
.stat-card .count {
  font-size:26px;
  font-weight:800;
  color:#2563eb;
  margin-top:6px;
}

/* Actions */
.actions-row {
  display:flex;
  gap:12px;
  margin-bottom:14px;
  flex-wrap:wrap;
}
.action-btn {
  flex:1;
  min-width:220px;
  padding:12px 16px;
  border-radius:12px;
  font-weight:700;
  text-decoration:none;
  text-align:center;
  color:#fff;
  background:#2563eb;
  transition: background 0.3s, transform 0.2s;
}
.action-btn.secondary {
  background:#10b981;
}
.action-btn:hover { 
  opacity:0.95; 
  transform: translateY(-2px);
}

/* Activity */
.activity-card {
  background:#ffffff;
  border-radius:14px;
  padding:16px;
  box-shadow:0 10px 22px rgba(0,0,0,0.06);
  transition: box-shadow 0.2s;
}
.activity-card:hover {
  box-shadow: 0 15px 35px rgba(0,0,0,0.12);
}
.activity-card h3 {
  margin-top:0;
  font-size:16px;
}
.activity-card p {
  color:#64748b;
  margin:6px 0 0;
}

/* ===== Sidebar polish ===== */
.sidebar {
  background: linear-gradient(180deg, #0f172a 0%, #0b1220 100%);
  box-shadow: 4px 0 20px rgba(0,0,0,0.2);
}

.sidebar h2 {
  display: flex;
  align-items: center;
  gap: 8px;
  white-space: nowrap;      /* keep Admin Panel in one line */
  font-weight: 700;
  letter-spacing: .3px;
  margin-bottom: 28px;
}

.sidebar a {
  transition: all .2s ease;
}

.sidebar a:hover {
  transform: translateX(4px);
}

/* Active link glow */
.sidebar a.active {
  background: linear-gradient(90deg, rgba(37,99,235,0.25), rgba(37,99,235,0.05));
  box-shadow: inset 0 0 0 1px rgba(37,99,235,.4);
}

/* ===== Main content polish ===== */
.main-content {
  background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
}

/* Header */
.header h1 {
  display: flex;
  align-items: center;
  gap: 8px;
}

/* ===== Cards animation ===== */
.stat-card,
.section-card {
  transition: transform .2s ease, box-shadow .2s ease;
}

.stat-card:hover,
.section-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 14px 30px rgba(0,0,0,0.12);
}

/* ===== Table polish (Pending Reports) ===== */
table tr:hover {
  background: #f8fafc;
}

table th {
  font-weight: 600;
  letter-spacing: .4px;
}

table td a {
  text-decoration: none;
  transition: opacity .2s ease;
}

table td a:hover {
  opacity: .7;
}

/* ===== Buttons (Approve / Reject) ===== */
a.approve-btn {
  color: #16a34a;
  font-weight: 600;
}

a.reject-btn {
  color: #dc2626;
  font-weight: 600;
}

/* Sidebar brand (logo + title) */
.sidebar .brand {
  display: flex;
  flex-direction: column;
  align-items: center;     /* centers logo + text */
  text-align: center;
  margin-bottom: 24px;
}

.sidebar .brand img {
  width: 64px;             /* adjust if too big/small */
  height: auto;
  margin-bottom: 8px;
}

.sidebar .brand-title {
  font-size: 18px;         /* readable but fits */
  font-weight: 600;
  line-height: 1.2;
  color: #fff;
  max-width: 180px;       /* prevents overflow */
}

/* Activity Items */
.activity-item {
  display:flex;
  align-items:center;
  gap:12px;
  padding:10px 0;
  border-bottom:1px dashed #e5e7eb;
}

.activity-item:last-child{
  border-bottom:none;
}

.activity-icon{
  width:36px;
  height:36px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:10px;
  font-size:16px;
  color:#fff;
}

.lost-icon{
  background:#ef4444;
}

.found-icon{
  background:#10b981;
}

.activity-content strong{
  display:block;
  color:#0f172a;
}

.activity-type{
  font-size:11px;
  font-weight:700;
  color:#64748b;
}

.activity-date{
  font-size:11px;
  color:#94a3b8;
}

/* Activity list */
.activity-item{
  display:flex;
  align-items:center;
  gap:12px;
  padding:12px 0;
  border-bottom:1px dashed #e5e7eb;
}

.activity-item:last-child{
  border-bottom:none;
}

/* Icon */
.activity-icon{
  width:38px;
  height:38px;
  border-radius:10px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:16px;
  color:white;
}

.lost-icon{
  background:#ef4444;
}

.found-icon{
  background:#10b981;
}

/* Text */
.activity-title{
  font-weight:700;
  color:#0f172a;
}

.activity-type{
  font-size:11px;
  font-weight:700;
  color:#64748b;
}

.activity-time{
  font-size:11px;
  color:#94a3b8;
}

/* Additional enhancements */
.stats-grid {
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
}
.activity-card {
  max-height: 400px;
  overflow-y: auto;
}
</style>
</head>
<body>

<div class="wrapper">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="brand">
      <!-- Put your logo file here -->
      <img src="nisu_logo1.jpg" alt="NISU Logo">
      <h2>NISU Lost & Found</h2>
    </div>
    
    <?php $current = basename($_SERVER['PHP_SELF']); ?>
    
    <a href="user_dashboard.php" class="<?= $current == 'user_dashboard.php' ? 'active' : '' ?>">🏠 Dashboard</a>
    <a href="profile.php" class="<?= $current == 'profile.php' ? 'active' : '' ?>">👤 My Profile</a>
    <a href="my_reports.php" class="<?= $current == 'my_reports.php' ? 'active' : '' ?>">📋 My Reports</a>
    <a href="public_feed.php" class="<?= $current == 'public_feed.php' ? 'active' : '' ?>">🌍 Public Feed</a>
    <a href="matched_items.php" class="<?= $current == 'matched_items.php' ? 'active' : '' ?>">🔁 Matched Items</a>
    <a href="claimed_items.php" class="<?= $current == 'claimed_items.php' ? 'active' : '' ?>"> 📦 Claimed Items</a>
    <a href="login.php">🚪 Logout</a>
  </div>

  <!-- Main -->
  <div class="main">
    <div class="dashboard-header">
        <div>
            <div class="app-title">NISU Lost and Found Portal</div>
            <div class="app-subtitle">Northern Iloilo State University</div>
          </div>
          <div class="welcome-text">Welcome, <?= htmlspecialchars($username) ?> 👋</div>
        </div>

    <div class="stats-grid">
      <div class="stat-card">
        <h4>My Lost Reports</h4>
        <div class="count"><?= (int)$lostCount ?></div>
      </div>
      <div class="stat-card">
        <h4>My Found Reports</h4>
        <div class="count"><?= (int)$foundCount ?></div>
      </div>
      <div class="stat-card">
        <h4>Matched Items</h4>
        <div class="count"><?= (int)$matchCount ?></div>
      </div>
    </div>

    <div class="actions-row">
      <a href="report_lost.php" class="action-btn">➕ Report Lost Item</a>
      <a href="report_found.php" class="action-btn secondary">➕ Report Found Item</a>
    </div>

    <div class="activity-card">
          <h3>Recent Activity</h3>

        <?php if(mysqli_num_rows($recentReports) > 0): ?>

        <?php while($row = mysqli_fetch_assoc($recentReports)): ?>

        <div class="activity-item">

          <div class="activity-icon <?= $row['type'] === 'lost' ? 'lost-icon' : 'found-icon' ?>">
            <?= $row['type'] === 'lost' ? '🔎' : '📍' ?>
          </div>

            <div class="activity-details">
                <div class="activity-title">
                  <?= htmlspecialchars($row['item_name']) ?>
                </div>

                <div class="activity-type">
                  <?= strtoupper($row['type']) ?>
                </div>

                <div class="activity-time">
                  <?= date("M d, Y • h:i A", strtotime($row['created_at'])) ?>
                </div>
            </div>

        </div>

        <?php endwhile; ?>

        <?php else: ?>

        <p style="color:#64748b;">You haven't posted any reports yet.</p>

        <?php endif; ?>

    </div>
  </div>
</div>

</body>
</html>