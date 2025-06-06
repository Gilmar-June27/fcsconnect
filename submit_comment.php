<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    echo "User not logged in.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    // Insert comment into the database
    $insert_comment = "INSERT INTO order_comments (order_id, user_id, comment) VALUES ('$order_id', '$user_id', '$comment')";
    mysqli_query($conn, $insert_comment);

    echo "Comment added successfully!";
} else {
    echo "Invalid request.";
}
?>
