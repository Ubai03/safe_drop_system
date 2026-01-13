<!DOCTYPE html>

<!-- session start -->
<?php
    session_start();

    include("../../database/to_connect.php"); 

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
    $username = $row["username"];

    if (isset($_GET['success']) && $_GET['success'] == 'true') {
        $alert_type = 'success';
        $alert_message = "Successfully update the admin profile.";
    } elseif (isset($_GET['error']) && $_GET['error'] == 'invalidPswConfirm') {
        $alert_type = 'danger';
        $alert_message = "New Password and confirm password does not match!. Update profile failure. Please try again.";
    } elseif (isset($_GET['error']) && $_GET['error'] == 'wrongOldPsw') {
        $alert_type = 'danger';
        $alert_message = "Insert the wrong old password!. Update profile failure. Please try again.";
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

    <style>
        #text-center{
            text-align: center;
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
            <li class="nav-item">
                <a class="nav-link" href="index.php" id="sidebar">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Dashboard</span></a>
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
                            <i class="fas fa-fw fa-user-circle"></i>
                            Profile
                        </strong>
                    </h3>

                    <br>

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="formTable" width="100%" cellspacing="0">
                                    <form method="POST" action="../../database/update_profile_admin.php">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <center><img src="../../css/pictures/admin.png" width="150px" height="150px"></center>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Full Name
                                                    <input class="form-control" type="text" name="newName" value="<?php echo $nameAdmin ?>" required>
                                                    <input class="form-control" type="hidden" name="adminID" value="<?php echo $adminID; ?>" required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Username
                                                    <input class="form-control" type="text" name="newUsername" value="<?php echo $username ?>" required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Old Password
                                                    <input class="form-control" type="password" name="oldPsw" required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    New Password
                                                    <input class="form-control" type="password" name="newPsw" required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Confirm Password
                                                    <input class="form-control" type="password" name="repeatPsw" required>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="row justify-content-center">
                                                        <a href="index.php" class="btn cancelBtn" style="width: 100px; margin-right: 3px; margin-left: 3px; margin-bottom: 3px">
                                                            Back
                                                        </a>

                                                        <button class="btn submitBtn" style="width: 100px; margin-right: 3px; margin-left: 3px; margin-bottom: 3px">
                                                            Update
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </form>
                                </table>
                            </div>
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
                        <span>Copyright &copy; FastTrack Iot SYSTEM</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <script type="text/javascript">
        $('#dataTable').DataTable({
            "pagingType": "simple",
            "ordering": false
        });
    </script>

</body>

</html>