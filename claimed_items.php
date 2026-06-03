<?php

session_start();
require_once "database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get all claimed reports
$claimedItems = mysqli_query($conn, "
    SELECT r.item_name,
           r.color,
           r.location_found,
           r.created_at,
           u.username
    FROM reports r
    JOIN users u ON r.user_id = u.id
    WHERE r.status = 'claimed'
    ORDER BY r.created_at DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Claimed Items History</title>
<link rel="stylesheet" href="style.css">

<style>
body {
  display:block !important;
  background:#f1f5f9;
  margin:0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

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

.sidebar a {
  display:block;
  color:#e5e7eb;
  text-decoration:none;
  padding:10px 12px;
  border-radius:8px;
  margin-bottom:6px;
  transition:background 0.2s;
}

.sidebar a:hover,
.sidebar a.active {
  background:rgba(255,255,255,0.12);
  color:#fff;
}

.main {
  flex:1;
  padding:30px 0;
  display:flex;
  flex-direction:column;
  align-items:center;
}

.header-area {
  width:100%;
  max-width:900px;
  margin-bottom:18px;
  padding:0 16px;
}

.header-area h1 {
  font-size:28px;
  color:#0f172a;
  margin-bottom:6px;
}

.header-area p {
  color:#64748b;
  margin-bottom:0;
}

.claimed-grid {
  width:100%;
  max-width:900px;
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
  gap:18px;
  padding:0 16px;
}

.card {
  background:#fff;
  border-radius:14px;
  padding:18px 16px;
  box-shadow:0 6px 18px rgba(0,0,0,0.07);
  display:flex;
  flex-direction:column;
  min-height:180px;
  transition:box-shadow 0.2s, transform 0.2s;
  border-left:4px solid #10b981;
  position:relative;
}

.card:hover {
  box-shadow:0 12px 32px rgba(16,185,129,0.13);
  transform:translateY(-3px) scale(1.02);
}

.card h3 {
  font-size:20px;
  color:#0f172a;
  margin:8px 0 4px 0;
  font-weight:600;
}

.card p {
  margin:4px 0;
  color:#475569;
  font-size:14px;
}

.card .meta {
  font-size:12px;
  color:#64748b;
  margin-top:auto;
  border-top:1px dashed #e5e7eb;
  padding-top:8px;
}

.badge {
  display:inline-block;
  padding:4px 10px;
  border-radius:999px;
  font-size:11px;
  font-weight:bold;
  background:#dcfce7;
  color:#166534;
  position:absolute;
  top:12px;
  right:12px;
}

.empty-state {
  background:#fff;
  border-radius:14px;
  padding:50px 24px;
  text-align:center;
  color:#64748b;
  box-shadow:0 10px 25px rgba(0,0,0,0.06);
  max-width:600px;
  margin-top:40px;
}
/* Active link glow */
.sidebar a.active {
  background: linear-gradient(90deg, rgba(37,99,235,0.25), rgba(37,99,235,0.05));
  box-shadow: inset 0 0 0 1px rgba(37,99,235,.4);
}
</style>
</head>
<body>

<div class="wrapper">

  <!-- Sidebar -->
  <div class="sidebar">
    <?php $current = basename($_SERVER['PHP_SELF']); ?>
    <h2>👤 My Account</h2>
    
    <a href="user_dashboard.php">🏠 Dashboard</a>	
    <a href="profile.php">👤 My Profile</a>
    <a href="my_reports.php">📋 My Reports</a>
    <a href="public_feed.php">🌍 Public Feed</a>
    <a href="matched_items.php">🔁 Matched Items</a>
    <a href="claimed_items.php" class="active">📦 Claimed Items</a>
    <a href="login.php">🚪 Logout</a>
  </div>

  <!-- Main -->
  <div class="main">
    <div class="header-area">
      <h1>📦 Claimed Items History</h1>
      <p>These items were successfully claimed and settled.</p>
    </div>

    <?php if ($claimedItems && mysqli_num_rows($claimedItems) > 0): ?>
      <div class="claimed-grid">
      <?php while ($row = mysqli_fetch_assoc($claimedItems)): ?>
        <div class="card">
          <span class="badge">CLAIMED</span>
          <h3><?= htmlspecialchars($row['item_name']) ?></h3>
          <p><strong>Color:</strong> <?= htmlspecialchars($row['color']) ?></p>
          <p><strong>Location:</strong> <?= htmlspecialchars($row['location_found']) ?></p>
          <p><strong>Owner:</strong> <?= htmlspecialchars($row['username']) ?></p>
          <div class="meta">
            Claimed on <?= htmlspecialchars($row['created_at']) ?>
          </div>
        </div>
      <?php endwhile; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <h3>No claimed items yet.</h3>
        <p>Claimed items will appear here once available.</p>
      </div>
    <?php endif; ?>

  </div>
</div>

</body>
</html>