<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:landing-page.php');
    exit;
}
// Get the POST data from AJAX request
$method = $_POST['method'];  // 'cod' or 'gcash'
$user_id = $_POST['user_id'];

// Calculate the cart total (sum of all products in the cart)
$cart_total_query = mysqli_query($conn, "SELECT SUM(p.price) AS total FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = '$user_id'");
if (!$cart_total_query) {
    die('Cart total query failed: ' . mysqli_error($conn));
}

$cart_total_row = mysqli_fetch_assoc($cart_total_query);
$cart_total = $cart_total_row['total'] ?? 0; // If no products, set cart total to 0

// If the payment method is COD, fetch the shipping fee
$shipping_fee = 0;
if ($method === 'cod') {
    // Fetch distinct admin IDs from the cart
    $admin_ids_query = mysqli_query($conn, "SELECT DISTINCT admin_id FROM `cart` WHERE user_id = '$user_id'");
    if (!$admin_ids_query) {
        die('Admin IDs query failed: ' . mysqli_error($conn));
    }

    // Calculate the shipping fee for each admin
    while ($admin_id_row = mysqli_fetch_assoc($admin_ids_query)) {
        $admin_id = $admin_id_row['admin_id'];

        // Now query the shipping fee for each admin_id
        $shipping_query = mysqli_query($conn, "SELECT price FROM `shipping_fee` WHERE admin_id = '$admin_id'");
        if (!$shipping_query) {
            die('Shipping query failed: ' . mysqli_error($conn));
        }

        while ($shipping_row = mysqli_fetch_assoc($shipping_query)) {
            $shipping_fee += (float)$shipping_row['price']; // Add the shipping fee to the total
        }
    }
}

// Return the shipping fee and cart total as JSON
echo json_encode([
    'shipping_fee' => $shipping_fee,
    'cart_total' => $cart_total
]);

?>
