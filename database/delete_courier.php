<?php
include("to_connect.php");

// Check if ID exists
if(isset($_GET['id'])){

    $id = $_GET['id'];

    // Safety: only delete courier, not admin
    $stmt = $conn->prepare("DELETE FROM user WHERE user_id = ? AND role = 'courier'");
    $stmt->bind_param("i", $id);

    if($stmt->execute()){
        header("Location: ../interface/admin/courier.php?delete=success");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

} else {
    echo "Invalid request";
}
?>