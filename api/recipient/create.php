<?php
header("Content-Type: application/json");
include "../../database/to_connect.php";

$raw = file_get_contents("php://input");

echo json_encode([
    "raw_input" => $raw,
    "decoded" => json_decode($raw, true)
]);
exit;

$data = json_decode(file_get_contents("php://input"), true);

$name     = $data['name'] ?? '';
$email    = $data['email'] ?? '';
$no_tel   = $data['no_tel'] ?? '';
$location = $data['location'] ?? '';
$lat      = $data['latitude'] ?? null;
$lng      = $data['longitude'] ?? null;

if (!$name || !$email || !$no_tel || !$location) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing required fields"
    ]);
    exit;
}

$query = "
INSERT INTO recipient (name, email, no_tel, location, latitude, longitude)
VALUES ('$name', '$email', '$no_tel', '$location', '$lat', '$lng')
";

if (mysqli_query($conn, $query)) {
    echo json_encode([
        "status" => "success",
        "message" => "Recipient registered"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => mysqli_error($conn)
    ]);
}