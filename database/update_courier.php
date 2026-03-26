<?php
include("to_connect.php");

if(isset($_POST['user_id'])){

    $id = $_POST['user_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $vehicle = $_POST['vehicle_type'];
    $plate = $_POST['vehicle_plate'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("
    UPDATE user 
    SET name=?, phone=?, vehicle_type=?, vehicle_plate=?, status=? 
    WHERE user_id=? AND role='courier'
    ");

    $stmt->bind_param("sssssi", $name, $phone, $vehicle, $plate, $status, $id);

    if($stmt->execute()){
    header("Location: ../interface/admin/courier.php?update=success");
    exit;
    }
    else{
    echo "Error: " . $stmt->error;
    }
}
?>