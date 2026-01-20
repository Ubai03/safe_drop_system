<?php

//Connect to database
$conn = mysqli_connect("localhost", "root", "", "safe_drop_test");

//if connection failed then display this error
if (mysqli_connect_errno())
{
    echo "Failed to connect to MYSQL: ". mysqli_connect_error();
}

?>