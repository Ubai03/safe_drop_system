<?php
//include file to_connect
include "database/to_connect.php";

$sql = mysqli_query($conn, "SELECT * FROM tbl_controller");
$data = mysqli_fetch_assoc($sql);

if ($data) {
    // Return the user verify
    echo json_encode(['user_verify' => (int)$data['user_verify']]);
} else {
    // Default to 0 if no record found
    echo json_encode(['user_verify' => 0]);
}
?>