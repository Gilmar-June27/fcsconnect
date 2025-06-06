<?php
include 'config.php';
session_start();

// Check if the user is logged in
$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    echo "User not logged in.";
    exit;
}

// Check if the form data is received via AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the posted data
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    
    // Ensure rating is between 1 and 5
    if ($rating < 1 || $rating > 5) {
        die("Invalid rating value.");
    }

    // Check if the user has already rated this order
    $existing_rating_query = "SELECT id FROM ratings WHERE order_id = '$order_id' AND user_id = '$user_id'";
    $existing_rating_result = mysqli_query($conn, $existing_rating_query);
    
    if (mysqli_num_rows($existing_rating_result) > 0) {
        // Update the existing rating
        $update_rating_query = "UPDATE ratings SET rating = '$rating' WHERE order_id = '$order_id' AND user_id = '$user_id'";
        if (mysqli_query($conn, $update_rating_query)) {
            echo "Rating updated successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // Insert a new rating if not already rated
        $insert_rating_query = "INSERT INTO ratings (user_id, order_id, product_id, rating, comment)
                                VALUES ('$user_id', '$order_id', '$product_id', '$rating', '')";
        if (mysqli_query($conn, $insert_rating_query)) {
            echo "Rating submitted successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
} else {
    // If not a POST request, send error message
    echo "Invalid request.";
}
?>
