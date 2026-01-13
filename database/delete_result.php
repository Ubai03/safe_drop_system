<?php
include("to_connect.php");

if (isset($_GET['id'])) {
    $result_id = intval($_GET['id']); // sanitize input

    $query = "DELETE FROM tbl_result WHERE result_id = $result_id";
    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Record deleted successfully!');
                window.location.href='../interface/admin/index.php';
              </script>";
    } else {
        echo "<script>
                alert('Error deleting record.');
                window.location.href='../interface/admin/index.php';
              </script>";
    }
} else {
    header("Location: ../interface/admin/index.php");
}
?>
