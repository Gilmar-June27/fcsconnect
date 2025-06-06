<?php
include 'config.php';

if (isset($_POST['query'])) {
    $query = mysqli_real_escape_string($conn, $_POST['query']);
    $query = htmlspecialchars($query);

    // Fetch products and users
    $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%$query%'") or die('query failed');
    $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE name LIKE '%$query%' OR username LIKE '%$query%'") or die('query failed');

    $suggestions = [];

    // Collect product suggestions
    while ($product = mysqli_fetch_assoc($select_products)) {
        $suggestions[] = [
            'type' => 'product',
            'name' => $product['name'],
            'image' => 'images/' . $product['image']
        ];
    }

    // Collect user suggestions
    while ($user = mysqli_fetch_assoc($select_users)) {
        $suggestions[] = [
            'type' => 'user',
            'name' => $user['name'],
            'image' => 'images/' . $user['image']
        ];
    }

    // Return suggestions as JSON
    echo json_encode($suggestions);
}
?>
