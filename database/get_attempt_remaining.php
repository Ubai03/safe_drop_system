<?php
include("to_connect.php");

$recipient_id = intval($_GET['recipient_id'] ?? 0);
if (!$recipient_id) {
    echo json_encode(["status" => "error", "message" => "Recipient ID missing."]);
    exit;
}

// Get the latest attempt count
$query = "
    SELECT attempt_remaining 
    FROM access_log 
    WHERE recipient_id = '$recipient_id'
    ORDER BY attempted_at DESC 
    LIMIT 1
";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row) {
    echo json_encode([
        "status" => "success",
        "attempt_remaining" => intval($row['attempt_remaining'])
    ]);
} else {
    echo json_encode([
        "status" => "none",
        "attempt_remaining" => -1
    ]);
}
?>
