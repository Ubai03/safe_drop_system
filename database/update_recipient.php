<?php
include("to_connect.php");

$recipient_id = $_POST['recipient_id'];
$name = $_POST['name'];
$email = $_POST['email'];
$no_tel = $_POST['no_tel'];
$location = $_POST['location'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

$sql = "UPDATE recipient 
        SET name='$name', email='$email', no_tel='$no_tel', 
            location='$location', latitude='$latitude', longitude='$longitude' 
        WHERE recipient_id='$recipient_id'";

if (mysqli_query($conn, $sql)) {
    header("Location: ../interface/admin/recipient.php?success=trueUpdate");
    exit();
} else {
    echo "Error updating record: " . mysqli_error($conn);
}
?>
