<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['action']) && isset($_GET['id'])) {

    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'approve') {

        // Approve report
        mysqli_query($conn, "UPDATE reports SET status='approved' WHERE id=$id");

        // Get approved report
        $result = mysqli_query($conn, "SELECT * FROM reports WHERE id=$id");
        $report = mysqli_fetch_assoc($result);

        if ($report) {

            // Normalize values (trim + lowercase)
            $item = strtolower(trim($report['item_name']));
            $color = strtolower(trim($report['color']));
            $location = strtolower(trim($report['location_found']));
            $type = $report['type'];

            $oppositeType = ($type === 'lost') ? 'found' : 'lost';

            // Case-insensitive match
            $matchQuery = mysqli_query($conn, "
                SELECT * FROM reports
                WHERE status='approved'
                AND type='$oppositeType'
                AND LOWER(TRIM(item_name)) = '$item'
                AND LOWER(TRIM(color)) = '$color'
                AND LOWER(TRIM(location_found)) = '$location'
            ");

            while ($match = mysqli_fetch_assoc($matchQuery)) {

                $lostId  = ($type === 'lost') ? $id : $match['id'];
                $foundId = ($type === 'found') ? $id : $match['id'];

                // Prevent duplicate match
                $check = mysqli_query($conn, "
                    SELECT id FROM matched_items
                    WHERE lost_report_id='$lostId'
                    AND found_report_id='$foundId'
                ");

                if (mysqli_num_rows($check) == 0) {

                    mysqli_query($conn, "
                        INSERT INTO matched_items (lost_report_id, found_report_id)
                        VALUES ('$lostId', '$foundId')
                    ");
                }
            }
        }

    } elseif ($action === 'reject') {

        mysqli_query($conn, "UPDATE reports SET status='rejected' WHERE id=$id");

    }

    header("Location: admin_review.php");
    exit;
}
?>