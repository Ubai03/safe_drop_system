<?php
    session_start();

    include("to_connect.php");

    extract($_POST);

    $query_obtain_userPsw = "SELECT password FROM user WHERE user_id = '$adminID'";
    $result_obtain_userPsw = mysqli_query($conn, $query_obtain_userPsw);
    if (!$result_obtain_userPsw) {
        die("Query execution failed: " . mysqli_error($conn));
    }
    $row = mysqli_fetch_assoc($result_obtain_userPsw);
    $dbOldPsw = $row["password"];

    if (password_verify($oldPsw, $dbOldPsw )) {

        if ($newPsw != $repeatPsw) {
            header("Location: ../interface/admin/profile.php?error=invalidPswConfirm");
        } else {

            $encryptedPsw = password_hash($newPsw, PASSWORD_DEFAULT);

            $query= "UPDATE user SET name='$newName', username='$newUsername', password='$encryptedPsw' WHERE user_id = '$adminID'";

            $result = mysqli_query ( $conn, $query );

                if ( $result ) 
                {
                    header("Location: ../interface/admin/profile.php?success=true");
                }
                else 
                {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
        }
        
    } else {
        header("Location: ../interface/admin/profile.php?error=wrongOldPsw");
    }
?>