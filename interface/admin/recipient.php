<!DOCTYPE html>

<!-- session start -->
<?php
    session_start();

    include("../../database/to_connect.php"); 

    require_once "../../phpqrcode/qrlib.php";
    // Automatically regenerate expired QR codes once per page load
    @include("../../database/auto_regenerate_qr.php");


    // Set the inactivity time of 20 minutes (1200 seconds)
    $inactivity_time = 20 * 60;

    // Check if the last_timestamp is set and last_timestamp is greater than 20 minutes
    if (isset($_SESSION['last_timestamp']) && (time() - $_SESSION['last_timestamp']) > $inactivity_time) {
        // Unset and destroy the session
        session_unset();
        session_destroy();

        // Redirect user to login page
        header("Location: ../../database/logout_process.php");
        exit();
    } else {
        // Update the last timestamp
        $_SESSION['last_timestamp'] = time();

        // Regenerate new session id and delete old one to prevent session fixation attack
        session_regenerate_id(true);
    }
    
    $query = "SELECT * FROM user WHERE user_id = '".$_SESSION['adminID']."'"; 
    $result = mysqli_query($conn, $query); 

    $row = mysqli_fetch_assoc($result);
	$adminID = $row["user_id"];
    $nameAdmin = $row["name"];

    if (isset($_GET['success']) && $_GET['success'] == 'true') {
        $alert_type = 'success';
        $alert_message = "Successfully saved the product information.";
    } elseif (isset($_GET['success']) && $_GET['success'] == 'trueUpdate') {
        $alert_type = 'success';
        $alert_message = "Successfully update the product information.";
    } elseif (isset($_GET['success']) && $_GET['success'] == 'trueDelete') {
        $alert_type = 'success';
        $alert_message = "Successfully delete the product information.";
    } elseif (isset($_GET['success']) && $_GET['success'] == 'trueDeleteAll') {
        $alert_type = 'success';
        $alert_message = "Successfully delete all the product information.";
    }
?>
<!-- end session start -->

<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SAFE DROP SYSTEM</title>

    <!-- Icon tab -->
    <link rel="icon" href="../../css/pictures/safeDrop_logo.png">

    <!-- Custom fonts for this template -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">     
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../css/dashboard_interface.css" rel="stylesheet">

    <!-- Bootstrap core JavaScript-->
    <script src="../../jQuery/jquery.min.js"></script>
    <script src="../../jQuery/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../jQuery/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../jQuery/sb-admin-2.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../../jQuery/datatables-demo.js"></script>
    <script src="../../jQuery/jquery.dataTables.min.js"></script>
    <script src="../../jQuery/dataTables.bootstrap4.min.js"></script>
    <link href="../../css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Calling validation function-->
    <script src="../../jQuery/jScript.js"></script>

    <script>
		function updateValValue() {
            $.ajax({
                url: 'update_value_scan.php', // Your PHP file for database update
                type: 'POST',
                data: {val_value: 2}, // Only send the value, not the staff ID
                success: function(response) {
					console.log('val_value updated successfully');
				},
				error: function(xhr, status, error) {
					console.log('Error updating val_value: ' + error);
				}
            });
        }

        function updateValueAndShowModal(recipient_id) {
            $.ajax({
                url: 'update_value_delete.php', // Your PHP file for database update
                type: 'POST',
                data: {val_value: 4}, // Only send the value, not the staff ID
                success: function(response) {
                    if(response === 'success') {
                        // Once the database is updated, trigger the modal
                        $('#deleteModalCenter' + staff_id).modal('show');
                    } else {
                        alert('Error updating value');
                    }
                }
            });
        }

        function updateCancelDelete() {
            $.ajax({
                url: 'update_value_delete_cancel.php', // Your PHP file for database update
                type: 'POST',
                data: {val_value: 2}, // Only send the value, not the staff ID
                success: function(response) {
					console.log('val_value updated successfully');
				},
				error: function(xhr, status, error) {
					console.log('Error updating val_value: ' + error);
				}
            });
        }
    </script>

    <style>
        #text-center{
            text-align: center;
        }

        footer.sticky-footer {
            margin-top: auto;   /* pushes footer to bottom */
            position: relative;
            width: 100%;
            background-color: #fff;
        }
    </style>

</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
            <br>
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div>
                    <?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Tablet') !== false)): ?>
                        <img class="rounded-circle" src="../../css/pictures/safeDrop_logo.png" id="logo" width="100px" height="80px">
                    <?php else: ?>
                        <img class="rounded-circle" src="../../css/pictures/safeDrop_logo.png" id="logo" width="130px" height="110px" style="margin-top: 20px">
                        <br><br>
                    <?php endif; ?>
                </div>
            </a>
            
            <br><br>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php" id="sidebar">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Dashboard</span></a>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="recipient.php" id="sidebar" style="background-color: #854643; font-weight: bold;">
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
                <a class="nav-link" href="map.php" id="sidebar">
                    <i class="fas fa-fw fa-map"></i>
                    <span>Map</span>
                </a>
            </li>
            
            <!-- Divider -->
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

                <script>
                    $(document).ready(function() {
                        $('#sidebarToggleTop').on('click', function() {
                            $('body').toggleClass('sidebar-toggled');
                            $('.sidebar').toggleClass('toggled');

                            // Check if sidebar is toggled
                            var isToggled = $('.sidebar').hasClass('toggled');
                        
                        });
                    });
                </script>

                    <!-- Topbar Navbar -->

                    <!-- Welcome Heading -->
                    <?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Tablet') !== false)): ?>
                        <h6 class="h6 mb-0" style="color: white; font-weight: bold;">Welcome!</h6>
                    <?php else: ?>
                        <h5 class="h5 mb-0" style="color: white; font-weight: bold;">Welcome!</h5>
                    <?php endif; ?>

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
                        
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-3 d-none d-lg-inline" style="color: white; font-size: 14px; letter-spacing: 1px"><b><?php echo strtoupper($nameAdmin) ?></b></span>
                                <?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Tablet') !== false)): ?>
                                    <img src="../../css/pictures/admin.png" alt="" style="width:40px; height:40px;" class="rounded-circle">
                                <?php else: ?>
                                    <img src="../../css/pictures/admin.png" alt="" width="50px" height="50px" class="rounded-circle">
                                <?php endif; ?>
                            </a>
                            <div class="dropdown">
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
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
                            </div>

                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Start Logout Modal -->
                <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="warningTitle"><b><i class="fas fa-fw fa-exclamation-triangle"></i> Warning Notification</b></h5>
                    </div>
                    <div class="modal-body">
                        Are you sure to log out?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn notiClose" data-dismiss="modal">No</button>
                        <a type="button" class="btn notiConfirm" href="../../database/logout_process.php">Yes</a>
                    </div>
                    </div>
                </div>
                </div>
                <!-- End Logout Modal -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <?php if (isset($alert_type) && isset($alert_message)) { ?>
                        <div class="alert alert-<?php echo $alert_type; ?>" role="alert">
                            <?php echo $alert_message; ?>
                        </div>
                        <script>
                            setTimeout(function() {
                                document.querySelector('.alert').style.display = 'none';
                            }, 15000); // Hide the alert after 15 seconds
                        </script>
                    <?php } ?>

                    <!-- Page Heading -->
                    <h3 class="h3 mb-0 text-900" id="title">
                        <strong>
                            <i class="fas fa-fw fa-users"></i>
                            Recipient
                        </strong>
                    </h3>

                    <br>

                    <div style="float: right">
                        <a class="btn submitBtn" id="updateValBtn" data-toggle="modal" data-target="#addModalCenter">
                            <i class="fas fa-fw fa-user"></i> Add Recipient
                        </a>
                    </div>

                    <!-- Start Delete All Modal -->
                    <div class="modal fade" id="deleteAllModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="warningTitle">
                                        <b><i class="fas fa-fw fa-exclamation-triangle"></i> Warning Notification!</b>
                                    </h5>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to <b>delete all recipients</b>? <br>
                                    This action <b>cannot be undone</b>!
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn notiClose" data-dismiss="modal">Cancel</button>
                                    <a type="button" class="btn notiConfirm" href="../../database/delete_all_recipient.php">Confirm</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Delete All Modal -->
                    

                    <!-- Start add Modal -->
                    <div class="modal fade" id="addModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">
                                        <i class="fas fa-fw fa-user"></i> Add Recipient
                                    </h5>
                                </div>

                                <div class="modal-body">
                                    <form id="addRecipientForm" method="POST" action="../../database/add_recipient.php"> <!-- Add action attribute here -->
                                        <div class="form-group">
                                            <label class="col-form-label">Name</label>
                                            <input class="form-control" type="text" name="name" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">Email</label>
                                            <input class="form-control" type="text" name="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="rack-position" class="col-form-label">No. Tel</label>
                                            <input class="form-control" type="text" name="no_tel" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="rfid-num" class="col-form-label">Location</label>
                                            <input class="form-control" type="text" name="location" id="location" placeholder="e.g. Lot 23, Jalan ABC, Kuala Lumpur" required>
                                        </div>

                                         <!-- Hidden fields for auto-filled coordinates -->
                                        <input type="hidden" name="longitude" id="longitude">
                                        <input type="hidden" name="latitude" id="latitude">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="closeModalBtn" class="btn notiClose" data-dismiss="modal" onclick="updateValValue()">Close</button>
                                    <button type="submit" class="btn notiConfirm">Submit</button>
                                </div>
                                    </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Add Modal -->


                    <!-- Upload QR Modal -->
                    <div class="modal fade" id="uploadQRModal" tabindex="-1" role="dialog" aria-labelledby="uploadQRModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="uploadQRModalLabel"><i class="fas fa-qrcode"></i> Upload QR Code</h5>
                                </div>
                                <div class="modal-body">
                                <form method="POST" action="../../database/upload_qr.php" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label>Recipient ID</label>
                                        <input type="number" name="recipient_id" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Upload QR Image (.png)</label>
                                        <input type="file" name="qr_file" class="form-control" accept=".png" required>
                                    </div>
                                        <button type="submit" class="btn notiConfirm">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                    <br><br>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div style="float: right">
                                <a class="btn cancelBtn" style="width: 130px; margin-right: 3px; margin-left: 3px; margin-bottom: 3px" data-toggle="modal" data-target="#deleteAllModalCenter">
                                    <i class="fas fa-solid fa-trash"></i> Delete All
                                </a>
                            </div>

                            <br><br>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="color: black;">
                                    <thead style="text-align:center">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>No.Tel</th>
                                            <th>Location</th>
                                            <th>Longitude</th>
                                            <th>Latitude</th>
                                            <th>QR Code</th>
                                            <th>Action</th>
                                        </tr>          
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $query = "SELECT r.*, q.qr_value, q.generated_at
                                                FROM recipient r
                                                LEFT JOIN qr_code q ON r.recipient_id = q.recipient_id
                                                ORDER BY r.recipient_id ASC";
                                        $result = mysqli_query($conn, $query);

                                        if (mysqli_num_rows($result) > 0){
                                            while($row = mysqli_fetch_assoc($result)) { 

                                                $recipient_id = $row["recipient_id"];

                                                // ===== Google Authenticator QR (ADMIN DOWNLOAD) =====
                                                $secretQuery = mysqli_query(
                                                    $conn,
                                                    "SELECT totp_secret 
                                                    FROM tracking_links 
                                                    WHERE recipient_id = '$recipient_id'
                                                    ORDER BY created_at DESC 
                                                    LIMIT 1"
                                                );

                                                $secretRow = mysqli_fetch_assoc($secretQuery);
                                                $totp_secret = $secretRow['totp_secret'] ?? null;

                                                $gaQrPath = null;

                                                if ($totp_secret) {
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

                                                    if (!file_exists($gaQrPath)) {
                                                        QRcode::png($otpAuthUri, $gaQrPath, QR_ECLEVEL_L, 6);
                                                    }
                                                }

                                                // Existing fields
                                                $name = $row["name"];
                                                $email = $row["email"];
                                                $no_tel = $row["no_tel"];
                                                $location = $row["location"];
                                                $longitude = $row["longitude"];
                                                $latitude = $row["latitude"];
                                        ?>
                                        <tr>
                                            <td id="text-center"><?php echo $row["recipient_id"]; ?></td>
                                            <td id="text-center"><?php echo $row["name"]; ?></td>
                                            <td id="text-center"><?php echo $row["email"]; ?></td>
                                            <td id="text-center"><?php echo $row["no_tel"]; ?></td>
                                            <td id="text-center"><?php echo $row["location"]; ?></td>
                                            <td id="text-center"><?php echo $row["longitude"]; ?></td>
                                            <td id="text-center"><?php echo $row["latitude"]; ?></td>
                                            <td style="text-align: center">
                                                <?php if ($gaQrPath && file_exists($gaQrPath)): ?>
                                                    <a href="<?php echo htmlspecialchars($gaQrPath); ?>" download
                                                        class="btn btn-success d-flex align-items-center justify-content-center shadow-sm"
                                                        style="width:180px; border-radius: 8px; font-weight: 600;">
                                                        <i class="fas fa-download"></i> Authenticator QR
                                                    </a>
                                                <?php else: ?>
                                                    <span style="color:gray;">No Authenticator</span>
                                                <?php endif; ?>
                                            </td>
                                            <!-- DELETE BUTTON COLUMN -->
                                            <td style="text-align:center">
                                                <button 
                                                    class="btn btn-danger btn-sm"
                                                    data-toggle="modal"
                                                    data-target="#deleteModalCenter<?php echo $recipient_id; ?>"
                                                    onclick="updateValueAndShowModal(<?php echo $recipient_id; ?>)">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </td>        
                                        </tr>
                                        <!-- Start Edit Modal -->
                                        <div class="modal fade" id="editModalCenter<?php echo $recipient_id;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">
                                                            <i class="fas fa-fw fa-pen"></i> Update Recipient
                                                        </h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="../../database/update_recipient.php"> <!-- Add action attribute here -->

                                                            <input type="hidden" name="recipient_id" value="<?php echo $recipient_id; ?>">

                                                            <div class="form-group">
                                                                <label class="col-form-label">Name</label>
                                                                <input class="form-control" type="text" name="name" value="<?php echo $name; ?>" required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-form-label">Email</label>
                                                                <input class="form-control" type="email" name="email" value="<?php echo $email; ?>" required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-form-label">No. Tel</label>
                                                                <input class="form-control" type="text" name="no_tel" value="<?php echo $no_tel; ?>" required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-form-label">Location</label>
                                                                <input class="form-control" type="text" name="location" id="location_<?php echo $recipient_id; ?>" value="<?php echo $location; ?>" required>
                                                            </div>

                                                            <!-- Hidden fields for updated coordinates -->
                                                            <input type="hidden" name="latitude" id="latitude_<?php echo $recipient_id; ?>" value="<?php echo $latitude; ?>">
                                                            <input type="hidden" name="longitude" id="longitude_<?php echo $recipient_id; ?>" value="<?php echo $longitude; ?>">
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" id="closeModalBtn" class="btn notiClose" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn notiConfirm" onclick="updateRecipient(<?php echo $recipient_id; ?>)">Update</button>
                                                    </div>
                                                </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Edit Modal -->
                                        <!-- Start Delete Modal -->
                                        <div class="modal fade" id="deleteModalCenter<?php echo $recipient_id;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="warningTitle"><b><i class="fas fa-fw fa-exclamation-triangle"></i> Warning Notification!</b></h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you confirm to proceed delete <b><?php echo $name; ?></b> information? <br>
                                                        This cannot be undone!
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn notiClose" data-dismiss="modal" onclick="updateCancelDelete()">Close</button>
                                                        <a type="button" class="btn notiConfirm" href="../../database/delete_recipient.php?id=<?php echo $recipient_id;?>">Confirm</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Delete Modal -->

                                        <?php
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                
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

 <script type="text/javascript">

// When the form is submitted (Add Recipient)
$('#addRecipientForm').on('submit', function(e) {
  e.preventDefault(); // stop default form submission temporarily

  const address = $('#location').val();
  if (!address.trim()) {
    alert("Please enter a location first.");
    return;
  }

  $.ajax({
    url: '../../database/geocode_proxy.php',
    data: { q: address },
    dataType: "json", // Force JSON parsing
    success: function(response) {
      console.log("Geocode response:", response);

      // Ensure response is array
      if (Array.isArray(response) && response.length > 0) {
        const lat = response[0].lat;
        const lon = response[0].lon;

        console.log("Coordinates found:", lat, lon);

        // Set hidden fields
        $('#latitude').val(lat);
        $('#longitude').val(lon);

        // Submit form
        $('#addRecipientForm')[0].submit();
      } 
      else if (response.error) {
        alert(response.error);
      } 
      else {
        alert("Could not find coordinates for the given address.");
      }
    },
    error: function(xhr, status, error) {
      console.error("Geocode proxy error:", status, error);
      alert("Error connecting to geocoding service. Try again later.");
    }
  });
});


// Update recipient
function updateRecipient(id) {
  const form = document.getElementById(`updateRecipientForm${id}`);
  const address = document.getElementById(`location_${id}`).value;

  if (!address.trim()) {
    alert("Please enter a valid location.");
    return;
  }

  // Call  PHP proxy instead of direct API
  $.ajax({
    url: '../../database/geocode_proxy.php', //use  proxy
    data: { q: address },
    success: function(response) {
  let data = response;

  // if jQuery didn’t parse JSON automatically
  if (typeof response === "string") {
    try {
      data = JSON.parse(response);
    } catch (e) {
      alert("Invalid response from server.");
      console.error("Parse error:", e, response);
      return;
    }
  }

  if (Array.isArray(data) && data.length > 0) {
    const lat = data[0].lat;
    const lon = data[0].lon;

    $('#latitude').val(lat);
    $('#longitude').val(lon);

    $('#addRecipientForm')[0].submit();
  } else if (data.error) {
    alert(data.error);
  } else {
    alert("Could not find coordinates for the given address.");
  }
}

  });
}

</script>

<script src="../../javascript.js"></script>


</body>

</html>