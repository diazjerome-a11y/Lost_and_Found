<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$claims = mysqli_query($conn, "
    SELECT claims.id,
           claims.message,
           claims.status,
           claims.admin_reply,
           claims.requested_at,
           users.username,
           reports.item_name
    FROM claims
    JOIN users ON claims.claimant_user_id = users.id
    JOIN matched_items ON claims.matched_id = matched_items.id
    JOIN reports ON matched_items.lost_report_id = reports.id
    ORDER BY claims.requested_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Claim Requests | Admin Panel</title>
<link rel="stylesheet" href="style.css">

<style>
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
  position: sticky;
  top: 0;
  height: 100vh;
  overflow-y: auto;
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

.main {
  flex:1;
  padding:30px 40px;
}

.page-header h1 {
  margin:0;
  font-size:26px;
  color:#0f172a;
}

.card {
  background:#fff;
  border-radius:14px;
  padding:18px;
  box-shadow:0 10px 25px rgba(0,0,0,0.08);
  margin-bottom:18px;
}

.badge {
  display:inline-block;
  padding:4px 10px;
  border-radius:999px;
  font-size:11px;
  font-weight:700;
  text-transform:uppercase;
}

.pending { background:#fde68a; color:#92400e; }
.approved { background:#bbf7d0; color:#065f46; }
.rejected { background:#fecaca; color:#7f1d1d; }

.action-btn {
  display:inline-block;
  margin-top:10px;
  padding:8px 14px;
  border-radius:8px;
  text-decoration:none;
  font-weight:600;
}

.approve-btn { background:#16a34a; color:#fff; }
.reject-btn { background:#dc2626; color:#fff; }

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
    <a href="admin_claims.php" class="<?= $current == 'admin_claims.php' ? 'active' : '' ?>">🔐 Claim Requests</a>
    <a href="public_feed.php">🌍 Public Feed</a>
	<a href="admin_settings.php">⚙ System Settings</a>
	<a href="monthly_report.php" class="action-btn">📄Monthly Report</a>
    <a href="login.php">🚪 Logout</a>
  </div>

  <!-- Main -->
  <div class="main">

    <div class="page-header">
      <h1>🔐 Claim Requests</h1>
      <p>Review and verify item ownership requests.</p>
    </div>

    <?php if ($claims && mysqli_num_rows($claims) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($claims)): ?>

        <div class="card">

          <h3><?= htmlspecialchars($row['item_name']) ?></h3>

          <p><strong>User:</strong> <?= htmlspecialchars($row['username']) ?></p>
          <p><strong>Message:</strong> <?= htmlspecialchars($row['message']) ?></p>

          <span class="badge <?= $row['status'] ?>">
            <?= strtoupper($row['status']) ?>
          </span>

          <?php if ($row['status'] === 'pending'): ?>
            <br>
            <a href="moderate_claim.php?action=approve&id=<?= $row['id'] ?>" class="action-btn approve-btn">
              Approve
            </a>
            <a href="moderate_claim.php?action=reject&id=<?= $row['id'] ?>" class="action-btn reject-btn">
              Reject
            </a>
          <?php else: ?>
            <p><strong>Admin Reply:</strong> <?= htmlspecialchars($row['admin_reply']) ?></p>
          <?php endif; ?>

        </div>

      <?php endwhile; ?>
    <?php else: ?>
      <div class="card">
        No claim requests yet.
      </div>
    <?php endif; ?>

  </div>
</div>

</body>
</html>