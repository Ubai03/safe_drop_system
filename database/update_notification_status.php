<?php
include("to_connect.php");

if (isset($_POST['id']) && isset($_POST['action'])) {
    $id = intval($_POST['id']);
    $action = $_POST['action'];

    $query_recipient = "SELECT recipient_id FROM notification WHERE noti_id = $id";
    $result = mysqli_query($conn, $query_recipient);
    $row = mysqli_fetch_assoc($result);
    $recipient_id = $row['recipient_id'];

    if (in_array($action, ['approved', 'rejected'])) {
        $update = "UPDATE notification SET status = '$action' WHERE noti_id = $id";
        mysqli_query($conn, $update);

        if ($action === 'approved' && $recipient_id) {
            // Give 2 new attempts
            mysqli_query($conn, "
                INSERT INTO access_log (recipient_id, attempted_at, attempt_remaining)
                VALUES ($recipient_id, NOW(), 2)
            ");
        }

        // Log admin decision
        mysqli_query($conn, "
            INSERT INTO access_log (recipient_id, attempted_at, attempt_remaining)
            VALUES ($recipient_id, NOW(), 0)
        ");

        echo "success";
    } else {
        echo "invalid";
    }
} else {
    echo "error";
}
?>
