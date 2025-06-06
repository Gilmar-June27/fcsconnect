<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:landing-page.php');
    exit;
}

// Message variable to display feedback
$message = [];

// Fetch all completed orders grouped by date
// $completed_orders_query = mysqli_query($conn, "
//     SELECT DISTINCT `placed_on`
//     FROM `orders`
//     WHERE `user_id` = '$user_id' 
//     AND `payment_status` = 'completed'
//     ORDER BY STR_TO_DATE(`placed_on`, '%d-%b-%Y') DESC
// ") or die('Query failed');


$current_date = date('d-M-Y');

// Fetch all completed orders for the user
$orders_query = mysqli_query($conn, "
    SELECT * FROM `orders`
    WHERE `user_id` = '$user_id'
    AND `payment_status` = 'completed'
    ORDER BY STR_TO_DATE(`placed_on`, '%d-%b-%Y') DESC
") or die('Query failed');

// Fetch all pending orders
$pending_orders_query = mysqli_query($conn, "
    SELECT * 
    FROM `orders`
    WHERE `user_id` = '$user_id'
    AND `payment_status` = 'pending'
    ORDER BY `placed_on` DESC
") or die('Query failed');

// Handle order cancellation
if (isset($_POST['cancel_order'])) {
    $order_id = $_POST['order_id'];
    $cancel_query = mysqli_query($conn, "
        UPDATE `orders`
        SET `payment_status` = 'canceled'
        WHERE `id` = '$order_id' AND `user_id` = '$user_id'
    ") or die('Cancellation failed');

    if ($cancel_query) {
        $message[] = "Order canceled successfully."; // Store message for display
    } else {
        $message[] = "Failed to cancel order."; // Store error message
    }
}

@include("header.php");
@include("navbar.php");
?>

<div class="container mt-5">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <p><span>My Or</span><span class="description-title">ders</span></p>
    </div>

    <div class="container">
        <h3>My Completed Orders</h3>

        <!-- Completed Orders Section with Parent Card for Each Date -->
        <?php
        if (mysqli_num_rows($orders_query) > 0){
            while ($order = mysqli_fetch_assoc($orders_query)) {
                $order_id = htmlspecialchars($order['id']);
                $name = htmlspecialchars($order['name']);
                $total_products = htmlspecialchars($order['total_products']);
                $total_price = htmlspecialchars($order['total_price']);
                $placed_on = htmlspecialchars($order['placed_on']);
                
                // Ensure 'product_id' exists in the $order array, else set it to a default value
                $product_id = isset($order['product_id']) ? htmlspecialchars($order['product_id']) : '';

                // Fetch the stored rating for this order
                $rating_query = mysqli_query($conn, "SELECT rating FROM ratings WHERE order_id = '$order_id' AND user_id = '$user_id'");
                $rating = mysqli_fetch_assoc($rating_query);
                $rating_value = $rating ? $rating['rating'] : 0; // default to 0 if no rating

                // Fetch previous comments for this order
                $comments_query = mysqli_query($conn, "SELECT * FROM order_comments WHERE order_id = '$order_id'");
                $comments = mysqli_fetch_all($comments_query, MYSQLI_ASSOC);
                // Check if the order was placed today
                if ($order['placed_on'] == $current_date){?>
                    <!-- Today's Orders -->
                    <!-- <h4>Completed Orders for Today (<?php echo $current_date; ?>)</h4> -->
                    <div class="col-md-3 mb-4 mt-4">
                        <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Order ID: <?php echo $order_id; ?></h5>
                            <p><strong>Total Products:</strong> <?php echo $total_products; ?></p>
                            <p><strong>Total Price:</strong> ₱<?php echo $total_price; ?></p>
                            <p><strong>Placed On:</strong> <?php echo $placed_on; ?></p>

                            <!-- Rating Stars -->
                            <div class="stars" data-order-id="<?php echo $order_id; ?>" data-product-id="<?php echo $product_id; ?>">
                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                    <span data-star="<?php echo $i; ?>" class="star <?php echo ($i <= $rating_value) ? 'yellow' : ''; ?>">&#9733;</span>
                                <?php } ?>
                            </div>

                            <!-- Comment Section -->
                            <textarea id="comment-<?php echo $order_id; ?>" class="form-control" placeholder="Leave a comment..." rows="4"></textarea>
                            <button class="btn btn-primary add-comment-btn mt-2" data-order-id="<?php echo $order_id; ?>">Add Comment</button>

                            <!-- Button to view previous comments -->
                            <button class="btn btn-info view-comments-btn mt-2" data-bs-toggle="modal" data-bs-target="#commentModal" data-order-id="<?php echo $order_id; ?>">View Comments</button>
                        </div>
                        </div>
                    </div>
                <?php } else {?>
                    <!-- Past Orders -->
                    <!-- <h4 class="mt-5">Past Completed Orders</h4> -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($order['placed_on']); ?></h5>
                            <a href="orders-on-date.php?date=<?php echo urlencode($order['placed_on']); ?>" class="btn btn-outline-primary">View Orders on <?php echo htmlspecialchars($order['placed_on']); ?></a>
                        </div>
                    </div>
                <?php } ?>
            <?php }
       } else{ ?>
            <p class="btn btn-outline-danger m-auto">No completed orders found.</p>
        <?php } ?>



        <div id="commentModal" class="modal fade" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel">Your Comments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="commentContent">
                <!-- Comments will be dynamically loaded here -->
            </div>
        </div>
    </div>
</div>

        <h3 class="mt-5">My Pending Orders</h3>

        <!-- Pending Orders Section -->
        <div class="container mt-5">
            <?php
            if (mysqli_num_rows($pending_orders_query) > 0) {
                // Step 1: Organize orders by admin_id
                $orders_by_admin = [];
                while ($order_row = mysqli_fetch_assoc($pending_orders_query)) {
                    $admin_id = $order_row['admin_id'];
                    $orders_by_admin[$admin_id][] = $order_row;
                }

                // Step 2: Loop through each admin_id group
                foreach ($orders_by_admin as $admin_id => $orders) {
                    // Retrieve the shipping fee for this admin's orders (assuming it's consistent across orders)
                    $shipping_fee = htmlspecialchars($orders[0]['shipping_fee']);

                    // Calculate the total cost for this group (sum of order prices + shipping fee)
                    $total_group_price = 0;
                    foreach ($orders as $order) {
                        $total_group_price += $order['total_price'];
                    }
                    $total_with_shipping = $total_group_price + $shipping_fee;
                    ?>
                    
                    <!-- Parent Card with Shipping Fee and Total for each Admin ID -->
                    <div class="card mt-4 mb-4">
                        <div class="card-header">
                            <h4>Shipping Fee: ₱<b><?php echo number_format($shipping_fee, 2); ?></b></h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php
                                // Step 3: Loop through each order in this admin_id group
                                foreach ($orders as $order) {
                                    $order_id = htmlspecialchars($order['id']);
                                    $total_products = htmlspecialchars($order['total_products']);
                                    $total_price = htmlspecialchars($order['total_price']);
                                    $placed_on = htmlspecialchars($order['placed_on']);
                                    $image = htmlspecialchars($order['image']);
                                ?>
                                <!-- Child Card for each Order -->
                                <div class="col-md-3 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">Order ID: <?php echo $order_id; ?></h5>
                                            <p><strong>Products:</strong> <?php echo $total_products; ?></p>
                                            <p><strong>Total Price:</strong> ₱<?php echo number_format($total_price, 2); ?></p>
                                            <p><strong>Placed On:</strong> <?php echo $placed_on; ?></p>
                                            <?php if ($image) { ?>
                                                <img src="images/<?php echo $image; ?>" alt="Product Image" style="width: 100%; height: auto;">
                                            <?php } ?>
                                            <form method="post" style="display:inline;">
                                                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                                                <button type="submit" name="cancel_order" class="btn btn-outline-danger mt-3">
                                                    cancel
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                            </div><!-- End Row for Child Cards -->
                        </div><!-- End Card Body for Parent Card -->
                    </div><!-- End Parent Card -->
                    <?php
                }
            } else {
                echo '<p class="btn btn-outline-danger m-auto mb-3">No pending orders found.</p>';
            }
            ?>
        </div>
    </div>

    <!-- Message Display -->
    <?php if (!empty($message)): ?>
        <div class="message-box" style="background: #efdece; color: green;">
            <?php foreach ($message as $msg): ?>
                <span><?php echo htmlspecialchars($msg); ?></span>
            <?php endforeach; ?>
            <i class="fas fa-times close-btn" style="cursor: pointer;"></i>
        </div>
    <?php endif; ?>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stars = document.querySelectorAll('.stars span');

        // Add pointer cursor on hover
        stars.forEach(star => {
            star.style.cursor = 'pointer';

            star.addEventListener('click', function () {
                const rating = this.getAttribute('data-star');
                const orderId = this.parentElement.getAttribute('data-order-id');
                const productId = this.parentElement.getAttribute('data-product-id');
                
                // Change color to yellow
                const allStars = this.parentElement.querySelectorAll('span');
                allStars.forEach(star => {
                    if (star.getAttribute('data-star') <= rating) {
                        star.classList.add('yellow');
                    } else {
                        star.classList.remove('yellow');
                    }
                });

                // Send the rating to the server via AJAX
                fetch('submit_rating.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'order_id': orderId,
                        'product_id': productId,
                        'rating': rating
                    })
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data); // Handle response
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });

   // Add comment functionality
const addCommentBtns = document.querySelectorAll('.add-comment-btn');

addCommentBtns.forEach(btn => {
    btn.addEventListener('click', function () {
        const orderId = this.getAttribute('data-order-id');
        const comment = document.getElementById('comment-' + orderId).value;

        // Send the comment to the server via AJAX
        fetch('submit_comment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'order_id': orderId,
                'comment': comment
            })
        })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Handle response
            
            // Clear the comment input field after successful submission
            document.getElementById('comment-' + orderId).value = '';
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});


        // View comments functionality
        const viewCommentsBtns = document.querySelectorAll('.view-comments-btn');
        
        viewCommentsBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const orderId = this.getAttribute('data-order-id');
                
                // Fetch previous comments for this order
                fetch('get_comments.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'order_id': orderId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Clear existing comments
                    const commentContent = document.getElementById('commentContent');
                    commentContent.innerHTML = '';

                    // Add each comment to the modal
                    data.forEach(comment => {
                        const commentElement = document.createElement('div');
                        commentElement.classList.add('comment');
                        commentElement.classList.add('mb-3');
                        commentElement.innerHTML = `
                            <div class="comment-box p-3" style="border: 1px solid #ddd; border-radius: 5px;">
                                <strong>${comment.user_id}</strong> <small>(${comment.created_at})</small>
                                <p>${comment.comment}</p>
                            </div>
                        `;
                        commentContent.appendChild(commentElement);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    });
</script>



<style>
    .star {
        font-size: 30px;
        color: gray;
        cursor: pointer;
    }

    .yellow {
        color: gold;
    }

    .comment-box {
        background-color: #f9f9f9;
        border-radius: 5px;
    }

    .comment {
        margin-bottom: 10px;
    }
</style>

<?php @include("footer.php"); ?>
