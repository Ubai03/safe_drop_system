<?php
header("Content-Type: application/json");

/**
 * Fetch coordinates from OpenStreetMap Nominatim API
 * using cURL (reliable for localhost / XAMPP)
 */
function fetch($q) {
    $url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($q) . "&format=json&limit=1";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false, // allow HTTPS on localhost
        CURLOPT_USERAGENT => "SafeDropSystem/1.0 (admin@localhost)", // required by Nominatim
        CURLOPT_HTTPHEADER => [
            "Accept: application/json"
        ],
    ]);

    $res = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($res === false || $http_code !== 200) {
        return [];
    }

    $decoded = json_decode($res, true);
    return (is_array($decoded) && count($decoded) > 0) ? $decoded : [];
}


// ========== Main logic ========== //

$q = trim($_GET['q'] ?? '');
if ($q === '') {
    echo json_encode(["error" => "Missing address"]);
    exit;
}

// Always append “Malaysia” for better accuracy
if (stripos($q, 'Malaysia') === false) {
    $q .= ', Malaysia';
}

// 1️⃣ Try full address
$data = fetch($q);

// 2️⃣ Remove postcode and retry
if (empty($data)) {
    $q2 = preg_replace('/\b\d{5}\b/', '', $q);
    $data = fetch($q2);
}

// 3️⃣ Try broader region (e.g., “Rantau, Negeri Sembilan”)
if (empty($data)) {
    $parts = explode(',', $q);
    $lastFew = implode(',', array_slice($parts, -3));
    $data = fetch($lastFew);
}

// 4️⃣ As final fallback, use district-level search
if (empty($data)) {
    $data = fetch("Rantau, Negeri Sembilan, Malaysia");
}

// Return result
if (empty($data)) {
    echo json_encode(["error" => "No coordinates found for that area. Try a broader location such as 'Pedas, Negeri Sembilan'."]);
    exit;
}

echo json_encode($data);
?>
