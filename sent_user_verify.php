<?php
include 'database/to_connect.php';

// Get POST data
$user_verify = $_POST['user_verify'];

if ($user_verify) {

    mysqli_query($conn, "INSERT INTO tbl_controller(user_verify) VALUES ('$user_verify')");
}