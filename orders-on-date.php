<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:landing-page.php');
    exit;
}

$selected_date = isset($_GET['date']) ? $_GET['date'] : '';

if (empty($selected_date)) {
    die('Invalid date.');
}

$orders_query = mysqli_query($conn, "
    SELECT * FROM `orders`
    WHERE `user_id` = '$user_id'
    AND `payment_status` = 'completed'
    AND `placed_on` = '$selected_date'
    ORDER BY STR_TO_DATE(`placed_on`, '%d-%b-%Y') DESC
") or die('Query failed');

?>

<?php @include("header.php"); ?>
<?php @include("navbar.php"); ?>

<div class="container mt-5">
    <div class="container section-title" data-aos="fade-up">
        <p><span>My Or</span><span class="description-title">ders</span></p>
    </div><!-- End Section Title -->

    <h3>Orders on <?php echo htmlspecialchars($selected_date); ?></h3>
    <a href="my_orders.php" class="btn btn-outline-secondary mb-3">Back to Orders List</a>

    <div class="row">
        <?php
        if (mysqli_num_rows($orders_query) > 0) {
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
        ?>
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
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
        <?php
            }
        } else {
            echo '<p class="btn btn-outline-danger m-auto">No orders found for this date.</p>';
        }
        ?>
    </div>
</div>

<!-- Modal for viewing the comment -->
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
