<?php


session_start();
require_once "database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$userId = $_SESSION['user_id'];

$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

$matches = mysqli_query($conn, "
    SELECT 
        m.id AS matched_id,
        m.matched_at,
        r1.item_name,
        r1.color,
        r1.location_found,
        u1.username AS lost_user,
        u2.username AS found_user,
        c.status AS claim_status,
        c.admin_reply
    FROM matched_items m
    JOIN reports r1 ON m.lost_report_id = r1.id
    JOIN reports r2 ON m.found_report_id = r2.id
    JOIN users u1 ON r1.user_id = u1.id
    JOIN users u2 ON r2.user_id = u2.id
    LEFT JOIN claims c 
        ON c.matched_id = m.id 
        AND c.claimant_user_id = '$userId'
    WHERE r1.status='approved'
      AND r2.status='approved'
    ORDER BY m.matched_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Matched Items</title>
<link rel="stylesheet" href="style.css">
<style>
body {
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
  font-size:15px;
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
  font-size:28px; /* Increased to match other pages */
  color:#0f172a;
  margin-bottom:6px;
}

.header-area p {
  color:#64748b;
  margin-bottom:0;
  font-size:14px;
}

.matches-grid {
  width:100%;
  max-width:900px;
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
  gap:18px;
  padding:0 16px;
}

.match-card {
  background:#fff;
  border-radius:14px;
  padding:18px 16px;
  box-shadow:0 6px 18px rgba(37,99,235,0.07);
  display:flex;
  flex-direction:column;
  min-height:180px;
  border-left:4px solid #2563eb;
  transition:box-shadow 0.2s, transform 0.2s;
  position:relative;
}

.match-card:hover {
  box-shadow:0 12px 32px rgba(37,99,235,0.13);
  transform:translateY(-3px) scale(1.02);
}

.match-card h3 {
  font-size:18px;
  color:#0f172a;
  margin:4px 0 2px 0;
  font-weight:600;
}

.match-card p {
  margin:2px 0;
  color:#475569;
  font-size:14px;
}

.match-card .meta {
  font-size:12px;
  color:#64748b;
  margin-top:auto;
  border-top:1px dashed #e5e7eb;
  padding-top:4px;
}

.status-badge {
  display:inline-block;
  margin-top:8px;
  padding:6px 10px;
  border-radius:6px;
  font-weight:bold;
  font-size:13px;
}

.status-badge.request {
  background:#2563eb;
  color:white;
}

.status-badge.pending {
  background:#fde68a;
  color:#92400e;
}

.status-badge.approved {
  background:#bbf7d0;
  color:#065f46;
}

.status-badge.rejected {
  background:#fecaca;
  color:#7f1d1d;
}

.empty-state {
  background:#fff;
  border-radius:14px;
  padding:40px 16px;
  text-align:center;
  color:#64748b;
  box-shadow:0 6px 18px rgba(0,0,0,0.06);
  max-width:500px;
  margin-top:20px;
  font-size:16px;
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

  <div class="sidebar">
    <h2 style="font-size:16px;margin-bottom:25px;">👤 My Account</h2>

    <?php $current = basename($_SERVER['PHP_SELF']); ?>
    <a href="user_dashboard.php">🏠 Dashboard</a>
    <a href="profile.php">👤 My Profile</a>
    <a href="my_reports.php">📋 My Reports</a>
    <a href="public_feed.php">🌍 Public Feed</a>
    <a href="matched_items.php" class="active">🔁 Matched Items</a>
    <a href="claimed_items.php">📦 Claimed Items</a>
    <a href="login.php">🚪 Logout</a>
  </div>

  <div class="main">
    <div class="header-area">
      <h1>🔁 Matched Items</h1>
      <p>These items have potential matches.</p>
    </div>

    <?php if (mysqli_num_rows($matches) > 0): ?>
      <div class="matches-grid">
      <?php while ($row = mysqli_fetch_assoc($matches)): ?>
        <div class="match-card">

          <h3><?= htmlspecialchars($row['item_name']) ?></h3>
          <p><strong>Color:</strong> <?= htmlspecialchars($row['color']) ?></p>
          <p><strong>Location:</strong> <?= htmlspecialchars($row['location_found']) ?></p>
          <p><strong>Lost By:</strong> <?= htmlspecialchars($row['lost_user']) ?></p>
          <p><strong>Found By:</strong> <?= htmlspecialchars($row['found_user']) ?></p>
          <div class="meta">
            Matched on <?= htmlspecialchars($row['matched_at']) ?>
          </div>

          <?php if (!$row['claim_status']): ?>
            <a href="request_claim.php?id=<?= $row['matched_id'] ?>"
               class="status-badge request">
               🔐 Request Claim
            </a>
          <?php elseif ($row['claim_status'] === 'pending'): ?>
            <span class="status-badge pending">
                ⏳ Claim Requested
            </span>
          <?php elseif ($row['claim_status'] === 'approved'): ?>
            <span class="status-badge approved">
                ✅ Claim Approved
            </span>
          <?php elseif ($row['claim_status'] === 'rejected'): ?>
            <span class="status-badge rejected">
                ❌ Claim Rejected
            </span>
          <?php endif; ?>

        </div>
      <?php endwhile; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <h3>No matched items yet.</h3>
        <p>Matched items will appear here once available.</p>
      </div>
    <?php endif; ?>

  </div>

</div>
</body>
</html>