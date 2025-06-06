<?php
include '../config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Check if the breakdown belongs to the logged-in admin
    $check_query = mysqli_query($conn, "SELECT * FROM `breakdowns` WHERE id = '$id' AND admin_id = '$admin_id'") or die('query failed');
    if (mysqli_num_rows($check_query) > 0) {
        $delete_query = mysqli_query($conn, "DELETE FROM `breakdowns` WHERE id = '$id'") or die('query failed');
        if ($delete_query) {
            $message[] = 'Breakdown deleted successfully!';
        } else {
            $message[] = 'Breakdown could not be deleted!';
        }
    } else {
        $message[] = 'Invalid request!';
    }
}

header('location: admin_page.php');
exit();
?>
