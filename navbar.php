<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
    header('location:landing-page.php');
    exit;
}
?>

<?php @include("header.php") ?>
<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <!-- <h1 class="sitename">FCs-Connect</h1> -->
        <img src="images/logo.jpeg" style="max-height: 36px; border-radius: 100px;margin-right: 8px;transform: scale(1.5);" alt="logo" srcset="">
        <span>.</span>



        <div class="d-flex align-items-center p-2 ml-2">
        
        </div>
      </a>
      
      <nav id="navmenu" class="navmenu d-flex">
          <a href="search_page.php">
            <input class="form-control mr-sm-1" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </a>
      </nav>
    
      <?php
            // Fetch unread messages count for both user and admin messages
            $select_messages = mysqli_query($conn, "SELECT COUNT(*) AS count FROM `message` WHERE user_id = '$user_id' AND is_read = 0") or die('Query failed: ' . mysqli_error($conn));
            $message_count = mysqli_fetch_assoc($select_messages)['count'];

            $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed: ' . mysqli_error($conn));
            $cart_rows_number = mysqli_num_rows($select_cart_number);

            // Fetch unread notifications count
            $select_notifications = mysqli_query($conn, "SELECT COUNT(*) AS count FROM `notifications` WHERE user_id = '$user_id' AND is_read = 0") or die('Query failed: ' . mysqli_error($conn));
            $notification_count = mysqli_fetch_assoc($select_notifications)['count'];
        ?>
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php" class="active">Home<br></a></li>
          <li><a href="browse_products.php">Browse Products</a></li>
          <li><a href="msg.php">Message<span><?php echo $message_count; ?></span></a></li>
          <!-- <li><a href="investment.php">Investments</a></li> -->
          <li><a href="cart.php"><i class="fas fa-shopping-cart"></i><span><?php echo $cart_rows_number; ?></span></a></li>
          <li><a href="notification.php"><i class="fas fa-bell"></i><span><?php echo $notification_count; ?></span></a></li>
          <li class="dropdown"><a href="#"><span><i class="fas fa-user-circle"></i></span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
             
              
              <li><a href="profile.php">Profile</a></li>
              <li><a href="my_orders.php">My Order</a></li>
              <li><a href="canceled-orders.php">Canceled Orders</a></li>
              <li><a href="logout.php">Logout</a></li>
              
            </ul>
          </li> 
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

   
      
    </div>
  </header>
  