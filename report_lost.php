<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
	$imageName = "";

if(isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0){

    $imageName = time() . "_" . $_FILES['item_image']['name'];
    $target = "uploads/" . $imageName;

    move_uploaded_file($_FILES['item_image']['tmp_name'], $target);
}

    // Handle Item Name (Other support)
    $item_name = $_POST['item_name'] === "Other"
        ? $_POST['custom_item']
        : $_POST['item_name'];

    // Handle Color (Other support)
    $color = $_POST['color'] === "Other"
        ? $_POST['custom_color']
        : $_POST['color'];

    // Handle Location (Other support)
    $location_found = $_POST['location_found'] === "Other"
        ? $_POST['custom_location']
        : $_POST['location_found'];

    // Escape inputs
    $item_name = mysqli_real_escape_string($conn, $item_name);
    $color = mysqli_real_escape_string($conn, $color);
    $location_found = mysqli_real_escape_string($conn, $location_found);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $userId = $_SESSION['user_id'];

    $sql = "INSERT INTO reports (user_id, item_name, description, color, image, location_found, type, status, created_at)

	VALUES ('$userId','$item_name','$description','$color','$imageName','$location_found','lost','pending',NOW())";		

    if (mysqli_query($conn, $sql)) {
        header("Location: my_reports.php");
        exit;
    } else {
        $message = "Failed to submit report. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Report Lost Item | NISU Lost and Found Portal</title>
<link rel="stylesheet" href="style.css">

<style>
body {
  display: block !important;
  background: #f1f5f9;
  margin: 0;
}

.wrapper { display:flex; min-height:100vh; }

.sidebar {
  width:220px;
  background:#0f172a;
  color:#fff;
  padding:20px;
}

.sidebar a {
  display:block;
  color:#e5e7eb;
  text-decoration:none;
  padding:10px 12px;
  border-radius:8px;
  margin-bottom:6px;
}

.sidebar a:hover,
.sidebar a.active {
  background: rgba(255,255,255,0.12);
  color:#fff;
}

.main { flex:1; padding:30px 40px; }

.form-card {
  background:#fff;
  border-radius:14px;
  padding:24px 26px;
  box-shadow:0 10px 25px rgba(0,0,0,0.08);
  max-width:700px;
}

.form-group { margin-bottom:14px; }

.form-group label {
  display:block;
  font-weight:600;
  margin-bottom:6px;
}

.form-group input,
.form-group textarea,
.form-group select {
  width:100%;
  padding:10px 12px;
  border-radius:8px;
  border:1px solid #cbd5e1;
  font-size:14px;
}

.form-actions { margin-top:16px; }

.submit-btn {
  background:#2563eb;
  color:#fff;
  border:none;
  padding:10px 16px;
  border-radius:8px;
  font-weight:bold;
  cursor:pointer;
}

.cancel-btn {
  margin-left:10px;
  background:#e5e7eb;
  color:#111827;
  padding:10px 16px;
  border-radius:8px;
  text-decoration:none;
  font-weight:600;
}
</style>
</head>
<body>

<div class="wrapper">
  <div class="sidebar">
    <a href="user_dashboard.php">🏠 Home</a>
    <a href="my_reports.php">📋 My Reports</a>
    <a href="public_feed.php">🌍 Public Feed</a>
    <a href="matched_items.php">🔁 Matched Items</a>
    <a href="login.php">🚪 Logout</a>
  </div>

  <div class="main">
    <div style="margin-bottom:20px;">
      <h1 style="font-size:26px; color:#0f172a; font-weight:bold;">
        NISU Lost and Found Portal
      </h1>
      <p style="color:#64748b; font-size:14px;">
        Report Lost Item
      </p>
    </div>

    <div class="form-card">
      <h2>Report Lost Item</h2>
      <p>Fill in the details of the item you lost.</p>

      <?php if ($message): ?>
        <p style="color:#ef4444; font-weight:bold;">
          <?= htmlspecialchars($message) ?>
        </p>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data">
	  
        <div class="form-group">
          <label>Item Name</label>
          <select name="item_name" id="itemSelect" required>

			<option value="">Select Item</option>

			<?php
			$items = mysqli_query($conn,"SELECT * FROM item_categories");

			while($row = mysqli_fetch_assoc($items)){
			?>

			<option value="<?= $row['item_name'] ?>">
			<?= htmlspecialchars($row['item_name']) ?>
			</option>

			<?php } ?>

			<option value="Other">Other</option>

			</select>	

          <input type="text" name="custom_item" id="customItem"
          placeholder="Enter item name"
          style="display:none; margin-top:8px;">
        </div>

        <div class="form-group">
          <label>Color</label>
          <select name="color" id="colorSelect" required>

			<option value="">Select Color</option>

			<?php
			$colors = mysqli_query($conn,"SELECT * FROM item_colors");

			while($row = mysqli_fetch_assoc($colors)){
			?>

			<option value="<?= $row['color_name'] ?>">
			<?= htmlspecialchars($row['color_name']) ?>
			</option>

			<?php } ?>

			<option value="Other">Other</option>

			</select>

          <input type="text" name="custom_color" id="customColor"
          placeholder="Enter color"
          style="display:none; margin-top:8px;">
        </div>

        <div class="form-group">
          <label>Description</label>
          <textarea name="description" rows="4" required></textarea>
        </div>

        <div class="form-group">
          <label>Location (Last Seen)</label>
          <select name="location_found" id="locationSelect" required>

			<option value="">Select Location</option>

			<?php
			$locations = mysqli_query($conn,"SELECT * FROM item_locations");

			while($row = mysqli_fetch_assoc($locations)){
			?>

			<option value="<?= $row['location_name'] ?>">
			<?= htmlspecialchars($row['location_name']) ?>
			</option>

			<?php } ?>

			<option value="Other">Other</option>

			</select>

          <input type="text" name="custom_location" id="customLocation"
          placeholder="Enter location"
          style="display:none; margin-top:8px;">
        </div>
		
		<div class="form-group">
			<label>Item Photo (optional)</label>
			<input type="file" name="item_image" accept="image/*">
		</div>

        <div class="form-actions">
          <button type="submit" class="submit-btn">Submit Report</button>
          <a href="user_dashboard.php" class="cancel-btn">Cancel</a>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
function handleOther(selectId, inputId) {
  const select = document.getElementById(selectId);
  const input = document.getElementById(inputId);

  select.addEventListener("change", function() {
    if (this.value === "Other") {
      input.style.display = "block";
      input.required = true;
    } else {
      input.style.display = "none";
      input.required = false;
    }
  });
}

handleOther("itemSelect", "customItem");
handleOther("colorSelect", "customColor");
handleOther("locationSelect", "customLocation");
</script>

</body>
</html>