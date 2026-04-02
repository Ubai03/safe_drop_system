<?php
header('Content-Type: application/json');
include("to_connect.php");

function base32Decode($base32) {
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
    $base32 = strtoupper($base32);
    $binary = '';
    foreach (str_split($base32) as $char) {
        $pos = strpos($alphabet, $char);
        if ($pos === false) continue;
        $binary .= str_pad(decbin($pos), 5, '0', STR_PAD_LEFT);
    }
    $bytes = '';
    foreach (str_split($binary, 8) as $byte) {
        if (strlen($byte) === 8) {
            $bytes .= chr(bindec($byte));
        }
    }
    return $bytes;
}

function verifyTOTP($secret, $code, $window = 1) {
    if (!preg_match('/^\d{6}$/', $code)) return false;

    $secretKey = base32Decode($secret);
    $time = floor(time() / 30);

    for ($i = -$window; $i <= $window; $i++) {
        $counter = pack('N*', 0) . pack('N*', $time + $i);
        $hash = hash_hmac('sha1', $counter, $secretKey, true);
        $offset = ord(substr($hash, -1)) & 0x0F;
        $truncated =
            ((ord($hash[$offset]) & 0x7F) << 24) |
            ((ord($hash[$offset + 1]) & 0xFF) << 16) |
            ((ord($hash[$offset + 2]) & 0xFF) << 8) |
            (ord($hash[$offset + 3]) & 0xFF);
        $otp = $truncated % 1000000;

        if (str_pad($otp, 6, '0', STR_PAD_LEFT) === $code) {
            return true;
        }
    }
    return false;
}

$input = json_decode(file_get_contents("php://input"), true);

$recipient_id = intval($input['recipient_id'] ?? 0);
$token = $input['token'] ?? '';
$otp = $input['otp'] ?? '';

if (!$recipient_id || !$token || !$otp) {
    echo json_encode(["status" => "error", "message" => "Missing required fields."]);
    exit;
}

// Verify the tracking link
$stmt = $conn->prepare(
    "SELECT totp_secret FROM tracking_links 
     WHERE recipient_id = ? AND token = ?"
);
$stmt->bind_param("is", $recipient_id, $token);
$stmt->execute();
$stmt->bind_result($totp_secret);
$found = $stmt->fetch();
$stmt->close();

if (!$found) {
    echo json_encode(["status" => "error", "message" => "Invalid or expired tracking link."]);
    exit;
}

if (empty($totp_secret)) {
    echo json_encode(["status" => "error", "message" => "Authenticator not set."]);
    exit;
}

// Get last recorded remaining attempts
$stmt = $conn->prepare(
    "SELECT attempt_remaining
     FROM access_log
     WHERE recipient_id = ?
     ORDER BY attempted_at DESC
     LIMIT 1"
);
$stmt->bind_param("i", $recipient_id);
$stmt->execute();
$stmt->bind_result($attempt_remaining_db);
$has_row = $stmt->fetch();
$stmt->close();

$attempt_remaining = $has_row ? intval($attempt_remaining_db) : -1;

// Handle OTP check
if (verifyTOTP($totp_secret, $otp)) {
    $stmt = $conn->prepare(
        "INSERT INTO parcel_log (recipient_id, status, updated_at)
         VALUES (?, 'Recipient verified OTP', NOW())"
    );
    $stmt->bind_param("i", $recipient_id);
    $stmt->execute();
    $stmt->close();

    $conn->query("UPDATE tbl_controller SET user_verify = 1, status = 'Delivered'");

    $stmt = $conn->prepare(
        "INSERT INTO parcel_log (recipient_id, status, updated_at)
         VALUES (?, 'Parcel delivered', NOW())"
    );
    $stmt->bind_param("i", $recipient_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare(
        "INSERT INTO access_log (recipient_id, attempted_at, attempt_remaining)
         VALUES (?, NOW(), 0)"
    );
    $stmt->bind_param("i", $recipient_id);
    $stmt->execute();
    $stmt->close();

    echo json_encode([
        "status" => "success",
        "message" => "✅ Verified successfully! Parcel delivered."
    ]);
    exit;
}

// Wrong OTP
if ($attempt_remaining < 0) {
    $attempt_remaining = 3;
}

$new_remaining = $attempt_remaining - 1;

$stmt = $conn->prepare(
    "INSERT INTO access_log (recipient_id, attempted_at, attempt_remaining)
     VALUES (?, NOW(), ?)"
);
$stmt->bind_param("ii", $recipient_id, $new_remaining);
$stmt->execute();
$stmt->close();

if ($new_remaining <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "No attempts left."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Incorrect code. $new_remaining attempt(s) remaining."
    ]);
}
exit;
?>