<?php
session_start();
require_once "database.php";

$userId = $_SESSION['user_id'];

if(isset($_FILES['profile_picture'])){

$file = $_FILES['profile_picture']['name'];
$tmp = $_FILES['profile_picture']['tmp_name'];

$filename = time().'_'.$file;

/* SAVE BACK TO YOUR ORIGINAL FOLDER */
move_uploaded_file($tmp,"profile_pictures/".$filename);

mysqli_query($conn,"
UPDATE users
SET profile_picture='$filename'
WHERE id='$userId'
");

}

header("Location: profile.php");
exit;
?>