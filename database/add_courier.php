<?php
include("to_connect.php");

$username = $_POST['username'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$vehicle_type = $_POST['vehicle_type'];
$vehicle_plate = $_POST['vehicle_plate'];

// ✅ PREPARED STATEMENT (SAFE)
$stmt = $conn->prepare("INSERT INTO user 
(username, name, password, role, phone, vehicle_type, vehicle_plate, status) 
VALUES (?, ?, ?, 'courier', ?, ?, ?, 'available')");

$stmt->bind_param("ssssss", $username, $name, $password, $phone, $vehicle_type, $vehicle_plate);

if($stmt->execute()){
    header("Location: ../interface/admin/courier.php?success=1");
    exit;
} else {
    echo "Error: " . $stmt->error;
}
?>