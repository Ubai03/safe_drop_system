<?php
include("to_connect.php");
header('Content-Type: application/json');

$query = "
    SELECT 
        r.recipient_id,
        r.name,
        r.email,
        r.no_tel,
        r.location,
        l.status,
        l.updated_at
    FROM parcel_log l
    INNER JOIN recipient r ON l.recipient_id = r.recipient_id
    ORDER BY l.updated_at DESC
";

$result = mysqli_query($conn, $query);
$data = [];
$no = 1;

while ($row = mysqli_fetch_assoc($result)) {
    $statusBadge = ($row['status'] == 'Delivery')
        ? "<span class='badge badge-warning'>Delivery</span>"
        : "<span class='badge badge-success'>Delivered</span>";

    $data[] = [
        "no" => $no++,
        "recipient_id" => $row['recipient_id'],
        "recipient_name" => $row['name'],
        "no_tel" => $row['no_tel'],
        "email" => $row['email'],
        "location" => $row['location'],
        "status" => $statusBadge
    ];
}

echo json_encode(["data" => $data]);
?>
