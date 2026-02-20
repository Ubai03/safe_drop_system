<?php
include("to_connect.php");

$query = "SELECT parcel_lat, parcel_long FROM tbl_controller LIMIT 1";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode([
        "status" => "success",
        "lat" => $row["parcel_lat"],
        "lng" => $row["parcel_long"]
    ]);
} else {
    echo json_encode(["status" => "error"]);
}
?>
