<?php
header('Content-Type: application/json');
include("to_connect.php");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(["status"=>"error","message"=>"Invalid request"]);
    exit;
}

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$no_tel = $_POST['no_tel'] ?? '';
$location = $_POST['location'] ?? '';
$latitude = $_POST['latitude'] ?? null;
$longitude = $_POST['longitude'] ?? null;

if (!$name || !$location) {
    echo json_encode(["status"=>"error","message"=>"Missing fields"]);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO recipient (name,email,no_tel,location,latitude,longitude)
    VALUES (?,?,?,?,?,?)
");

$stmt->bind_param("ssssdd",$name,$email,$no_tel,$location,$latitude,$longitude);

if ($stmt->execute()) {

    echo json_encode([
        "status"=>"success",
        "recipient_id"=>$stmt->insert_id
    ]);

} else {
    echo json_encode([
        "status"=>"error",
        "message"=>$conn->error
    ]);
}

$stmt->close();
$conn->close();
?>