<?php
include 'database/to_connect.php';

// Get POST data
$parcel_lat = $_POST['parcel_lat'];
$parcel_long = $_POST['parcel_long'];

if ($parcel_lat && $parcel_long) {

    mysqli_query($conn, "INSERT INTO tbl_controller(parcel_lat,parcel_long) VALUES ('$parcel_lat','$parcel_long')");
}