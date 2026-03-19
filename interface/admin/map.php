<!DOCTYPE html>

<?php
session_start();
include("../../database/to_connect.php");

// Session timeout (20 mins)
$inactivity_time = 20 * 60;
if (isset($_SESSION['last_timestamp']) && (time() - $_SESSION['last_timestamp']) > $inactivity_time) {
    session_unset();
    session_destroy();
    header("Location: ../../database/logout_process.php");
    exit();
} else {
    $_SESSION['last_timestamp'] = time();
    session_regenerate_id(true);
}

$query = "SELECT * FROM user WHERE user_id = '" . $_SESSION['adminID'] . "'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$nameAdmin = $row["name"];
?>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>SAFE DROP SYSTEM - Map</title>

    <link rel="icon" href="../../css/pictures/safeDrop_logo.png">

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800,900" rel="stylesheet">

    <!-- Template Styles -->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/dashboard_interface.css" rel="stylesheet">

    <!-- Leaflet Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

    <!-- JS Dependencies -->
    <script src="../../jQuery/jquery.min.js"></script>
    <script src="../../jQuery/bootstrap.bundle.min.js"></script>
    <script src="../../jQuery/jquery.easing.min.js"></script>
    <script src="../../jQuery/sb-admin-2.min.js"></script>

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
    </style>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
            <br>
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div>
                    <img class="" src="../../css/pictures/safeDrop_logo.png" id="logo" width="130px" height="110px" style="margin-top: 20px">
                    <br><br>
                </div>
            </a>

            <br><br>


            <hr class="sidebar-divider my-0">

            <li class="nav-item active">
                <a class="nav-link" href="index.php" id="sidebar">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="recipient.php" id="sidebar">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Recipient</span>
                </a>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="courier.php" id="sidebar">
                    <i class="fas fa-fw fa-user" style="color: white;"></i>
                    <span>Courier</span></a>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="map.php" id="sidebar" style="background-color:#854643; font-weight:bold;">
                    <i class="fas fa-fw fa-map"></i>
                    <span>Map</span>
                </a>
            </li>

            <hr class="sidebar-divider my-0">
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <h5 class="h5 mb-0" style="color: white; font-weight: bold;">Welcome!</h5>

                    <ul class="navbar-nav ml-auto">
                         <li class="nav-item topbar-icon dropdown hidden-caret" style="position:relative;">
                            <a class="nav-link dropdown-toggle" id="notifDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-fw fa-bell" style="color: #FFD700;"></i>
                                <span class="notification" id="count_noti"></span>
                            </a>
                            <ul class="dropdown-menu notif-box animated fadeIn dropdown-menu custom-noti-dropdown" aria-labelledby="notifDropdown">
                                <li>
                                    <div class="dropdown-title" id="notice_noti"></div>
                                </li>
                                <li>
                                    <div class="notif-scroll scrollbar-outer">
                                        <div class="notif-center" id="noti_content">
                                            
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- User Dropdown -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                                <span class="mr-3 d-none d-lg-inline" style="color: white; font-size: 14px; letter-spacing: 1px"><b><?php echo strtoupper($nameAdmin) ?></b></span>
                                <img src="../../css/pictures/admin.png" alt="" width="50px" height="50px" class="rounded-circle">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <div class="user-box d-flex align-items-center">
                                        <div class="avatar-lg">
                                            <img src="../../css/pictures/admin.png" alt="Profile Image" class="avatar-img rounded-circle">
                                        </div>
                                        <div class="u-text ml-3">
                                            <h4><?php echo $nameAdmin; ?></h4>
                                            <a href="profile.php" class="btn btn-sm mt-2 submitBtn">Profile</a>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-black-400"></i> Logout
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Logout Modal -->
                <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><b><i class="fas fa-fw fa-exclamation-triangle"></i> Warning Notification</b></h5>
                            </div>
                            <div class="modal-body">Are you sure to log out?</div>
                            <div class="modal-footer">
                                <button type="button" class="btn notiClose" data-dismiss="modal">No</button>
                                <a type="button" class="btn notiConfirm" href="../../database/logout_process.php">Yes</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Logout Modal -->

                <!-- Page Content -->
                <div class="container-fluid">
                    <h3 class="h3 mb-3 text-900" id="title">
                        <strong><i class="fas fa-fw fa-map-marked-alt"></i> Recipient Map</strong>
                    </h3>

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div id="map" style="height:600px; width:100%; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.2);"></div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; SAFE DROP SYSTEM</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- map pin point color -->
    <!-- map pin point color -->
<script>
const markerColors = [
  "red", "green", "orange", "purple", "yellow",
  "blue", "pink", "violet", "grey", "black"
];

// Correct CDN for colored markers
const iconBaseURL = "https://cdn.jsdelivr.net/gh/pointhi/leaflet-color-markers@master/img/";

// Function to create colored marker icon
function getColoredIcon(color) {
  return new L.Icon({
    iconUrl: `${iconBaseURL}marker-icon-${color}.png`,
    shadowUrl: `${iconBaseURL}marker-shadow.png`,
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  });
}

fetch('../../database/get_recipients.php')
  .then(response => response.json())
  .then(data => {
    console.log("Recipient data:", data); // Debug

    const map = L.map('map');

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Auto-zoom to fit all markers
    const bounds = L.latLngBounds([]);
    data.forEach((recipient, index) => {
      if (recipient.latitude && recipient.longitude) {
        // Pick color and apply custom icon
        const color = markerColors[index % markerColors.length];
        const icon = getColoredIcon(color);

        // Use the custom icon (previously missing)
        const marker = L.marker([recipient.latitude, recipient.longitude], { icon })
          .addTo(map)
          .bindPopup(`
            <b>${recipient.name}</b><br>
            📍 ${recipient.location}<br>
            📞 ${recipient.no_tel}<br>
            ✉️ ${recipient.email}
          `);

          // Add this marker to the bounds
          bounds.extend(marker.getLatLng());
      }
    });
    // Fit map to all markers nicely
    if (bounds.isValid()) {
        map.fitBounds(bounds, { padding: [50, 50] });
    } else {
        map.setView([2.7, 101.95], 10); // fallback if no markers
    }
  })
  .catch(err => console.error('Error fetching recipient data:', err));
</script>

<script src="../../javascript.js"></script>

</body>
</html>
