<?php

session_start();
require_once "database.php";

if (!isset($_SESSION['user_id']) || !isset($_GET['user_id'])) {
    echo "Unauthorized";
    exit;
}

$userId = intval($_GET['user_id']);

// Fetch user details
$result = mysqli_query($conn, "
SELECT 
u.username,
u.email,
u.profile_picture,
u.first_name,
u.last_name,
u.contact_number,
c.college_name,
cr.course_name
FROM users u
LEFT JOIN colleges c ON u.college_id = c.id
LEFT JOIN courses cr ON u.course_id = cr.id
WHERE u.id = '$userId'
");

if ($user = mysqli_fetch_assoc($result)) {
    // Count stats
    $returnedQuery = mysqli_query($conn, "SELECT COUNT(*) AS total_returned FROM reports WHERE user_id='$userId' AND type='found' AND status='claimed'");
    $returnedCount = mysqli_fetch_assoc($returnedQuery)['total_returned'];

    $lostQuery = mysqli_query($conn, "SELECT COUNT(*) AS total_lost FROM reports WHERE user_id='$userId' AND type='lost'");
    $lostCount = mysqli_fetch_assoc($lostQuery)['total_lost'];

    $foundQuery = mysqli_query($conn, "SELECT COUNT(*) AS total_found FROM reports WHERE user_id='$userId' AND type='found'");
    $foundCount = mysqli_fetch_assoc($foundQuery)['total_found'];

    $reputation = "New Member";
    if ($returnedCount >= 10) $reputation = "Campus Hero 🏆";
    elseif ($returnedCount >= 5) $reputation = "Trusted Finder ⭐";
    elseif ($returnedCount >= 1) $reputation = "Helpful Student 👍";

    echo "
    <img class='profile-pic-modal' src='profile_pictures/" . $user['profile_picture'] . "'>
    <h2>" . htmlspecialchars($user['first_name'] . " " . $user['last_name']) . "</h2>
    <p>" . htmlspecialchars($user['email']) . "</p>
    <div class='user-details-modal'>
        <div><strong>College:</strong> " . htmlspecialchars($user['college_name']) . "</div>
        <div><strong>Course:</strong> " . htmlspecialchars($user['course_name']) . "</div>
        <div><strong>Contact:</strong> " . htmlspecialchars($user['contact_number']) . "</div>
        <div><strong>Returned Items:</strong> $returnedCount</div>
        <div><strong>Lost Reports:</strong> $lostCount</div>
        <div><strong>Found Reports:</strong> $foundCount</div>
        <div><strong>Reputation:</strong> $reputation</div>
    </div>
    ";
} else {
    echo "User not found.";
}
?>