<?php
session_start();
require_once "database.php";

// Secure admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Validate GET parameters
if (!isset($_GET['id'], $_GET['action'])) {
    header("Location: admin_claims.php");
    exit;
}

$id = (int) $_GET['id'];
$action = $_GET['action'];

if ($action === 'approve') {

    // 1️⃣ Approve claim
    mysqli_query($conn, "
        UPDATE claims 
        SET status='approved',
            admin_reply='Claim approved. Please claim your item at:

			 NISU OSA Office – Room 102
			 8:00 AM – 5:00 PM
			 admin@nisu.edu.ph'
					WHERE id=$id
			");

    // 2️⃣ Get matched_id
    $claimResult = mysqli_query($conn, "
        SELECT matched_id FROM claims WHERE id=$id
    ");

    if ($claimRow = mysqli_fetch_assoc($claimResult)) {

        $matchedId = (int)$claimRow['matched_id'];

        // 3️⃣ Get lost & found report IDs
        $matchResult = mysqli_query($conn, "
            SELECT lost_report_id, found_report_id
            FROM matched_items
            WHERE id = $matchedId
        ");

        if ($matchRow = mysqli_fetch_assoc($matchResult)) {

            $lostId = (int)$matchRow['lost_report_id'];
            $foundId = (int)$matchRow['found_report_id'];

            // 4️⃣ Mark both reports as claimed
            mysqli_query($conn, "
                UPDATE reports SET status='claimed' WHERE id=$lostId
            ");

            mysqli_query($conn, "
                UPDATE reports SET status='claimed' WHERE id=$foundId
            ");
        }
    }

} elseif ($action === 'reject') {

    mysqli_query($conn, "
        UPDATE claims 
        SET status='rejected',
            admin_reply='Claim rejected. Insufficient proof.'
        WHERE id=$id
    ");
}

header("Location: admin_claims.php");
exit;
?>
