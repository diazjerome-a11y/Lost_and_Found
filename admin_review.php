<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch pending reports
$pendingReports = mysqli_query($conn, "
    SELECT reports.id, reports.item_name, reports.type, users.username 
    FROM reports 
    JOIN users ON reports.user_id = users.id
    WHERE reports.status = 'pending'
    ORDER BY reports.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Review Pending Reports | NISU Lost and Found Portal</title>
<link rel="stylesheet" href="style.css">

<style>
html, body { height:100%; }

body {
  display:block !important;
  background:#f1f5f9;
  margin:0;
}

.wrapper { display:flex; min-height:100vh; }

.sidebar {
  width:220px;
  background:linear-gradient(180deg,#0f172a 0%,#0b1220 100%);
  color:#fff;
  padding:20px;
  box-shadow:4px 0 20px rgba(0,0,0,0.2);
}

.sidebar h2 {
  font-size:18px;
  font-weight:700;
  margin-bottom:28px;
}

.sidebar a {
  display:block;
  color:#e5e7eb;
  text-decoration:none;
  padding:10px 12px;
  border-radius:8px;
  margin-bottom:6px;
  transition:.2s ease;
}

.sidebar a:hover,
.sidebar a.active {
  background:rgba(255,255,255,0.12);
}

.main { flex:1; padding:30px 40px; }

.page-header h1 {
  margin:0;
  font-size:26px;
  color:#0f172a;
}

.page-header p {
  margin:6px 0 20px;
  color:#64748b;
}

.table-card {
  background:#fff;
  border-radius:14px;
  padding:20px;
  box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

table {
  width:100%;
  border-collapse:collapse;
}

th, td {
  padding:12px;
  text-align:left;
}

th {
  border-bottom:2px solid #e5e7eb;
  color:#334155;
}

tr:not(:last-child) td {
  border-bottom:1px solid #f1f5f9;
}

.action a {
  margin-right:10px;
  font-weight:600;
  text-decoration:none;
}

.approve-btn { color:#16a34a; }
.reject-btn { color:#dc2626; }

.empty {
  padding:30px;
  text-align:center;
  color:#64748b;
}

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
    <h2>🛠️ Admin Panel</h2>

    <a href="admin_dashboard.php" class="<?= $current == 'admin_dashboard.php' ? 'active' : '' ?>">🏠 Dashboard</a>
    <a href="admin_review.php" class="<?= $current == 'admin_review.php' ? 'active' : '' ?>">📝 Review Pending Reports</a>
	<a href="admin_claims.php">🔐 Claim Requests</a>
    <a href="public_feed.php">🌍 Public Feed</a>
	<a href="admin_settings.php">⚙ System Settings</a>
	<a href="monthly_report.php" class="action-btn">📄Monthly Report</a>
    <a href="login.php">🚪 Logout</a>
  </div>

  <!-- Main -->
  <div class="main">

    <div class="page-header">
      <h1>NISU Lost and Found Portal</h1>
      <p>Review Pending Reports (Admin)</p>
    </div>

    <div class="table-card">
      <h3>📝 Pending Reports</h3>

      <?php if ($pendingReports && mysqli_num_rows($pendingReports) > 0): ?>

        <table>
          <thead>
            <tr>
              <th>User</th>
              <th>Item</th>
              <th>Type</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>

            <?php while ($row = mysqli_fetch_assoc($pendingReports)): ?>
              <tr>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['item_name']) ?></td>
                <td><?= strtoupper(htmlspecialchars($row['type'])) ?></td>
                <td class="action">
                  <a href="moderate.php?action=approve&id=<?= $row['id'] ?>" class="approve-btn">
                    Approve
                  </a>
                  <a href="moderate.php?action=reject&id=<?= $row['id'] ?>" class="reject-btn">
                    Reject
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>

          </tbody>
        </table>

      <?php else: ?>
        <div class="empty">
          No pending reports.
        </div>
      <?php endif; ?>

    </div>

  </div>
</div>

</body>
</html> 