<?php
header('Content-Type: application/json; charset=utf-8');

// Clean output for JSON
while (ob_get_level()) ob_end_clean();

error_reporting(E_ALL);
ini_set('display_errors', 0); // prevent raw HTML errors

include("to_connect.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

// Helper: consistent JSON response
function jsonResponse($status, $message, $extra = []) {
    echo json_encode(array_merge(["status" => $status, "message" => $message], $extra));
    exit;
}

try {
    if (empty($_POST['recipient_id'])) {
        jsonResponse("error", "Missing recipient_id");
    }

    $recipient_id = intval($_POST['recipient_id']);

    // --- Fetch recipient info ---
    $query = "SELECT name, email FROM recipient WHERE recipient_id = $recipient_id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        throw new Exception("Database error: " . mysqli_error($conn));
    }

    $recipient = mysqli_fetch_assoc($result);

    if (!$recipient || empty($recipient['email'])) {
        jsonResponse("error", "Invalid recipient or no email found");
    }

    // --- Generate tracking token ---
    $token = bin2hex(random_bytes(16));
    $insert = mysqli_query($conn, "
        INSERT INTO tracking_links (recipient_id, token)
        VALUES ('$recipient_id', '$token')
        ON DUPLICATE KEY UPDATE token='$token', created_at=NOW()
    ");

    if (!$insert) {
        throw new Exception("Failed to update tracking_links: " . mysqli_error($conn));
    }

    // --- Build tracking link ---
    $tracking_link = "http://localhost/safe_drop_system/interface/recipient/tracking.php?recipient_id=$recipient_id&token=$token";

    // --- Configure PHPMailer (using Mailtrap for local testing) ---
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth   = true;
    $mail->Username   = '186359ececc29a'; // ← your Mailtrap username
    $mail->Password   = 'e6844e214b74c9'; // ← your Mailtrap password
    $mail->Port       = 2525;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->SMTPDebug  = 0;

    $mail->setFrom('safedrop12@gmail.com', 'SafeDrop');
    $mail->addAddress($recipient['email'], $recipient['name']);
    $mail->isHTML(true);
    $mail->Subject = 'Your Parcel is on the Way!';
    $mail->Body = "
        <p>Dear <strong>{$recipient['name']}</strong>,</p>
        <p>Your parcel box is ready for delivery! You can track it in real time using the link below:</p>
        <p><a href='$tracking_link' style='color:#0066cc; font-weight:bold;'>Track My Parcel</a></p>
        <p>Thank you for using <b>SafeDrop System</b>.</p>
        <p><small>This link is valid for your current delivery only.</small></p>
    ";

    if (!$mail->send()) {
        throw new Exception("Mailer Error: " . $mail->ErrorInfo);
    }

    // --- Success response ---
    jsonResponse("success", "Tracking email sent to {$recipient['email']}", [
        "tracking_link" => $tracking_link
    ]);

} catch (Throwable $e) {
    jsonResponse("error", $e->getMessage());
}
?>
