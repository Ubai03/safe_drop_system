<?php
session_start();
include("to_connect.php");

$count_query = "SELECT COUNT(*) AS notification_count FROM notification WHERE status = 'pending'";
$count_result = mysqli_query($conn, $count_query);
$notification_count = mysqli_fetch_assoc($count_result)['notification_count'];

echo ($notification_count > 0) ? $notification_count : '';
?>
