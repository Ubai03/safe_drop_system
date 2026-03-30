<!DOCTYPE html>
<!-- session start -->
<!--<?php
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

    date_default_timezone_set("Asia/Kuala_Lumpur");
	$current_date = date('d-m-Y');

    /*if(!isset($_SESSION['userID'])) {
        header("Location: ../login.php");
    }*/
?>-->
<!-- end session start -->
<html lang="en">
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
    <!--<script src="../../jQuery/datatables-demo.js"></script>-->
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
<script>
    $(document).ready(function(){
        $('.editBtn').on('click', function(){
            var id = $(this).data('id');
            var name = $(this).data('name');
            var phone = $(this).data('phone');
            var vehicle = $(this).data('vehicle');
            var plate = $(this).data('plate');
            var status = $(this).data('status');

            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_phone').val(phone);
            $('#edit_vehicle').val(vehicle);
            $('#edit_plate').val(plate);
            $('#edit_status').val(status);
        });
    });
</script>
<body>
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
                <a class="nav-link" href="courier.php" id="sidebar" style="background-color: #854643; font-weight: bold;">
                    <i class="fas fa-fw fa-user" style="color: white;"></i>
                    <span>Courier</span></a>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="recipient.php" id="sidebar">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Recipient</span>
                </a>
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
                <!-- Add Courier Modal -->
                <div class="modal fade" id="addModalCenter" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">

                            <form action="../../database/add_courier.php" method="POST">

                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-user-plus"></i> Add Courier
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <div class="modal-body">

                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" name="username" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" name="phone" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Vehicle Type</label>
                                        <select name="vehicle_type" class="form-control" required>
                                            <option value="">-- Select --</option>
                                            <option value="Motorbike">Motorbike</option>
                                            <option value="Car">Car</option>
                                            <option value="Van">Van</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Plate Number</label>
                                        <input type="text" name="vehicle_plate" class="form-control" required>
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn notiClose" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn notiConfirm">Add</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <!-- Edit Courier Modal -->
                <div class="modal fade" id="editCourierModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="../../database/update_courier.php" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                    <i class="fas fa-user-edit"></i> Edit Courier
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="user_id" id="edit_id">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" id="edit_name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" name="phone" id="edit_phone" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Vehicle Type</label>
                                        <select name="vehicle_type" id="edit_vehicle" class="form-control">
                                        <option value="Motorbike">Motorbike</option>
                                        <option value="Car">Car</option>
                                        <option value="Van">Van</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Plate Number</label>
                                        <input type="text" name="vehicle_plate" id="edit_plate" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" id="edit_status" class="form-control">
                                            <option value="available">Available</option>
                                            <option value="busy">Busy</option>
                                            <option value="offline">Offline</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn notiClose" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn notiConfirm">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <?php if(isset($_GET['success'])): ?>
                        <div class="alert alert-success">
                            Courier added successfully!
                        </div>
                    <?php endif; ?>
                    <?php if(isset($_GET['delete'])): ?>
                        <div class="alert alert-success">
                            Courier deleted successfully!
                        </div>
                    <?php endif; ?>
                    <?php if(isset($_GET['update'])): ?>
                        <div class="alert alert-success">
                            Courier updated successfully!
                        </div>
                    <?php endif; ?>
                    <!-- Page Heading -->
                    <h3 class="h3 mb-0 text-900" id="title">
                        <strong>
                            <i class="fas fa-fw fa-users"></i>
                            Courier
                        </strong>
                    </h3>
                    <br>
                    <div style="float: right">
                        <a class="btn submitBtn" id="updateValBtn" data-toggle="modal" data-target="#addModalCenter">
                            <i class="fas fa-fw fa-user"></i> Add Courier
                        </a>
                    </div>
                    <br><br>
                    <!-- Card Table -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="color: black;">
                                <thead style="text-align:center">
                                    <tr>
                                        <th>Courier Id</th>
                                        <th>Name</th>
                                        <th>Phone No.</th>
                                        <th>Vehicle</th>
                                        <th>No. Plate</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>          
                                </thead>

                                <tbody>
                                    <?php
                                    include("../../database/to_connect.php");

                                    $query = "SELECT * FROM user WHERE role='courier'";
                                    $result = mysqli_query($conn, $query);

                                    while($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr style="text-align:center">
                                        <td><?php echo $row['user_id']; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['phone']; ?></td>
                                        <td><?php echo $row['vehicle_type']; ?></td>
                                        <td><?php echo $row['vehicle_plate']; ?></td>
                                        <td>
                                            <?php
                                                if($row['status'] == 'available'){
                                                    echo "<span class='badge badge-success'>Available</span>";
                                                } elseif($row['status'] == 'busy'){
                                                    echo "<span class='badge badge-warning'>Busy</span>";
                                                } else {
                                                    echo "<span class='badge badge-secondary'>Offline</span>";
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <button 
                                                class="btn btn-sm btn-primary editBtn"
                                                data-id="<?php echo $row['user_id']; ?>"
                                                data-name="<?php echo $row['name']; ?>"
                                                data-phone="<?php echo $row['phone']; ?>"
                                                data-vehicle="<?php echo $row['vehicle_type']; ?>"
                                                data-plate="<?php echo $row['vehicle_plate']; ?>"
                                                data-status="<?php echo $row['status']; ?>"
                                                data-toggle="modal"
                                                data-target="#editCourierModal">
                                                Edit
                                            </button>
                                            <a href="../../database/delete_courier.php?id=<?php echo $row['user_id']; ?>" 
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this courier?')">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- End Card Table -->
                </div>
                <!-- End Page Content -->
            </div>                            
            <!-- End Main Content -->
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
<script src="../../javascript.js"></script>
<script>
$(document).ready(function() {
    $('#dataTable').DataTable();
});
</script>
</body>
</html>