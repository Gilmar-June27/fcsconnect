<?php
include '../config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../login.php');
}

// Fetch the admin's name
$admin_query = mysqli_query($conn, "SELECT name FROM `users` WHERE id = '$admin_id'") or die('query failed');
$admin_data = mysqli_fetch_assoc($admin_query);
$admin_name = $admin_data['name'];

function updateTopSales($conn) {
    $today = date('Y-m-d');
    
    // Fetch orders for today
    $query = "SELECT total_products FROM `orders` WHERE DATE(placed_on) = '$today'";
    $result = mysqli_query($conn, $query) or die('Query failed');

    $product_counts = [];
    $total_orders = mysqli_num_rows($result);

    while ($row = mysqli_fetch_assoc($result)) {
        $products = explode(', ', $row['total_products']);
        foreach ($products as $product) {
            if (!empty($product)) {
                if (isset($product_counts[$product])) {
                    $product_counts[$product]++;
                } else {
                    $product_counts[$product] = 1;
                }
            }
        }
    }

    // Find the top-selling product
    if ($total_orders > 0) {
        arsort($product_counts);
        $top_product = key($product_counts);
        $top_product_count = $product_counts[$top_product];
        $top_product_percentage = ($top_product_count / $total_orders) * 100;

        // Update each order with the top-selling product and percentage
        $update_query = "UPDATE `orders` SET top_sales = '$top_product', top_sales_percentage = '$top_product_percentage' WHERE DATE(placed_on) = '$today'";
        mysqli_query($conn, $update_query) or die('Query failed');
    }
}

if (isset($_POST['update_order'])) {
    $order_update_id = $_POST['order_id'];
    $update_payment = $_POST['update_payment'];
    
    // Update the order payment status
    mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
    
    // Fetch the user_id associated with the order
    $order_query = mysqli_query($conn, "SELECT user_id FROM `orders` WHERE id = '$order_update_id'") or die('query failed');
    $order_row = mysqli_fetch_assoc($order_query);
    $user_id = $order_row['user_id'];
  
    // Insert notification with admin's name
    $admin_name = mysqli_real_escape_string($conn, $admin_name);
    $message = "Your Order has been completed";
    $msg = "$admin_name";
    $escaped_message = mysqli_real_escape_string($conn, $message);
    $escaped_msg = mysqli_real_escape_string($conn, $msg);
    
    mysqli_query($conn, "INSERT INTO `notifications` (user_id, message, msg) VALUES ('$user_id', '$escaped_message', '$escaped_msg')") or die('query failed');
    
    // Update the top sales information
    updateTopSales($conn);
    // Directly output the success message
    echo '<p class="alert alert-success">Payment status has been updated!</p>';
}

if (isset($_POST['update_message'])) {
    $notification_id = $_POST['notification_id'];
    $new_message = $_POST['new_message'];

    // Update the notification message
    mysqli_query($conn, "UPDATE `notifications` SET message = '$new_message' WHERE id = '$notification_id'") or die('query failed');

    echo '<p class="alert alert-success">Notification message has been updated!</p>';
}

if (isset($_POST['add_message'])) {
    $user_id = $_POST['user_id'];
    $new_message = $_POST['new_message'];
    $msg = $_POST['msg'];

    // Check if the user_id exists in orders
    $user_check_query = mysqli_query($conn, "SELECT id FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
    if (mysqli_num_rows($user_check_query) > 0) {
        // Insert new notification message with admin's name
        $message = "$new_message";
        
        mysqli_query($conn, "INSERT INTO `notifications` (user_id, message, msg) VALUES ('$user_id', '$message', '$msg')") or die('query failed');
        header('location:admin_orders.php');
        exit();
    } else {
        echo '<p class="alert alert-danger">No orders found for this user ID!</p>';
    }
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
    header('location:admin_orders.php');
}

if (isset($_GET['deletes'])) {
    mysqli_query($conn, "DELETE FROM `orders`") or die('query failed');
    header('location:admin_orders.php');
}
?>
<?php include("admin_header.php") ?>

<?php
$select = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id'") or die('query failed');
if(mysqli_num_rows($select) > 0){
   $fetch = mysqli_fetch_assoc($select);
}
?>
<?php @include("admin_header.php") ?>
<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">

      <a href="admin_dashboard.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <img src="../images/logo.jpeg" style="max-height: 36px; border-radius: 100px;margin-right: 8px;transform: scale(1.5);" alt="logo" srcset="">
        <span>.</span>



        <div class="d-flex align-items-center p-2 ml-2">
        
        </div>
      </a>
      
      <nav id="navmenu" class="navmenu d-flex">
          <a href="admin_search.php">
            <input class="form-control mr-sm-1" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </a>
      </nav>
    
      <?php
    // Fetch unread messages count
$select_messages = mysqli_query($conn, "SELECT COUNT(*) AS count FROM `message` WHERE admin_id = '$admin_id' AND is_read = 0") or die('query failed');
$message_count = mysqli_fetch_assoc($select_messages)['count'];
    ?>
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="admin_dashboard.php" class="active">Home<br></a></li>
           <!--- <li><a href="all_user.php"> Buyers</a></li>
         <li><a href="post-page-admin.php">Post<span></a></li>
          <li><a href="admin_investment.php">Investments</a></li> -->
          
          <li class="dropdown"><a href="#"><span>Products</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
             
              
              <li><a href="admin_products.php">Add Product</a></li>
              <li><a href="admin_shipping_fee.php">Add Shipping Fee</a></li>
              <li><a href="admin_processing_products.php">Ready To Harvest Product</a></li>
              <li><a href="admin_approved_products.php">Harvested Product</a></li>

              <li><a href="admin_reject_products.php">Ready to pick up Product</a></li>
            </ul>
          </li> 
          <li class="dropdown"><a href="#"><span>Orders</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
             
              
              <li><a href="admin_orders.php">Pending Orders</a></li>
              <li><a href="admin_completed.php">Completed Orders</a></li>
              <li><a href="admin_processing_order.php">Processing Orders</a></li>
              <li><a href="admin_cancelled.php">Cancelled Orders</a></li>
            </ul>
          </li> 
         
          <li><a href="admin_analytics.php">Analytics</a></li>
          <li><a href="admin_msg.php">Messages<span><?php echo $message_count; ?></span></a></li>
          <li class="dropdown"><a href="#"><span>
            <?php
           if($fetch['image'] == ''){
              echo '<img src="images/default-avatar.png" style="width: 30px;
                                                                aspect-ratio: 1;
                                                                border-radius: 100px;">';
           } else {
              echo '<img src="images/'.$fetch['image'].'" style="width: 30px;
                                                                aspect-ratio: 1;
                                                                border-radius: 100px;">';
           }
          ?></span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
             
              
              <li><a href="admin_profile.php">Profile</a></li>
              <li><a href="../logout.php">Logout</a></li>
            </ul>
          </li> 
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

   
      
    </div>
  </header>

  <div class="container mt-4">
    <h1 class="text-center text-success mt-4">Concelled Orders</h1>

    <section class="mt-4">
        <form action="" method="post" class="m-2">
            <button type="submit" class="btn btn-danger" name="deletes">Delete All Orders</button>
        </form>

        <div class="container">
    <div class="table-responsive">
      <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>User Id</th>
                    <th>Placed On</th>
                    <th>Name</th>
                    <th>Number</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Total Products</th>
                    <th>Total Price</th>
                    <th>Payment Method</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE admin_id='$admin_id' AND payment_status='canceled'") or die('query failed');
                if(mysqli_num_rows($select_orders) > 0){
                    while($fetch_orders = mysqli_fetch_assoc($select_orders)){
                        $is_completed = isset($fetch_orders['payment_status']) && $fetch_orders['payment_status'] === 'completed';
                ?>
                <tr>
                    <td><?php echo $fetch_orders['user_id']; ?></td>
                    <td><?php echo $fetch_orders['placed_on']; ?></td>
                    <td><?php echo $fetch_orders['name']; ?></td>
                    <td><?php echo $fetch_orders['number']; ?></td>
                    <td><?php echo $fetch_orders['email']; ?></td>
                    <td><?php echo $fetch_orders['address']; ?></td>
                    <td><?php echo $fetch_orders['total_products']; ?></td>
                    <td>₱<?php echo $fetch_orders['total_price']; ?></td>
                    <td><?php echo $fetch_orders['method']; ?></td>
                    <td>
                        <button type="button" class="btn btn-info add-btn" data-user-id="<?php echo $fetch_orders['user_id']; ?>">Add</button>
                    </td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
                            <!-- <select name="update_payment" class="form-control mb-2" <?php if($is_completed) echo 'disabled'; ?> disabled>
                                <option value="pending" <?php if(!$is_completed) echo 'selected'; ?>>Pending</option>
                                <option value="completed" <?php if($is_completed) echo 'selected'; ?>>Completed</option>
                            </select>
                            <button type="submit" name="update_order" class="btn btn-primary" <?php if($is_completed) echo 'disabled'; ?> disabled>Update</button> -->
                            <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this order?');" <?php if($is_completed) echo 'style="pointer-events: none;"'; ?>>Delete</a>
                        </form>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="11" class="text-center">No orders placed yet!</td></tr>';
                }
                ?>
            </tbody>
        </table>
</div></div>

    </section>

    <!-- Modal -->
    <section class="modal fade" id="addNotificationModal" tabindex="-1" role="dialog" aria-labelledby="addNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNotificationModalLabel">Add New Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="modal_user_id" name="user_id" value="">
                        <div class="form-group">
                            <label for="new_message">Message</label>
                            <textarea id="new_message" name="new_message" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="msg">Additional Info</label>
                            <input id="msg" name="msg" type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="add_message" class="btn btn-primary">Add Message</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('.add-btn').click(function() {
            var userId = $(this).data('user-id');
            $('#modal_user_id').val(userId);
            $('#addNotificationModal').modal('show');
        });
    });
</script>

