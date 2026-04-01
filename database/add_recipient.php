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
    $courier_id = $_POST['courier_id'] ?? NULL;

    $tracking_number = "SD-" . date("Ymd") . "-" . rand(100,999);
    // Step 1: Insert new recipient (PREPARED STATEMENT)
    $stmt = $conn->prepare("
        INSERT INTO recipient
        (tracking_number,name,email,no_tel,sender_address,location,longitude,latitude,courier_id)
        VALUES (?,?,?,?,?,?,?,?,?)
    ");
    $stmt->bind_param(
        "sssssssss",
        $tracking_number,
        $name,
        $email,
        $no_tel,
        $sender_address,
        $location,
        $longitude,
        $latitude,
        $courier_id
    );
    $result = $stmt->execute();

    if ($result) {
        $recipient_id = $conn->insert_id;

        // Step 2: Generate QR code automatically
        $qrData = "Recipient ID: $recipient_id\nName: $name\nLocation: $location";
        $folderPath = "../uploads/qr_codes/";

        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $fileName = "qr_" . $recipient_id . ".png";
        $filePath = $folderPath . $fileName;

        QRcode::png($qrData, $filePath, QR_ECLEVEL_L, 10);

        // Step 3: Save to qr_code table
        $qrValue = "uploads/qr_codes/" . $fileName;
        $stmtQR = $conn->prepare("
            INSERT INTO qr_code (recipient_id, qr_value, generated_at)
            VALUES (?, ?, NOW())
        ");
        $stmtQR->bind_param("is", $recipient_id, $qrValue);
        $stmtQR->execute();

        // Step 4: Create first timeline event
        $stmtLog1 = $conn->prepare("
            INSERT INTO parcel_log (recipient_id, status, updated_at)
            VALUES (?, 'Parcel registered in system', NOW())
        ");
        $stmtLog1->bind_param("i", $recipient_id);
        $stmtLog1->execute();

        // Step 5: Parcel is now in delivery
        $stmtLog2 = $conn->prepare("
            INSERT INTO parcel_log (recipient_id, status, updated_at)
            VALUES (?, 'Parcel in delivery', NOW())
        ");
        $stmtLog2->bind_param("i", $recipient_id);
        $stmtLog2->execute();

        // Step 6: Update controller to reflect delivery started
        $conn->query("
            UPDATE tbl_controller 
            SET status = 'Delivery',
                user_verify = 0
        ");
    }

    header("Location: ../interface/admin/recipient.php?success=true");
    exit();
}
?>
