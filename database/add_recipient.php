<?php
include("to_connect.php");
require_once '../phpqrcode/qrlib.php'; // <-- make sure phpqrcode library is available

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $no_tel = $_POST['no_tel'];
    $sender_address = $_POST['sender_address'];
    $location = $_POST['location'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];

    // Step 1: Insert new recipient
    $insert = "INSERT INTO recipient (name, email, no_tel, sender_address, location, longitude, latitude)
               VALUES ('$name', '$email', '$no_tel', '$sender_address', '$location', '$longitude', '$latitude')";
    $result = mysqli_query($conn, $insert);

    if ($result) {
        $recipient_id = mysqli_insert_id($conn);

        // Step 2: Generate QR code automatically
        $qrData = "Recipient ID: $recipient_id\nName: $name\nLocation: $location";
        $folderPath = "../uploads/qr_codes/";
        if (!is_dir($folderPath)) mkdir($folderPath, 0777, true);

        $fileName = "qr_" . $recipient_id . ".png";
        $filePath = $folderPath . $fileName;

        QRcode::png($qrData, $filePath, QR_ECLEVEL_L, 10);

        // Step 3: Save to qr_code table
        $qrValue = "uploads/qr_codes/" . $fileName;
        $insertQR = "INSERT INTO qr_code (recipient_id, qr_value, generated_at)
                     VALUES ('$recipient_id', '$qrValue', NOW())";
        mysqli_query($conn, $insertQR);

        // Step 4: Create first timeline event
        mysqli_query($conn,"
        INSERT INTO parcel_log (recipient_id, status, updated_at)
        VALUES ('$recipient_id', 'Parcel registered in system', NOW())
        ");

        // Step 5: Parcel is now in delivery
        mysqli_query($conn,"
        INSERT INTO parcel_log (recipient_id, status, updated_at)
        VALUES ('$recipient_id', 'Parcel in delivery', NOW())
        ");

        // Step 6: Update controller to reflect delivery started
        mysqli_query($conn, "
                        UPDATE tbl_controller 
                        SET status = 'Delivery',
                            user_verify = 0
        ");
    }

    header("Location: ../interface/admin/recipient.php?success=true");
    exit();
}
?>
