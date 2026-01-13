<?php
include("to_connect.php");

if (isset($_FILES['qr_file']) && isset($_POST['recipient_id'])) {
    $recipient_id = $_POST['recipient_id'];

    // Folder for uploaded QRs
    $qrDir = "../qrcodes/";
    if (!file_exists($qrDir)) {
        mkdir($qrDir, 0777, true);
    }

    $fileName = "qr_" . $recipient_id . ".png";
    $targetPath = $qrDir . $fileName;
    $relativePath = "qrcodes/" . $fileName; // Store clean path in DB

    if (move_uploaded_file($_FILES['qr_file']['tmp_name'], $targetPath)) {
        // Save or update record
        $sql = "INSERT INTO qr_code (recipient_id, qr_value, generated_at)
                VALUES ('$recipient_id', '$relativePath', NOW())
                ON DUPLICATE KEY UPDATE qr_value='$relativePath', generated_at=NOW()";
        mysqli_query($conn, $sql);

        header("Location: ../interface/admin/recipient.php?success=trueUpload");
        exit();
    } else {
        echo "Upload failed. Check file permissions.";
    }
}
?>
