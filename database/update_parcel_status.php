<?php
include("to_connect.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// 1. Get current controller state
$result = mysqli_query($conn, "SELECT * FROM tbl_controller LIMIT 1");
$controller = mysqli_fetch_assoc($result);

if (!$controller) {
    echo json_encode(["status" => "error", "message" => "No row found in tbl_controller"]);
    exit;
}

$lat  = $controller['parcel_lat'];
$long = $controller['parcel_long'];
$status = $controller['status'];
$user_verify = $controller['user_verify'];

// 2. Verify presence
if ($lat === NULL || $long === NULL) {
    echo json_encode(["status" => "error", "message" => "Missing data", "debug" => $controller]);
    exit;
}

// 3. Try to find matching recipient by GPS
$query_rec = "
    SELECT recipient_id 
    FROM recipient
    WHERE ABS(latitude - $lat) < 0.001
      AND ABS(longitude - $long) < 0.001
    LIMIT 1";
$res_rec = mysqli_query($conn, $query_rec);
$rec = mysqli_fetch_assoc($res_rec);

// If no recipient found nearby, use last active delivery
if (!$rec) {
    $query_last = "
        SELECT recipient_id 
        FROM parcel_log 
        WHERE status = 'Delivery'
        ORDER BY updated_at DESC
        LIMIT 1";
    $res_last = mysqli_query($conn, $query_last);
    $rec = mysqli_fetch_assoc($res_last);
    if (!$rec) {
        echo json_encode(["status" => "error", "message" => "No active delivery found"]);
        exit;
    }
}

$recipient_id = $rec['recipient_id'];

// Calculate distance in meters between controller and recipient
function haversine($lat1, $lon1, $lat2, $lon2) {
    $R = 6371000; // Earth radius in meters
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $R * $c;
}

// Get recipient coordinates
$rec_coords = mysqli_query($conn, "SELECT latitude, longitude FROM recipient WHERE recipient_id = '$recipient_id'");
$rec_row = mysqli_fetch_assoc($rec_coords);

$distance = haversine($lat, $long, $rec_row['latitude'], $rec_row['longitude']);

// Update geofence status automatically
if ($distance <= 15000) { // within 10 meters
    mysqli_query($conn, "UPDATE tbl_controller SET geofence_status = 1");
} else {
    mysqli_query($conn, "UPDATE tbl_controller SET geofence_status = 0");
}


$parcel_status = ($user_verify == 1) ? 'Delivered' : 'Delivery';

// 4. Update or insert parcel_log
$check = mysqli_query($conn, "
    SELECT log_id, status 
    FROM parcel_log
    WHERE recipient_id = '$recipient_id'
    ORDER BY updated_at DESC
    LIMIT 1
");
$existing = mysqli_fetch_assoc($check);

if ($existing) {
    if ($existing['status'] != $parcel_status) {
        $log_id = $existing['log_id'];
        mysqli_query($conn, "
            UPDATE parcel_log
            SET status = '$parcel_status', updated_at = NOW()
            WHERE log_id = '$log_id'
        ");
    }
} else {
    mysqli_query($conn, "
        INSERT INTO parcel_log (recipient_id, status, updated_at)
        VALUES ('$recipient_id', '$parcel_status', NOW())
    ");
}

// 5. If delivered, reset controller
if ($user_verify == 1) {
    mysqli_query($conn, "
        UPDATE tbl_controller 
        SET parcel_lat=NULL,
            parcel_long=NULL,
            geofence_status=NULL,
            user_verify=NULL,
            status=NULL
    ");
}

echo json_encode(["status" => "success", "message" => "Status updated to $parcel_status for recipient $recipient_id"]);
?>
