<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    $user_id = $_POST['user_id'];
    $rating = $_POST['rating'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    // Insert the review into the database
    $review_query = "INSERT INTO `product_reviews` (`order_id`, `product_id`, `user_id`, `rating`, `comment`)
                     VALUES ('$order_id', '$product_id', '$user_id', '$rating', '$comment')";

    if (mysqli_query($conn, $review_query)) {
        echo "Review submitted successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
