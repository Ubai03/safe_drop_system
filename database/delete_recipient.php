<?php
include("to_connect.php");

if (isset($_GET['id'])) {
    $recipient_id = mysqli_real_escape_string($conn, $_GET['id']); // 

    $sql = "DELETE FROM recipient WHERE recipient_id = '$recipient_id'"; // 

    if (mysqli_query($conn, $sql)) {
        header("Location: ../interface/admin/recipient.php?success=trueDelete");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "No recipient ID provided.";
}
?>
