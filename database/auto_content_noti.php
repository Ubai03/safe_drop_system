<?php
session_start();
include("to_connect.php");
date_default_timezone_set("Asia/Kuala_Lumpur");

// Fetch pending notifications
$query_noti = "
    SELECT n.*, r.name
    FROM notification n
    INNER JOIN recipient r ON n.recipient_id = r.recipient_id
    WHERE n.status = 'pending'
    ORDER BY n.created_at DESC
";
$result_noti = mysqli_query($conn, $query_noti);

if (mysqli_num_rows($result_noti) > 0) {
    echo '<div class="noti-header">📦 You have ' . mysqli_num_rows($result_noti) . ' pending notification(s)</div>';

    while ($row = mysqli_fetch_assoc($result_noti)) {
        $noti_id = $row["noti_id"];
        $recipient_name = htmlspecialchars($row["name"]);
        $content = htmlspecialchars($row["content"]);
        $timestamp = $row["created_at"];

        // Time ago format
        $noti_datetime = new DateTime($timestamp);
        $current_datetime = new DateTime();
        $interval = $current_datetime->diff($noti_datetime);

        if ($interval->d > 0) $time_ago = $interval->d . " day(s) ago";
        elseif ($interval->h > 0) $time_ago = $interval->h . " hour(s) ago";
        elseif ($interval->i > 0) $time_ago = $interval->i . " minute(s) ago";
        elseif ($interval->s > 0) $time_ago = $interval->s . " second(s) ago";
        else $time_ago = "just now";

        // Dynamic color by content type
        $highlight = (stripos($content, 'incorrect') !== false) ? "#ffefc2" : "#fdf1f0";

        echo "
        <div class='noti-item' style='background:$highlight;'>
            <div class='noti-text'>
                <strong>$recipient_name</strong> — $content
            </div>
            <div class='noti-time'><i class='far fa-clock'></i> $time_ago</div>
            <div class='noti-actions'>
                <button class='btn btn-success btn-sm' onclick=\"updateStatus($noti_id, 'approved')\" title='Approve'>
                    <i class='fas fa-check'></i>
                </button>
                <button class='btn btn-danger btn-sm' onclick=\"updateStatus($noti_id, 'rejected')\" title='Reject'>
                    <i class='fas fa-times'></i>
                </button>
            </div>
        </div>";
    }
} else {
    echo '<div class="noti-empty"><i class="far fa-bell-slash"></i> No new notifications</div>';
}
?>
