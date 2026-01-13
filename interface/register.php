<!DOCTYPE html>
<html lang="en">
<?php
    if (isset($_GET['success']) && $_GET['success'] == 'true') {
        $alert_type = 'success';
        $alert_message = "Registration successful. Please continue to login.";
    } elseif (isset($_GET['error']) && $_GET['error'] == 'invalidPswConfirm') {
        $alert_type = 'danger';
        $alert_message = "Password and confirm password do not match! Registration failure. Please try again.";
    }
?>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SAFE DROP SYSTEM</title>

    <!-- Icon tab -->
    <link rel="icon" href="../css/pictures/safeDrop_logo.png">

    <!-- Custom fonts for this template-->
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../css/login_interface.css" rel="stylesheet">

    <style>
        #frontWallpaper {
            background-image: url(../css/pictures/background16.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }
    </style>

</head>

<body class="bg-gradient" id="frontWallpaper">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5" style="background-color: #051d40">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block">
                        <img src="../css/pictures/image15.png" style="width: 120%; height: 100%" alt="Image">
                    </div>
                    <div class="col-lg-7" id="login">
                        <div class="p-5">

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

                            <div class="text-center">
                                <img class="" src="../css/pictures/safeDrop_logo.png" style="height: 180px; width: 180px; margin-bottom: 10px">

                                <h5 class="h5 text-900 mb-4" id="titleLogin"><b>Create an Account!</b></h5>
                            </div>
                            <form class="user" id="loginForm" method="POST" action="../database/register_admin.php">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" name="name" placeholder="Full Name" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" name="username" id="username" placeholder="Username" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" name="psw" id="psw" placeholder="Password" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" name="pswRepeat" id="pswRepeat" placeholder="Confirm Password" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-user btn-block" id="loginButton">
                                    REGISTER ACCOUNT
                                </button>
                                <hr style="background-color: white">
                            </form>
                            <div class="text-center">
                                <a class="small" href="login.php" id="linkSignUp">
                                    Already have an account? <br> Login Now!
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../jquery/jquery.min.js"></script>
    <script src="../jquery/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../jquery/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../jquery/sb-admin-2.min.js"></script>

</body>

</html>