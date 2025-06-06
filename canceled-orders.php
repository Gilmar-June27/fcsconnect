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

// Handle deletion of an order
if (isset($_POST['delete_order_id'])) {
    $delete_order_id = $_POST['delete_order_id'];

    // Delete the order from the database
    $delete_query = mysqli_query($conn, "
        DELETE FROM `orders`
        WHERE `id` = '$delete_order_id' 
        AND `user_id` = '$user_id'
        AND `payment_status` = 'canceled'
    ");

    if ($delete_query) {
        $message[] = "Order deleted successfully.";
    } else {
        $message[] = "Failed to delete order.";
    }
}

// Query for canceled orders
$canceled_orders_query = mysqli_query($conn, "
    SELECT *
    FROM `orders`
    WHERE `user_id` = '$user_id'
    AND `payment_status` = 'canceled'
    ORDER BY `placed_on` DESC
") or die('Query failed');

@include("header.php");
@include("navbar.php");
?>

<div class="container mt-5">
    <div class="container section-title" data-aos="fade-up">

    <!---duplicate description for canceled---->
        <p><span>My Can</span><span class="description-title">celed Orders</span></p>
    </div>

    <div class="container">
        <h3>My Canceled Orders</h3>

        <!-- Message Display -->
        <?php if (!empty($message)): ?>
            <div class="message-box" style="background: #efdece; color: green;text-align:center;margin:0 auto;display:flex;justify-content:center;">
                <?php foreach ($message as $msg): ?>
                    <span><?php echo htmlspecialchars($msg); ?></span>
                <?php endforeach; ?>
                <i class="fas fa-times close-btn" style="cursor: pointer;"></i>
            </div>
        <?php endif; ?>
       <!----display all canceled product----->
        <div class="row">
        <?php
        if (mysqli_num_rows($canceled_orders_query) > 0) {
            while ($order_row = mysqli_fetch_assoc($canceled_orders_query)) {
                $order_id = htmlspecialchars($order_row['id']);
                $total_products = htmlspecialchars($order_row['total_products']);
                $total_price = htmlspecialchars($order_row['total_price']);
                $placed_on = htmlspecialchars($order_row['placed_on']);
                $image = htmlspecialchars($order_row['image']);
        ?>
        <!-----this card shows the description of the product----->
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Order ID: <?php echo $order_id; ?></h5>
                    <p><strong>Products:</strong> <?php echo $total_products; ?></p>
                    <p><strong>Total Price:</strong> $<?php echo number_format($total_price, 2); ?></p>
                    <p><strong>Placed On:</strong> <?php echo $placed_on; ?></p>
                    <?php if ($image): ?>
                        <img src="<?php echo $image; ?>" alt="Product Image" style="width: 100%; height: auto;">
                    <?php endif; ?>
                    <!-- Delete Form -->
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="delete_order_id" value="<?php echo $order_id; ?>">
                        <button type="submit" class="btn btn-outline-danger mt-3">
                            <i class="fas fa-trash"></i> 
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php
            }
        } else {
            echo '<p class="btn btn-outline-danger m-auto mb-3">No canceled orders found.</p>';
        }
        ?>
        </div>
    </div>
</div>

<?php @include("footer.php"); ?>
