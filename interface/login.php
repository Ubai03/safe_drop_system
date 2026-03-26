<!DOCTYPE html>
<html lang="en">
<?php

    if (isset($_GET['error']) && $_GET['error'] == 'wrongPsw') {
        $alert_type = 'danger';
        $alert_message = "Wrong password!.";
    } elseif (isset($_GET['error']) && $_GET['error'] == 'wrongUsername') {
        $alert_type = 'danger';
        $alert_message = "Wrong username!.";
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
    <link href="../css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../css/login_interface.css" rel="stylesheet">

    <!-- To prevent back -->
    <script type="text/javascript">
        function preventBack()
        {
            window.history.forward()
        };

        setTimeout("preventBack()",0);

        window.onunload=function()
        {
            null;
        }
    </script>
    <!-- End to prevent back -->

    <style>
        .form-control {
            border-radius: 30px;
            font-size: .8rem;
            height: 50px;
        }
        .h4 {
            margin-bottom: 10px !important;
        }
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

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block">
                                <img src="../css/pictures/side_wallpaper9.png" style="width: 110%; height: 100%" alt="Image">
                            </div>
                            <div class="col-lg-6" id="login">
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
                                        <img class="" src="../css/pictures/safeDrop_logo.png" style="height: 160px; width: 180px; margin-bottom: 20px">
                                        
                                        <h5 class="text-center mb-4" style="color: white; font-weight: bold;">
                                             "SMART IOT SECURE PARCEL DELIVERY SYSTEM"
                                        </h5>

                                        <h6 class="h5 text-900 mb-4" id="titleLogin"><b>WELCOME BACK!</b></h6>
                                    </div>
                                    <form class="user" id="loginForm" method="post" action="../database/login_process.php">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="username" placeholder="Username" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                                        </div>
                                        <div class="captcha-wrapper">
                                            <div class="h-captcha" data-sitekey="32b6e35a-dcb6-4624-bdda-10beb12c29d4"></div>
                                            <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
                                        </div>
                                        <button type="submit" class="btn btn-user btn-block" id="loginButton">
                                            LOGIN
                                        </button>
                                        <hr style="background-color: white">
                                    </form>
                                    <div class="text-center">
                                        <a class="small" href="register.php" id="linkSignUp">
                                            Don't have an account? <br> Register Now!
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../jQuery/jquery.min.js"></script>
    <script src="../jQuery/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../jQuery/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../jQuery/sb-admin-2.min.js"></script>

</body>

</html>