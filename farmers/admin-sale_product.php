<?php
include '../config.php';
session_start();

$admin_id = $_GET['admin_id'] ?? '';

if (!isset($admin_id)) {
    header('location:../login.php');
    exit;
}

$admin_query = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id' AND user_type = 'admin'") or die('Query failed');
$admin_data = mysqli_fetch_assoc($admin_query);
$status_class = $admin_data['status'] == 'activate' ? 'success' : 'danger';

if (!$admin_data) {
    echo 'Admin not found!';
    exit;
}

// Check if the current user has blocked the admin
$check_block_status = mysqli_query($conn, "SELECT * FROM `reports` WHERE reporter_id = '{$_SESSION['user_id']}' AND reported_user_id = '$admin_id' AND blocked = 1") or die('Query failed');
$is_blocked = mysqli_num_rows($check_block_status) > 0;

if ($is_blocked) {
    $block_data = mysqli_fetch_assoc($check_block_status);
    $block_date = new DateTime($block_data['block_date']);
    $current_date = new DateTime();
    $interval = $current_date->diff($block_date);
    $days_blocked = $interval->days;
    $can_unblock = $days_blocked >= 5;

    // Calculate remaining days if unblock is not possible
    $remaining_days = 5 - $days_blocked;
} else {
    $can_unblock = false;
    $remaining_days = 0;
}

if (isset($_POST['add_to_cart']) && !$is_blocked) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '{$_SESSION['user_id']}'") or die('Query failed');

    if (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'Already added to cart!';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('{$_SESSION['user_id']}', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('Query failed');
        $message[] = 'Product added to cart!';
    }
}

if (isset($_POST['block_user'])) {
    $reported_user_id = $_POST['reported_user_id'] ?? '';
    $reporter_id = $_SESSION['user_id'];
    $current_date = date('Y-m-d H:i:s');

    // Check if the user is already blocked
    $check_existing_block = mysqli_query($conn, "SELECT * FROM `reports` WHERE reporter_id = '$reporter_id' AND reported_user_id = '$reported_user_id' AND blocked = 1") or die('Query failed');

    if (mysqli_num_rows($check_existing_block) == 0) {
        mysqli_query($conn, "INSERT INTO `reports` (reporter_id, reported_user_id, report_type, description, blocked, block_date) VALUES ('$reporter_id', '$reported_user_id', 'blocked', 'has been blocked', 1, '$current_date')") or die('Query failed');
    } else {
        mysqli_query($conn, "UPDATE `reports` SET blocked = 1, block_date = '$current_date' WHERE reporter_id = '$reporter_id' AND reported_user_id = '$reported_user_id'") or die('Query failed');
    }

    echo "<script>alert('User has been blocked successfully.');</script>";
    header('Location: ' . $_SERVER['PHP_SELF'] . '?admin_id=' . $admin_id);
    exit;
}

if (isset($_POST['unblock_user'])) {
    if ($can_unblock) {
        $reported_user_id = $_POST['reported_user_id'] ?? '';
        $reporter_id = $_SESSION['user_id'];

        // Unblock the user
        mysqli_query($conn, "UPDATE `reports` SET blocked = 0 WHERE reporter_id = '$reporter_id' AND reported_user_id = '$reported_user_id'") or die('Query failed');

        echo "<script>alert('User has been unblocked successfully.');</script>";
        header('Location: ' . $_SERVER['PHP_SELF'] . '?admin_id=' . $admin_id);
        exit;
    } else {
        echo "<script>alert('You can only unblock the user after 5 days.');</script>";
    }
}

if (isset($_POST['report_user']) && !isset($_POST['block_user'])) {
    $report_reason = $_POST['report_reason'] ?? '';
    $report_description = $_POST['report_description'] ?? '';
    $reported_user_id = $_POST['reported_user_id'] ?? '';
    $reporter_id = $_SESSION['user_id'];

    if ($report_reason && $report_description) {
        mysqli_query($conn, "INSERT INTO `reports` (reporter_id, reported_user_id, report_type, description) VALUES ('$reporter_id', '$reported_user_id', '$report_reason', '$report_description')") or die('Query failed');
        echo "<script>alert('Report submitted successfully.');</script>";
    } else {
        echo "<script>alert('Please fill out all fields.');</script>";
    }
}


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

  
<main class="container mt-4">
    <section class="row mb-4">
        <div class="col-md-4 text-center">
            <?php 
                $admin_image = $admin_data['image'] ? 'images/' . $admin_data['image'] : 'images/default-avatar.png';
            ?>
            <img src="<?php echo $admin_image; ?>" alt="Admin Image" class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
            <div class="mt-2">
                <span class="badge rounded-circle <?php echo 'bg-' . $status_class; ?>" style="width: 20px; height: 20px; display: inline-block;"></span>
            </div>
        </div>
        <div class="col-md-8">
            <h2><?php echo $admin_data['name']; ?></h2>
            <div class="d-flex align-items-center mb-3">
                <p class="me-3">Farmer</p>
                <?php if (!$is_blocked) { ?>
                    <a href="chatsystem.php?admin_id=<?php echo $admin_data['id']; ?>&scroll_to_user=<?php echo $admin_data['id']; ?>" class="btn btn-primary me-2">Message</a>
                    <button class="btn btn-secondary" id="reportButton">Report</button>
                <?php } else { ?>
                    <form action="" method="post">
                        <input type="hidden" name="reported_user_id" value="<?php echo $admin_data['id']; ?>">
                        <button type="submit" name="unblock_user" class="btn btn-warning" <?php echo !$can_unblock ? 'disabled' : ''; ?>>Unblock</button>
                        <?php if (!$can_unblock) { ?>
                            <p class="text-danger">You can unblock this user after <?php echo $remaining_days; ?> day(s).</p>
                        <?php } else { ?>
                            <p class="text-success">You can now unblock this user.</p>
                        <?php } ?>
                    </form>
                <?php } ?>
            </div>
        </div>
    </section>

    <h2 class="text-center text-success">Products</h2>
    <section class="row">
    <div class="row gy-5">

<div class="container m-5">
<div class="row row-cols-1 row-cols-md-3">
        <?php  
            $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE admin_id = '$admin_id' AND status = 'approved'") or die('Query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
        ?>
            <div class="col mb-4">
                
                <div class="card">
                <form action="" method="post">
                    <img src="images/<?php echo $fetch_products['image']; ?>" class="card-img-top" alt="..." style="height: 52vh;">
                    
                    <div class="card-body text-center">
                        <h3><?php echo $fetch_products['name']; ?></h3>
                        <p>₱<?php echo $fetch_products['price']; ?></p>
                        <p>Status: <?php echo ($fetch_products['havest_status'] == 'ready_to_harvest') ? 'Ready to Harvest' : 'Harvested'; ?></p>
                        <p>Description: <?php echo $fetch_products['descriptions']; ?></p>
                        <div class="mb-3">
                             <input type="number" min="1" name="product_quantity" value="1" class="form-control text-center" style="width: 100px; margin: 0 auto;">
                        </div>

                        <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">

                        <button type="submit" name="add_to_cart" class="btn btn-primary" <?php echo $is_blocked ? 'disabled' : ''; ?>>
                                <?php echo $is_blocked ? 'Blocked' : 'Add to Cart'; ?>
                            </button>
                   </div>
                </form>   
                </div>
                
            </div>
        <?php 
                }
            } else {
                echo '<p class="text-center">No products available.</p>';
            }
        ?>
        </div>
        </div>
        </div>
    </section>
</main>

<!-- <button type="submit" name="add_to_cart" class="btn btn-primary" <?php echo $is_blocked ? 'disabled' : ''; ?>>
                                <?php echo $is_blocked ? 'Blocked' : 'Add to Cart'; ?>
                            </button> -->
    <!-- Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Report User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <input type="hidden" name="reported_user_id" value="<?php echo $admin_id; ?>">
                        <div class="mb-3">
                            <label for="report_reason" class="form-label">Reason:</label>
                            <select name="report_reason" id="report_reason" class="form-select">
                                <option value="Spam">Spam</option>
                                <option value="Abuse">Abuse</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="report_description" class="form-label">Description:</label>
                            <textarea name="report_description" id="report_description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <button type="submit" name="report_user" class="btn btn-primary me-2">Submit Report</button>
                            <button type="submit" name="block_user" class="btn btn-danger">Block User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('reportButton').addEventListener('click', function() {
    var reportModal = new bootstrap.Modal(document.getElementById('reportModal'));
    reportModal.show();
});
</script>