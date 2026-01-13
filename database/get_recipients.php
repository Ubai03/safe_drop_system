<?php
include("to_connect.php");

$query = "SELECT name, email, no_tel, location, latitude, longitude FROM recipient WHERE latitude != 0 AND longitude != 0";
$result = mysqli_query($conn, $query);

$recipients = [];
while ($row = mysqli_fetch_assoc($result)) {
    $recipients[] = $row;
}

header('Content-Type: application/json');
echo json_encode($recipients);
?>
