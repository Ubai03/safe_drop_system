<?php
include 'database/to_connect.php';

// Get POST data
$status_vibrate = $_POST['status_vibrate'];

if ($status_vibrate) {

    mysqli_query($conn, "INSERT INTO tbl_controller(status_vibrate) VALUES ('$status_vibrate')");
}