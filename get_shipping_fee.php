<?php
include 'config.php';
session_start();

// Check if user is logged in
$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('Location: landing-page.php');
    exit;
}// Assuming user_id is stored in session

// Fetch distinct admin IDs from the cart
$admin_ids_query = mysqli_query($conn, "SELECT DISTINCT admin_id FROM `cart` WHERE user_id = '$user_id'");
if (!$admin_ids_query) {
    die('Admin IDs query failed: ' . mysqli_error($conn));
}

$shipping_total = 0;

// Calculate total shipping fee based on the cart items
while ($admin_id_row = mysqli_fetch_assoc($admin_ids_query)) {
    $admin_id = $admin_id_row['admin_id'];

    // Fetch the shipping fee for each admin
    $shipping_query = mysqli_query($conn, "SELECT price FROM `shipping_fee` WHERE admin_id = '$admin_id'");
    if (!$shipping_query) {
        die('Shipping query failed: ' . mysqli_error($conn));
    }

    while ($shipping_row = mysqli_fetch_assoc($shipping_query)) {
        $shipping_total += (float)$shipping_row['price']; // Add the shipping fee to the total
    }
}

// Return the calculated shipping fee as the response
echo number_format($shipping_total, 2);
?>
