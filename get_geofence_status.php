<?php
//include file to_connect
include "database/to_connect.php";

$sql = mysqli_query($conn, "SELECT * FROM tbl_controller");
$data = mysqli_fetch_assoc($sql);
if ($data) {
    // Return the geofence status
    echo json_encode(['geofence_status' => (int)$data['geofence_status']]);
} else {
    // Default to 0 if no record found
    echo json_encode(['geofence_status' => 0]);
}
?>