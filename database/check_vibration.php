<?php
include("to_connect.php");

// Get current vibration status
$query = "SELECT status_vibrate FROM tbl_controller LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    if ($row['status_vibrate'] == 1) {
        // Reset it back to 0 after detecting
        mysqli_query($conn, "UPDATE tbl_controller SET status_vibrate = 0");

        echo json_encode([
            'status' => 'success',
            'vibrate' => 1
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'vibrate' => 0
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'No data found'
    ]);
}
?>
