<?php
include '../config.php';
session_start();

$admin_id = $_SESSION['admin_id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

if (!$admin_id) {
    header('location:../login.php');
    exit;
}

if (isset($_GET['user_id'])) {
    $selected_user_id = mysqli_real_escape_string($conn, $_GET['user_id']);

    // Fetch user details
    $user_query = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$selected_user_id'") or die('Query failed');
    $user_data = mysqli_fetch_assoc($user_query);

    if (!$user_data) {
        die('User not found');
    }
} else {
    die('No user specified');
}

// Check if the current user has blocked the admin
$check_block_status = mysqli_query($conn, "SELECT * FROM `reports` WHERE reporter_id = '$user_id' AND reported_user_id = '$admin_id' AND blocked = 1") or die('Query failed');
$is_blocked = mysqli_num_rows($check_block_status) > 0;

if ($is_blocked) {
    $block_data = mysqli_fetch_assoc($check_block_status);
    $block_date = new DateTime($block_data['block_date']);
    $current_date = new DateTime();
    $interval = $current_date->diff($block_date);
    $days_blocked = $interval->days;
    $can_unblock = $days_blocked >= 5;
    $remaining_days = max(0, 5 - $days_blocked);
} else {
    $can_unblock = false;
    $remaining_days = 0;
}

$select = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id'") or die('Query failed');
if (mysqli_num_rows($select) > 0) {
    $fetch = mysqli_fetch_assoc($select);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reported_user_id = $_POST['reported_user_id'] ?? '';
    $reporter_id = $user_id;

    if (isset($_POST['block_user'])) {
        $current_date = date('Y-m-d H:i:s');

        $check_existing_block = mysqli_query($conn, "SELECT * FROM `reports` WHERE reporter_id = '$reporter_id' AND reported_user_id = '$reported_user_id' AND blocked = 1") or die('Query failed');

        if (mysqli_num_rows($check_existing_block) == 0) {
            mysqli_query($conn, "INSERT INTO `reports` (reporter_id, reported_user_id, report_type, description, blocked, block_date) VALUES ('$reporter_id', '$reported_user_id', 'blocked', 'User blocked', 1, '$current_date')") or die('Query failed');
        } else {
            mysqli_query($conn, "UPDATE `reports` SET blocked = 1, block_date = '$current_date' WHERE reporter_id = '$reporter_id' AND reported_user_id = '$reported_user_id'") or die('Query failed');
        }

        header("Location: {$_SERVER['PHP_SELF']}?user_id=$selected_user_id");
        exit;
    }

    if (isset($_POST['unblock_user']) && $can_unblock) {
        mysqli_query($conn, "UPDATE `reports` SET blocked = 0 WHERE reporter_id = '$reporter_id' AND reported_user_id = '$reported_user_id'") or die('Query failed');
        echo "<script>alert('User has been unblocked successfully.');</script>";
        header("Location: {$_SERVER['PHP_SELF']}?user_id=$selected_user_id");
        exit;
    }

    if (isset($_POST['report_user']) && !isset($_POST['block_user'])) {
        $report_reason = $_POST['report_reason'] ?? '';
        $report_description = $_POST['report_description'] ?? '';

        if ($report_reason && $report_description) {
            mysqli_query($conn, "INSERT INTO `reports` (reporter_id, reported_user_id, report_type, description) VALUES ('$reporter_id', '$reported_user_id', '$report_reason', '$report_description')") or die('Query failed');
            echo "<script>alert('Report submitted successfully.');</script>";
        } else {
            echo "<script>alert('Please fill out all fields.');</script>";
        }
    }
}
?>

<!-- Include header -->
<?php include("admin_header.php"); ?>
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


<div class="container my-5">
    <h2>User Details</h2>
    <div class="col-md-4 mb-4">
                                            <div class="card">
                                              <?php
                                            $admin_image = $user_data['image'] ? 'images/' . $user_data['image'] : 'images/default-avatar.png';
                                        ?>
                                                <img src="<?php echo $admin_image; ?>" class="card-img-top" alt="User Image">
                                                <div class="card-body">
                                                <!-- <p><a href="users.php?user_id=<?= $user_data['id']; ?>"><?= $user_data['name']; ?></a></p> -->
                                                <p><a href="admin_user?user_id=<?= $user_data['id']; ?>"><?= $user_data['name']; ?></a></p>
                                                <a href="admin-chatsystem.php?user_id=<?php echo htmlspecialchars($user_data['id']); ?>" class="btn btn-primary">Message</a>
                                                </div>
                                                <form action="" method="post">
                        <input type="hidden" name="reported_user_id" value="<?php echo $admin_data['id']; ?>">
                        <button type="submit" name="unblock_user" class="btn btn-warning" <?php echo !$can_unblock ? 'disabled' : ''; ?>>Unblock</button>
                        <?php if (!$can_unblock) { ?>
                            <p class="text-danger">You can unblock this user after <?php echo $remaining_days; ?> day(s).</p>
                        <?php } else { ?>
                            <p class="text-success">You can now unblock this user.</p>
                        <?php } ?>
                    </form>
                                            </div>
                                        </div>
</div>

<!-- Include footer -->
<?php include("admin_footer.php"); ?>
