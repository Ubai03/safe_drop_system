<?php
header('Content-Type: application/json');
include("to_connect.php");

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
    echo json_encode([
        "status"=>"error",
        "message"=>"Missing credentials"
    ]);
    exit;
}

// Step 1: Find user by username ONLY
$stmt = $conn->prepare("
    SELECT user_id, name, password
    FROM user
    WHERE username = ?
    LIMIT 1
");

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {

    $row = $result->fetch_assoc();

    // Step 2: Verify hashed password
    if (password_verify($password, $row['password'])) {

        echo json_encode([
            "status"=>"success",
            "user_id"=>$row['user_id'],
            "name"=>$row['name']
        ]);

    } else {

        echo json_encode([
            "status"=>"error",
            "message"=>"Invalid password"
        ]);
    }

} else {

    echo json_encode([
        "status"=>"error",
        "message"=>"User not found"
    ]);
}

$stmt->close();
$conn->close();