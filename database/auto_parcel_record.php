<?php
include("to_connect.php");
header('Content-Type: application/json');

$query = "
SELECT 
    r.recipient_id,
    r.tracking_number,
    r.name,
    r.email,
    r.no_tel,
    r.location,

    (
        SELECT status
        FROM parcel_log
        WHERE recipient_id = r.recipient_id
        ORDER BY updated_at DESC
        LIMIT 1
    ) AS status

FROM recipient r
ORDER BY r.recipient_id DESC
";

$result = mysqli_query($conn, $query);
$data = [];
$no = 1;

while ($row = mysqli_fetch_assoc($result)) {
    if ($row['status'] == 'Parcel delivered') {
        $statusBadge = "<span class='badge badge-success'>Delivered</span>";
    } else {
        $statusBadge = "<span class='badge badge-warning'>Delivery</span>";
    }

    $data[] = [
        "no" => $no++,
        "recipient_id" => $row['recipient_id'],
        "recipient_name" => $row['name'],
        "no_tel" => $row['no_tel'],
        "email" => $row['email'],
        "tracking_number" => $row['tracking_number'],
        "location" => $row['location'],
        "status" => $statusBadge
    ];
}

echo json_encode(["data" => $data]);
?>
