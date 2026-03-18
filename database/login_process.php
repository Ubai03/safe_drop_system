<?php
session_start();
include("to_connect.php");
extract($_POST);

$query = "SELECT * FROM user WHERE username = '$username'";
$result = mysqli_query($conn, $query) or trigger_error(mysqli_error($conn));
$rows = mysqli_fetch_array($result);

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

if($rows) {
    if (password_verify($password, $rows['password'])){
        // no user_type check, directly login
        $_SESSION['username'] = $username;
        $_SESSION['adminID'] = $rows['user_id'];
        header("location: ../interface/admin/index.php");
        exit();
    } else {
        header("Location: ../interface/login.php?error=wrongPsw");
    }
} else {
    header("Location: ../interface/login.php?error=wrongUsername");
}
?>
