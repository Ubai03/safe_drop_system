<?php
session_start();
include("to_connect.php");
extract($_POST);

$query = "SELECT * FROM user WHERE username = '$username'";
$result = mysqli_query($conn, $query) or trigger_error(mysqli_error($conn));
$rows = mysqli_fetch_array($result);

if($rows) {
    if (password_verify($password, $rows['password'])){
        // no user_type check, directly login
        $_SESSION['username'] = $username;
        $_SESSION['adminID'] = $rows['user_id'];
        header("location: ../interface/admin/index.php");
        exit();
    } else {
        header("Location: ../interface/login.php?error=wrongPsw");
    }
} else {
    header("Location: ../interface/login.php?error=wrongUsername");
}
?>
