<?php
    session_start();

    session_destroy();

    header("location: ../interface/login.php");

?>