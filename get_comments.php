<?php
include 'config.php';
session_start();

$order_id = $_POST['order_id'] ?? null;
if (!$order_id) {
    echo json_encode([]);
    exit;
}

// Fetch comments for the order
$query = mysqli_query($conn, "SELECT * FROM order_comments WHERE order_id = '$order_id' ORDER BY created_at DESC");
$comments = mysqli_fetch_all($query, MYSQLI_ASSOC);

echo json_encode($comments);
?>
