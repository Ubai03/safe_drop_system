<?php
require_once "../../phpqrcode/qrlib.php";

include("../../database/to_connect.php");

// Get recipient info from tracking link
$recipient_id = isset($_GET['recipient_id']) ? intval($_GET['recipient_id']) : 0;
$token = isset($_GET['token']) ? $_GET['token'] : '';

if (!$recipient_id || !$token) {
    die("<h3 style='color:red;'>Invalid tracking link.</h3>");
}

// 1. Check latest parcel status
$parcelStatusQuery = "
    SELECT status 
    FROM parcel_log 
    WHERE recipient_id = '$recipient_id' 
    ORDER BY updated_at DESC 
    LIMIT 1
";
$statusResult = mysqli_query($conn, $parcelStatusQuery);

if (!$statusResult || mysqli_num_rows($statusResult) === 0) {
    die("<h3 style='color:red;'>No parcel record found.</h3>");
}

$parcelStatus = mysqli_fetch_assoc($statusResult)['status'];

// 2. If delivered, stop tracking
if (
    strtolower($parcelStatus) === 'delivered' ||
    strtolower($parcelStatus) === 'parcel delivered'
) {
    die("<h3 style='color:red;'>This parcel has already been delivered. Tracking link is no longer valid.</h3>");
}

// 3. Only check token if parcel is not delivered
$query = "SELECT * FROM tracking_links WHERE recipient_id='$recipient_id' AND token='$token'";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) === 0) {
    die("<h3 style='color:red;'>Invalid or expired token.</h3>");
}

$tracking = mysqli_fetch_assoc($result);

if (empty($tracking['totp_secret'])) {
    die("<h3 style='color:red;'>Authenticator setup not available.</h3>");
}

$totp_secret = $tracking['totp_secret'];

$issuer = "SafeDrop";
$label  = "SafeDrop:Recipient_" . $recipient_id;

$otpAuthUri = "otpauth://totp/" . urlencode($label)
    . "?secret=" . $totp_secret
    . "&issuer=" . urlencode($issuer);

$qrDir = "../uploads/qr_codes/";
if (!is_dir($qrDir)) {
    mkdir($qrDir, 0777, true);
}

$gaQrFile = "ga_recipient_" . $recipient_id . ".png";
$gaQrPath = $qrDir . $gaQrFile;

QRcode::png($otpAuthUri, $gaQrPath, QR_ECLEVEL_L, 6);

// Get recipient destination location
$recipient_query = "SELECT latitude, longitude, location FROM recipient WHERE recipient_id='$recipient_id' LIMIT 1";
$recipient_result = mysqli_query($conn, $recipient_query);

if (!$recipient_result || mysqli_num_rows($recipient_result) === 0) {
    die("<h3 style='color:red;'>Recipient location not found.</h3>");
}

$recipient = mysqli_fetch_assoc($recipient_result);
$dest_lat = $recipient['latitude'];
$dest_lng = $recipient['longitude'];
$dest_location = addslashes($recipient['location']);


// Get latest parcel location
$parcel_query = "SELECT parcel_lat, parcel_long FROM tbl_controller LIMIT 1"; 
$parcel_result = mysqli_query($conn, $parcel_query);

if (!$parcel_result || mysqli_num_rows($parcel_result) === 0) {
    die("<h3 style='color:red;'>No parcel location found yet.</h3>");
}

$parcel = mysqli_fetch_assoc($parcel_result);
$lat = $parcel['parcel_lat'];
$lng = $parcel['parcel_long'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SafeDrop Parcel Tracker</title>
    <link rel="icon" href="../../css/pictures/safeDrop_logo.png">

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800,900" rel="stylesheet">

    <!-- Template Styles -->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/dashboard_interface.css" rel="stylesheet">

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        #map {
            height: 600px;
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .leaflet-popup-content {
            font-size: 14px;
        }
        #reader {
            width: 320px;
            height: 240px;
            margin: 20px auto;
            background: #000;
            display: none; /* default hidden */
        }
        @media (max-width: 768px) {
            #map {
                height: 45vh;
                min-height: 300px;
            }
            #otpCode {
                width: 120px !important;
                font-size: 18px;
            }
            img[alt="Google Authenticator QR"] {
                width: 150px !important;
            }
            h3#title {
                font-size: 20px;
                text-align: center;
            }
            #accordionSidebar {
                display: none;
            }
            #sidebarToggleTop{
                display: none !important;

            }
        }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
            <br>
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div>
                    <img src="../../css/pictures/safeDrop_logo.png" width="130px" height="110px" style="margin-top: 20px">
                    <br><br>
                </div>
            </a>
            <br><br>
            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="#" id="sidebar" style="background-color:#854643; font-weight:bold;">
                    <i class="fas fa-fw fa-map"></i>
                    <span>Map</span>
                </a>
            </li>
            <hr class="sidebar-divider my-0">
        </ul>
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <h5 class="h5 mb-0" style="color: white; font-weight: bold;">Safe Drop System</h5>
                </nav>
                <!-- Page Content -->
                <div class="container-fluid">
                    <h3 class="h3 mb-3 text-900" id="title">
                        <strong><i class="fas fa-fw fa-map-marked-alt"></i> Parcel Tracker</strong>
                    </h3>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
                <!-- QR Scanner Section -->
                <div class="card shadow mb-4 text-center">
                    <div class="card-body">
                        <p>Scan the QR on the box using <b>Google Authenticator</b>, then enter the 6-digit code.</p>
                        <button id="startScan" class="btn btn-success"><i class="fas fa-key"></i> Enter 6-digit Code</button>
                        <div id="otpSection" style="display:none; margin-top:15px;">
                          <input type="text" id="otpCode" maxlength="6" class="form-control text-center" placeholder="123456" style="width:150px; margin:auto; letter-spacing:4px;">
                          <button class="btn btn-primary mt-2" id="verifyOtpBtn">
                              Verify Code
                          </button>
                          <p id="verifyMessage" style="margin-top:10px; font-weight:bold;"></p>
                        </div>
                        <div id="reader" style="width: 320px; margin: 20px auto; display:none;"></div>
                        <p id="scanResult" style="font-weight:bold; color:#854643; margin-top:15px;"></p>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; SAFE DROP SYSTEM</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

<!-- Fix for browser autoplay issues -->
<style>
  #reader video {
    width: 100% !important;
    height: auto !important;
    object-fit: cover;
    background: #000;
    transform: none !important;
  }
</style>

<!--  1. Load map first -->
<script>
const parcelLat = parseFloat("<?php echo $lat; ?>");
const parcelLng = parseFloat("<?php echo $lng; ?>");
const destLat = parseFloat("<?php echo $dest_lat; ?>");
const destLng = parseFloat("<?php echo $dest_lng; ?>");
const destLocation = "<?php echo $dest_location; ?>";

// Initialize map
const map = L.map('map').setView([parcelLat, parcelLng], 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Icons
const parcelIcon = L.icon({
  iconUrl: "https://cdn.jsdelivr.net/gh/pointhi/leaflet-color-markers@master/img/marker-icon-red.png",
  shadowUrl: "https://cdn.jsdelivr.net/gh/pointhi/leaflet-color-markers@master/img/marker-shadow.png",
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowSize: [41, 41]
});

// Add markers
let parcelMarker = L.marker([parcelLat, parcelLng], { icon: parcelIcon }).addTo(map)
  .bindPopup("<b>Your Parcel</b><br>Live location update.").openPopup();

// Fit bounds so both markers are visible
map.fitBounds([
  [parcelLat, parcelLng],
  [destLat, destLng]
]);

// Function to update parcel location live
async function updateParcelLocation() {
  try {
    const response = await fetch("../../database/get_parcel_location.php");
    const data = await response.json();
    if (data.status === "success") {
      const newLat = parseFloat(data.lat);
      const newLng = parseFloat(data.lng);
      parcelMarker.setLatLng([newLat, newLng]);
      map.panTo([newLat, newLng]);
    }
  } catch (err) {
    console.error("Error updating parcel location:", err);
  }
}

// Auto-update every 5 seconds
setInterval(updateParcelLocation, 5000);
// Auto-update every 5 seconds (for parcel marker only)
setInterval(updateParcelLocation, 5000);
</script>

<!-- 2. Load the QR library AFTER the map -->
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- 3. Wait until everything is loaded before using Html5Qrcode -->
<script>
window.addEventListener('load', async () => {
  console.log("✅ Page fully loaded");

  const recipientId = "<?php echo $recipient_id; ?>";
  const token = "<?php echo $token; ?>";
  const startScanBtn = document.getElementById('startScan');
  const modal = document.getElementById('verifyModal');
  const verifyMsg = document.getElementById('verifyMessage');
  const cancelBtn = document.getElementById('cancelVerify');
  const verifyOtpBtn = document.getElementById("verifyOtpBtn");

  if (verifyOtpBtn) {
    verifyOtpBtn.addEventListener("click", () => {
          const otp = document.getElementById("otpCode").value.trim();

          if (otp.length !== 6 || !/^\d+$/.test(otp)) {
              verifyMsg.style.color = "red";
              verifyMsg.innerText = "Enter a valid 6-digit code.";
              return;
          }
          verifyMsg.style.color = "#555";
          verifyMsg.innerText = "Verifying...";

          fetch("../../database/verify_qr.php", {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({
                  recipient_id: recipientId,
                  token: token,
                  otp: otp
              })
          })
          .then(res => res.json())
          .then(data => {
              if (data.status === "success") {
                  verifyMsg.style.color = "green";
                  verifyMsg.innerText = data.message;
                  setTimeout(() => location.reload(), 1500);
              } else {
                  verifyMsg.style.color = "red";
                  verifyMsg.innerText = data.message;
              }
          })
          .catch(err => {
              console.error(err);
              verifyMsg.style.color = "red";
              verifyMsg.innerText = "Verification error.";
          });
      });
  }

  // When user clicks Start Scan
  startScanBtn.addEventListener('click', () => {
    // Button is already geofence-controlled
    document.getElementById("otpSection").style.display = "block";
    startScanBtn.disabled = true;
  });
});
</script>
<script>
// Recipient vibration alert every 3 seconds
setInterval(() => {
    fetch("../../database/check_vibration.php")
        .then(res => res.json())
        .then(data => {
            if (data.status === "success" && data.vibrate == 1) {
                showVibrationWarning();
            }
        })
        .catch(err => console.error("Vibration check error:", err));
}, 3000);

function showVibrationWarning() {
    if (!window.vibrationWarned) {
        window.vibrationWarned = true;
        alert("⚠️ Warning: The parcel box is vibrating!");
        setTimeout(() => {
            window.vibrationWarned = false;
        }, 10000);
    }
}
</script>
</body>
</html>

