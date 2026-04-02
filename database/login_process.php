<?php
session_start();
include("to_connect.php");

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$captchaToken = $_POST['h-captcha-response'] ?? '';
$ip = $_SERVER['REMOTE_ADDR'] ?? '';

if(empty($captchaToken)){
    header("Location: ../interface/login.php?error=captchaMissing");
    exit();
}

list($captchaSuccess, $captchaErrors) = verifyToken($captchaToken, $ip);

if(!$captchaSuccess){
    header("Location: ../interface/login.php?error=captchaFailed");
    exit();
}

$stmt = $conn->prepare("SELECT user_id, username, password FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

$stmt->bind_result($db_user_id, $db_username, $db_password);
$stmt->fetch();
$stmt->close();

if($db_username) {
    if(password_verify($password, $db_password)){

        $_SESSION['username'] = $db_username;
        $_SESSION['adminID'] = $db_user_id;

        header("location: ../interface/admin/index.php");
        exit();
    } else {
        header("Location: ../interface/login.php?error=wrongPsw");
        exit();
    }
} else {
    header("Location: ../interface/login.php?error=wrongUsername");
    exit();
}

function verifyToken(string $token, string $ip): array {
  $payload = http_build_query([
    "secret" => "ES_1e1f6e1fcc584a7cacfa21c3f02c3c40",
    "response" => $token,
    "remoteip" => $ip,
    "sitekey" => "32b6e35a-dcb6-4624-bdda-10beb12c29d4",
  ]);
  $ctx = stream_context_create([
    "http" => [
      "method" => "POST",
      "header" => "Content-type: application/x-www-form-urlencoded\r\n",
      "content" => $payload,
      "timeout" => 5,
    ],
  ]);
  $raw = file_get_contents(
    "https://api.hcaptcha.com/siteverify",
    false,
    $ctx
  );
  $j = json_decode($raw, true);
  if (!empty($j["success"])) {
    return [true, []];
  }
  return [false, $j["error-codes"] ?? []];
}
?>