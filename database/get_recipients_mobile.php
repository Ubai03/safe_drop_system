<?php
header('Content-Type: application/json');
include("to_connect.php");

$query = "
    SELECT recipient_id, name, email, no_tel, location, longitude, latitude
    FROM recipient
    ORDER BY recipient_id ASC
";

$result = mysqli_query($conn, $query);

$recipients = [];

while ($row = mysqli_fetch_assoc($result)) {
    $recipients[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => $recipients
]);

$conn->close();