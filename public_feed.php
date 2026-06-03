<?php

session_start();
require_once "database.php";

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Role flag
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

/* ==============================
   1️⃣ TOP FINDER QUERY - Monthly Top 3
============================== */
$topFinder = mysqli_query($conn, "
    SELECT u.username, COUNT(*) as total_returned
    FROM reports r
    JOIN users u ON r.user_id = u.id
    WHERE r.type = 'found'
    AND r.status = 'claimed'
    AND MONTH(r.created_at) = MONTH(CURDATE())
    AND YEAR(r.created_at) = YEAR(CURDATE())
    GROUP BY r.user_id
    ORDER BY total_returned DESC
    LIMIT 3
");

$topUsers = [];
while ($row = mysqli_fetch_assoc($topFinder)) {
    $topUsers[] = $row;
}

/* ==============================
   2️⃣ PUBLIC FEED QUERY
============================== */
$approvedReports = mysqli_query($conn, "
SELECT 
reports.id,
reports.item_name,
reports.description,
reports.color,
reports.location_found,
reports.type,
reports.status,
reports.created_at,
users.id AS user_id,
users.username,
users.contact_number,
users.profile_picture,
courses.course_name AS course,
u2.username AS returned_by
FROM reports
JOIN users ON reports.user_id = users.id
LEFT JOIN courses ON users.course_id = courses.id
LEFT JOIN matched_items m 
    ON reports.id = m.lost_report_id 
    OR reports.id = m.found_report_id
LEFT JOIN claims c 
    ON m.id = c.matched_id 
    AND c.status = 'approved'
LEFT JOIN users u2 
    ON c.claimant_user_id = u2.id
WHERE reports.status IN ('approved','claimed')
ORDER BY reports.created_at DESC
");

// Fetch user details for modal (if needed, but we'll use AJAX)
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Public Feed | NISU Lost and Found Portal</title>
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

.sidebar h2 {
  margin-bottom:25px;
  font-size:18px;
  font-weight:600;
}

.sidebar a {
  display:block;
  color:#e5e7eb;
  text-decoration:none;
  padding:10px 12px;
  border-radius:8px;
  margin-bottom:6px;
  transition: background 0.3s;
}

.sidebar a:hover,
.sidebar a.active {
  background:rgba(255,255,255,0.12);
}

.main {
  flex:1;
  padding:30px 40px;
}

.page-header h1 {
  margin:0;
  font-size:28px;
  color:#0f172a;
}

/* Campus Hero Card */
.hero-card {
  background:#ffffff;
  padding:20px;
  border-radius:14px;
  margin-bottom:25px;
  box-shadow:0 10px 25px rgba(0,0,0,0.08);
  border-left:5px solid #f59e0b;
  animation: fadeIn 0.5s ease-in;
}

/* Feed grid */
.feed-grid {
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
  gap:20px;
}

.feed-card {
  background:#ffffff;
  border-radius:14px;
  padding:20px;
  box-shadow:0 10px 25px rgba(0,0,0,0.08);
  transition: transform 0.3s, box-shadow 0.3s;
  cursor: pointer;
}

.feed-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(0,0,0,0.12);
}

.badge {
  display:inline-block;
  padding:4px 10px;
  border-radius:999px;
  font-size:11px;
  font-weight:bold;
  color:#fff;
  margin-bottom:6px;
}

.badge.lost { background:#ef4444; }
.badge.found { background:#10b981; }
.badge.settled { background:#6366f1; }

.feed-meta {
  margin-top:10px;
  font-size:12px;
  color:#64748b;
  display:flex;
  justify-content:space-between;
  border-top:1px dashed #e5e7eb;
  padding-top:8px;
}

.sidebar a.active {
  background: linear-gradient(90deg, rgba(37,99,235,0.25), rgba(37,99,235,0.05));
  box-shadow: inset 0 0 0 1px rgba(37,99,235,.4);
}

/* Modal Styles */
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.5);
  animation: fadeIn 0.3s ease-in;
}

.modal-content {
  background-color: #fefefe;
  margin: 10% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
  max-width: 500px;
  border-radius: 16px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.2);
  text-align: center;
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.close:hover {
  color: black;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.profile-pic-modal {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid #667eea;
  margin-bottom: 15px;
}

.user-details-modal {
  text-align: left;
  margin-top: 20px;
}

.user-details-modal div {
  margin-bottom: 10px;
  font-size: 14px;
  color: #475569;
}

.user-details-modal strong {
  color: #0f172a;
}
</style>
</head>
<body>

<div class="wrapper">

<div class="sidebar">
<h2><?= $isAdmin ? '🛠️ Admin Panel' : '🌍 Browse' ?></h2>

<?php if ($isAdmin): ?>
<a href="admin_dashboard.php">🏠 Dashboard</a>
<a href="admin_review.php">📝 Review Pending Reports</a>
<a href="admin_claims.php">🔐 Claim Requests</a>
<a href="public_feed.php" class="active">🌍 Public Feed</a>
<a href="admin_settings.php">⚙ System Settings</a>
<a href="monthly_report.php" class="action-btn">📄Monthly Report</a>
<?php else: ?>
<a href="user_dashboard.php">🏠 Dashboard</a>
<a href="profile.php">👤 My Profile</a>
<a href="my_reports.php">📋 My Reports</a>
<a href="public_feed.php" class="active">🌍 Public Feed</a>
<a href="matched_items.php">🔁 Matched Items</a>
<a href="claimed_items.php">📦 Claimed Items</a>
<?php endif; ?>

<a href="login.php">🚪 Logout</a>
</div>

<div class="main">

<div class="page-header">
<h1>NISU Lost and Found Portal</h1>
</div>

<?php if (!empty($topUsers)): ?>
<div class="hero-card">
<h2 style="margin:0 0 10px 0;color:#b45309;">🏆 Monthly Campus Heroes</h2>
<?php foreach ($topUsers as $index => $user): ?>
<p style="margin:5px 0;">
<strong>#<?= $index + 1 ?> - <?= htmlspecialchars($user['username']) ?></strong>
returned <strong><?= $user['total_returned'] ?></strong> item(s) this month.
</p>
<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if ($approvedReports && mysqli_num_rows($approvedReports) > 0): ?>
<div class="feed-grid">

<?php while ($row = mysqli_fetch_assoc($approvedReports)): ?>
<div class="feed-card">

<!-- PROFILE HEADER -->
<div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">

<?php
$profileImage = !empty($row['profile_picture'])
    ? "profile_pictures/" . $row['profile_picture']
    : "profile_pictures/default.png";
?>

<img src="<?= $profileImage ?>"
style="width:40px;height:40px;border-radius:50%;object-fit:cover;">

<div>
<strong class="username-link" data-user-id="<?= $row['user_id'] ?>" style="cursor:pointer;color:#2563eb;text-decoration:underline;"><?= htmlspecialchars($row['username']) ?></strong><br>
<span style="font-size:12px;color:#64748b;">
<?= date("M d, Y • h:i A", strtotime($row['created_at'])) ?>
</span>
</div>

</div>

<?php if (!empty($row['image'])): ?>
<img src="uploads/<?= $row['image'] ?>" style="width:100%;height:160px;object-fit:cover;border-radius:10px;margin-bottom:10px;">
<?php endif; ?>

<?php if ($row['status'] === 'claimed'): ?>
<span class="badge settled">SETTLED</span>
<?php else: ?>
<span class="badge <?= $row['type'] ?>">
<?= strtoupper($row['type']) ?>
</span>
<?php endif; ?>

<h3><?= htmlspecialchars($row['item_name']) ?></h3>

<p><?= htmlspecialchars($row['description']) ?></p>

<p><strong>Color:</strong> <?= htmlspecialchars($row['color']) ?></p>

<p><strong>Location:</strong> <?= htmlspecialchars($row['location_found']) ?></p>

<p><strong>Course:</strong> <?= htmlspecialchars($row['course']) ?></p>

<p><strong>Contact:</strong> <?= htmlspecialchars($row['contact_number']) ?></p>

<?php if ($row['status'] === 'claimed' && $row['returned_by']): ?>
<p style="font-size:13px;color:#16a34a;font-weight:600;">
Returned by <?= htmlspecialchars($row['returned_by']) ?>

</p>
<?php endif; ?>

</div>
<?php endwhile; ?>

</div>
<?php else: ?>

<p>No posts yet.</p>

<?php endif; ?>

</div>
</div>

<!-- Modal for User Profile -->
<div id="profileModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <div id="modal-body">
      <!-- Profile content will be loaded here -->
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('profileModal');
  const modalBody = document.getElementById('modal-body');
  const closeBtn = document.querySelector('.close');

  // Open modal on username click
  document.querySelectorAll('.username-link').forEach(link => {
    link.addEventListener('click', function() {
      const userId = this.getAttribute('data-user-id');
      // Fetch user profile data via AJAX
      fetch('get_user_profile.php?user_id=' + userId)
        .then(response => response.text())
        .then(data => {
          modalBody.innerHTML = data;
          modal.style.display = 'block';
        })
        .catch(error => console.error('Error:', error));
    });
  });

  // Close modal
  closeBtn.onclick = function() {
    modal.style.display = 'none';
  };

  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = 'none';
    }
  };
});
</script>

</body>
</html>