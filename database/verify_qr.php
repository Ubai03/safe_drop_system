<?php
header('Content-Type: application/json');
include("to_connect.php");

// ---- TOTP (Google Authenticator) helpers ----
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
$token        = $conn->real_escape_string($input['token'] ?? '');
/*$qr_code      = $conn->real_escape_string($input['qr_code'] ?? '');
$last_digits  = $conn->real_escape_string($input['last_digits'] ?? '');*/
$otp = $conn->real_escape_string($input['otp'] ?? '');

if (!$recipient_id || !$token || !$otp) {
    echo json_encode(["status" => "error", "message" => "Missing required fields."]);
    exit;
}

//Verify the tracking link
$query = "SELECT * FROM tracking_links WHERE recipient_id='$recipient_id' AND token='$token'";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) === 0) {
    echo json_encode(["status" => "error", "message" => "Invalid or expired tracking link."]);
    exit;
}

$tracking = mysqli_fetch_assoc($result);

if (empty($tracking['totp_secret'])) {
    echo json_encode(["status" => "error", "message" => "Authenticator not set."]);
    exit;
}

$totp_secret = $tracking['totp_secret'];

//Get last recorded remaining attempts
$attempt_query = "
    SELECT attempt_remaining 
    FROM access_log 
    WHERE recipient_id = '$recipient_id'
    ORDER BY attempted_at DESC 
    LIMIT 1
";
$attempt_result = mysqli_query($conn, $attempt_query);
$attempt_row = mysqli_fetch_assoc($attempt_result);
$attempt_remaining = intval($attempt_row['attempt_remaining'] ?? -1); // -1 means no record yet

//Handle OTP check
if (verifyTOTP($totp_secret, $otp)) {
    // CORRECT PASSWORD
    mysqli_query($conn, "
        UPDATE tbl_controller 
        SET user_verify = 1, status = 'Delivered'
    ");
    mysqli_query($conn, "
        UPDATE parcel_log 
        SET status = 'Delivered', updated_at = NOW()
        WHERE recipient_id = '$recipient_id' AND status = 'Delivery'
        ORDER BY updated_at DESC
        LIMIT 1
    ");
    mysqli_query($conn, "
        INSERT INTO access_log (recipient_id, attempted_at, attempt_remaining)
        VALUES ('$recipient_id', NOW(), 0)
    ");
    echo json_encode(["status" => "success", "message" => "✅ Verified successfully! Parcel delivered."]);
    exit;
}

//Wrong OTP
if ($attempt_remaining < 0) {
    $attempt_remaining = 3;
}

$new_remaining = $attempt_remaining - 1;

mysqli_query($conn, "
    INSERT INTO access_log (recipient_id, attempted_at, attempt_remaining)
    VALUES ('$recipient_id', NOW(), '$new_remaining')
");

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
