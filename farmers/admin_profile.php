<?php
include '../config.php';
session_start();
$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../login.php');
}
?>
<?php include("admin_header.php"); ?>

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
              <li><a href="out_of_deliver_order.php">Out of Delivery Orders</a></li>
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
<?php
$select = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id'") or die('query failed');
if (mysqli_num_rows($select) > 0) {
    $fetch = mysqli_fetch_assoc($select);
}
?>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-4 text-center">
            <div class="profile-header">
                <?php
                if ($fetch['image'] == '') {
                    echo '<img src="images/default-avatar.png" class="img-fluid rounded-circle" alt="Profile Image">';
                } else {
                    echo '<img src="images/' . $fetch['image'] . '" class="img-fluid rounded-circle" alt="Profile Image">';
                }
                ?>
                <h1 class="m-3"><b style="font-weight:900"><?php echo $fetch['name']; ?></b></h1>
                <h1 class="m-3"><?php echo $fetch['farmer_select']; ?></h1>
            </div>
        </div>
        <div class="col-md-8">
            <div class="profile-details">
                <h2>Info</h2>
                <div class="list-group">
                    <p class="list-group-item"><strong>Email:</strong> <?php echo $fetch['email']; ?></p>
                    <p class="list-group-item"><strong>Username:</strong> <?php echo $fetch['username']; ?></p>
                    <p class="list-group-item"><strong>User Type:</strong> Farmer</p>
                    <?php
                    if (!empty($fetch['contact']) || !empty($fetch['address']) || !empty($fetch['description'])) {
                        ?>
                        <p class="list-group-item"><strong>Contact:</strong> <?php echo $fetch['contact']; ?></p>
                        <p class="list-group-item"><strong>Address:</strong> <?php echo $fetch['address']; ?></p>
                        <p class="list-group-item"><strong>Description:</strong> <?php echo $fetch['description']; ?></p>
                        <?php
                    } else {
                        ?>
                        <p class="list-group-item"><strong>Contact:</strong> <?php echo $fetch['contact']; ?></p>
                        <p class="list-group-item"><strong>Address:</strong> <?php echo $fetch['address']; ?></p>
                        <p class="list-group-item"><strong>Description:</strong> <?php echo $fetch['description']; ?></p>
                        <?php
                    }
                    ?>
                </div>

                <!-- Button to toggle QR Code display -->
                <button id="toggleQrCode" class="btn btn-secondary mt-3">Show My QR Code</button>
                
                <!-- QR Code Image -->
                <div id="qrCodeContainer" style="display: none; margin-top: 20px;">
                    <?php
                    if (!empty($fetch['qr_code'])) {
                        echo '<img src="../qr_codes/' . $fetch['qr_code'] . '" class="img-fluid" alt="QR Code" style="max-width: 200px;">';
                    } else {
                        echo '<p>No QR Code available.</p>';
                    }
                    ?>
                </div> 

           

                <button id="toggleId" class="btn btn-secondary mt-3">Show My Farmers Id </button>
                
                <!-- QR Code Image -->
                <div id="IdContainer" style="display: none; margin-top: 20px;">
                    <?php
                    if (!empty($fetch['farmers_id'])) {
                        echo '<img src="../images/' . $fetch['farmers_id'] . '" class="img-fluid" alt="QR Code" style="max-width: 200px;">';
                    } else {
                        echo '<p>No Farmers Id available.</p>';
                    }
                    ?>
                </div> 

                <a href="admin_update_profile.php" class="btn btn-primary mt-3">Update Profile</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('toggleQrCode').addEventListener('click', function() {
        var qrCodeContainer = document.getElementById('qrCodeContainer');
        if (qrCodeContainer.style.display === 'none') {
            qrCodeContainer.style.display = 'block';
            this.textContent = 'Hide QR Code';
        } else {
            qrCodeContainer.style.display = 'none';
            this.textContent = 'Show QR Code';
        }
    });

    document.getElementById('toggleId').addEventListener('click', function() {
        var qrCodeContainer = document.getElementById('IdContainer');
        if (qrCodeContainer.style.display === 'none') {
            qrCodeContainer.style.display = 'block';
            this.textContent = 'Hide Farmers Id';
        } else {
            qrCodeContainer.style.display = 'none';
            this.textContent = 'Show Farmers Id';
        }
    });
</script>


<?php include("admin_footer.php"); ?>
