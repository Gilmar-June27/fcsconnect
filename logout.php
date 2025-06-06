<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'] ?? $_SESSION['admin_id'] ?? $_SESSION['super_admin_id'] ?? null;

if($user_id){
   $update_query = "UPDATE `users` SET status = 'deactivate' WHERE id = '$user_id'";
   mysqli_query($conn, $update_query) or die('query failed');
}

session_unset();
session_destroy();

header('location:login.php');
?>
