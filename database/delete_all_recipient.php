<?php
include("to_connect.php");

// First, delete all related rows in child tables (respect FK order)
mysqli_query($conn, "DELETE FROM access_log"); 
mysqli_query($conn, "DELETE FROM parcel_log");
mysqli_query($conn, "DELETE FROM qr_code");
mysqli_query($conn, "DELETE FROM tracking_links");

// Then delete all recipients
$delete_query = "DELETE FROM recipient";

if (mysqli_query($conn, $delete_query)) {
    header("Location: ../interface/admin/recipient.php?success=trueDeleteAll");
    exit();
} else {
    header("Location: ../interface/admin/recipient.php?error=deleteAllFailed");
    exit();
}
?>
