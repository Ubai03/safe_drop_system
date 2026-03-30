<?php
include("to_connect.php");

if (isset($_GET['id'])) {

    // Ensure ID is treated as integer
    $recipient_id = intval($_GET['id']);

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM recipient WHERE recipient_id = ?");
    $stmt->bind_param("i", $recipient_id);

    if ($stmt->execute()) {
        header("Location: ../interface/admin/recipient.php?success=trueDelete");
        exit();
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();

} else {
    echo "No recipient ID provided.";
}
?>