<?php

session_start();
require_once "database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch pending reports for admin approval
$pendingReports = mysqli_query($conn, "
    SELECT reports.*, users.username 
    FROM reports 
    JOIN users ON reports.user_id = users.id
    WHERE reports.status = 'pending'
    ORDER BY reports.created_at DESC
");

// Get total users count
$userCountResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$userCountRow = mysqli_fetch_assoc($userCountResult);
$totalUsers = $userCountRow['total'];

// Get total lost and found items
$lostCountResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM reports WHERE type='lost'");
$lostCountRow = mysqli_fetch_assoc($lostCountResult);
$totalLostItems = $lostCountRow['total'];

$foundCountResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM reports WHERE type='found'");
$foundCountRow = mysqli_fetch_assoc($foundCountResult);
$totalFoundItems = $foundCountRow['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard | NISU Lost and Found Portal</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
  body {
    display: block !important;
    align-items: initial !important;
    justify-content: initial !important;
    margin: 0;
    min-height: 100vh;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .dashboard-wrapper {
    display: flex;
    min-height: 100vh;
    width: 100%;
  }

  .sidebar {
    width: 240px;
    background: linear-gradient(180deg, #0f172a 0%, #0b1220 100%);
    color: #fff;
    padding: 20px;
    box-shadow: 4px 0 20px rgba(0,0,0,0.2);
  }

  .sidebar .brand {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    margin-bottom: 24px;
  }

  .sidebar .brand img {
    width: 64px;
    height: auto;
    margin-bottom: 8px;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
  }

  .sidebar .brand-title {
    font-size: 18px;
    font-weight: 600;
    line-height: 1.2;
    color: #fff;
    max-width: 180px;
  }

  .sidebar h2 {
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    font-weight: 700;
    letter-spacing: .3px;
    margin-bottom: 28px;
  }

  .sidebar a {
    display: block;
    color: #e5e7eb;
    text-decoration: none;
    padding: 10px 12px;
    border-radius: 8px;
    margin-bottom: 6px;
    font-weight: 500;
    transition: all .2s ease;
  }

  .sidebar a:hover,
  .sidebar a.active {
    background: linear-gradient(90deg, rgba(37,99,235,0.25), rgba(37,99,235,0.05));
    box-shadow: inset 0 0 0 1px rgba(37,99,235,.4);
    transform: translateX(4px);
    color: #fff;
  }

  .main-content {
    flex: 1;
    padding: 30px 40px;
    background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
  }

  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
  }

  .header h1 {
    margin: 0;
    color: #0f172a;
    font-size: 28px;
    display: flex;
    align-items: center;
    gap: 8px;
  }


  .stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
  }

  .stat-card {
    background: #fff;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    transition: transform .2s ease, box-shadow .2s ease;
    border-left: 4px solid #2563eb;
    position: relative;
    overflow: hidden;
  }

  .stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 60px;
    height: 60px;
    background: rgba(37,99,235,0.1);
    border-radius: 50%;
    transform: translate(20px, -20px);
  }

  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 30px rgba(0,0,0,0.12);
  }

  .stat-card h3 {
    margin: 0;
    font-size: 14px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .stat-card p {
    font-size: 32px;
    margin-top: 12px;
    color: #2563eb;
    font-weight: bold;
  }

  .section-card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    transition: box-shadow .2s ease;
    margin-bottom: 20px;
  }

  .section-card:hover {
    box-shadow: 0 14px 30px rgba(0,0,0,0.12);
  }

  .section-card h3 {
    margin-top: 0;
    font-size: 20px;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 16px;
  }

  table th {
    font-size: 12px;
    color: #64748b;
    text-transform: uppercase;
    padding: 12px;
    border-bottom: 2px solid #e5e7eb;
    text-align: left;
    font-weight: 600;
    letter-spacing: .4px;
  }

  table td {
    font-size: 14px;
    color: #0f172a;
    padding: 12px;
    border-bottom: 1px solid #f1f5f9;
  }

  table tr:hover {
    background: #f8fafc;
  }

  table td a {
    text-decoration: none;
    transition: opacity .2s ease;
    font-weight: 600;
  }

  table td a:hover {
    opacity: .7;
  }

  a.approve-btn {
    color: #16a34a;
  }

  a.reject-btn {
    color: #dc2626;
  }

  .no-data {
    color: #64748b;
    font-style: italic;
  }
  </style>
</head>
<body>

<div class="dashboard-wrapper">

  <!-- Sidebar -->
  <div class="sidebar">

  <div class="brand">
  <img src="nisu_logo1.jpg" alt="NISU Logo">
  <div class="brand-title">NISU Lost &amp; Found</div>
</div>

  <?php $current = basename($_SERVER['PHP_SELF']); ?>

  <a href="admin_dashboard.php" class="<?= $current == 'admin_dashboard.php' ? 'active' : '' ?>">🏠 Dashboard</a>
  <a href="admin_review.php" class="<?= $current == 'admin_review.php' ? 'active' : '' ?>">📝 Review Pending Reports</a>
  <a href="admin_claims.php">🔐 Claim Requests</a>
  <a href="public_feed.php">🌍 Public Feed</a>
  <a href="admin_settings.php">⚙ System Settings</a>
  <a href="monthly_report.php" class="action-btn">📄Monthly Report</a>

  <a href="login.php">🚪 Logout</a>
</div>

  <!-- Main content -->
  <div class="main-content">
    <div class="header">
      <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
      
    </div>

    <p>Welcome back, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?> 👋</p>

    <!-- Stats -->
    <div class="stats">
      <div class="stat-card">
        <h3><i class="fas fa-users"></i> Total Users</h3>
        <p><?= $totalUsers ?></p>
      </div>

      <div class="stat-card">
        <h3><i class="fas fa-search"></i> Total Lost Items</h3>
        <p><?= $totalLostItems ?></p>
      </div>

      <div class="stat-card">
        <h3><i class="fas fa-map-marker-alt"></i> Total Found Items</h3>
        <p><?= $totalFoundItems ?></p>
      </div>
    </div>

    <!-- Pending Reports -->
    <div class="section-card">
      <h3><i class="fas fa-clock"></i> Pending Reports (Admin Approval)</h3>

      <?php if (mysqli_num_rows($pendingReports) > 0): ?>
        <table>
          <tr>
            <th>User</th>
            <th>Item</th>
            <th>Type</th>
            <th>Action</th>
          </tr>

          <?php while ($row = mysqli_fetch_assoc($pendingReports)): ?>
            <tr>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= htmlspecialchars($row['item_name']) ?></td>
              <td><?= ucfirst($row['type']) ?></td>
              <td>
                <a href="moderate.php?action=approve&id=<?= $row['id'] ?>" class="approve-btn">
                  <i class="fas fa-check"></i> Approve
                </a>
                |
                <a href="moderate.php?action=reject&id=<?= $row['id'] ?>" class="reject-btn">
                  <i class="fas fa-times"></i> Reject
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </table>
      <?php else: ?>
        <p class="no-data">No pending reports.</p>
      <?php endif; ?>
    </div>

  </div>
</div>

</body>
</html>