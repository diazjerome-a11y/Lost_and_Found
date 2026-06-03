<?php
require_once "database.php";

$collegeId = $_GET['college_id'];

$result = mysqli_query($conn,"
SELECT id, course_name 
FROM courses 
WHERE college_id='$collegeId'
");

$courses = [];

while($row = mysqli_fetch_assoc($result)){
$courses[] = $row;
}

echo json_encode($courses);
?>