<?php
include("to_connect.php");
require_once '../../phpqrcode/qrlib.php'; // QR library

// Step 1: Find all QR codes older than 24 hours
$query = "
    SELECT r.recipient_id, r.name, r.location 
    FROM recipient r
    JOIN qr_code q ON r.recipient_id = q.recipient_id
    WHERE q.generated_at < (NOW() - INTERVAL 24 HOUR)
";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$folderPath = "../uploads/qr_codes/";
if (!is_dir($folderPath)) mkdir($folderPath, 0777, true);

$updated = 0;

// Step 2: Loop through expired recipients
while ($row = mysqli_fetch_assoc($result)) {
    $recipient_id = $row["recipient_id"];
    $name = $row["name"];
    $location = $row["location"];

    // Generate new QR data
    $qrData = "Recipient ID: $recipient_id\nName: $name\nLocation: $location";
    $fileName = "qr_" . $recipient_id . ".png";
    $filePath = $folderPath . $fileName;

    // Generate and overwrite the QR image
    QRcode::png($qrData, $filePath, QR_ECLEVEL_L, 10);

    // Update database timestamp
    $qrValue = "uploads/qr_codes/" . $fileName;
    $update = "
        UPDATE qr_code 
        SET qr_value = '$qrValue', generated_at = NOW()
        WHERE recipient_id = '$recipient_id'
    ";
    mysqli_query($conn, $update);

    $updated++;
}

//echo "Auto regeneration complete. Total regenerated: $updated";
?>
