<?php
include '../config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../login.php');
    exit();
}

$select = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id'") or die('query failed');
if(mysqli_num_rows($select) > 0){
   $fetch = mysqli_fetch_assoc($select);
}


include("admin_header.php");

?>

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

<!-- Section Title -->
<div class="container section-title" data-aos="fade-up">
    <p><span>Out of Stock</span> <span class="description-title">Products</span></p>
</div>
<!-- End Section Title -->

<section class="show-products py-5">
    <div class="container">
        <div class="row">
            <?php
            // Select products where the available stock is zero
            $select_outofstock_products = mysqli_query($conn, "SELECT * FROM `products` WHERE admin_id = '$admin_id' AND available_kilo = 0") or die('Query failed');

            if (mysqli_num_rows($select_outofstock_products) > 0) {
                while ($fetch_outofstock = mysqli_fetch_assoc($select_outofstock_products)) {
                    $product_id = $fetch_outofstock['id'];
                    $images = explode(',', $fetch_outofstock['images']);
                    $main_image = isset($images[0]) ? $images[0] : 'default-image.jpg';
            ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="../images/<?php echo $main_image; ?>" class="card-img-top" alt="<?php echo $fetch_outofstock['name']; ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $fetch_outofstock['name']; ?></h5>
                        <p class="card-text">₱<?php echo $fetch_outofstock['price']; ?></p>
                        <p class="card-text"><?php echo $fetch_outofstock['category']; ?></p>
                        <p class="card-text"><?php echo $fetch_outofstock['descriptions']; ?></p>
                        <p class="card-text text-danger"><strong>Out of Stock</strong></p>
                        <div class="d-flex justify-content-between">
                            <a href="admin_products.php?delete=<?php echo $fetch_outofstock['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this product?');">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo '<p class="text-center">No out-of-stock products available.</p>';
            }
            ?>
        </div>
    </div>
</section>

<?php include("admin_footer.php"); ?>
