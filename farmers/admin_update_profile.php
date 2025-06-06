<?php
include '../config.php';
session_start();
$admin_id = $_SESSION['admin_id'];

if (isset($_POST['update_profile'])) {
    $farmer_select = mysqli_real_escape_string($conn, $_POST['farmer_select']);
    $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
    $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Update profile information
    mysqli_query($conn, "UPDATE `users` SET name = '$update_name', email = '$update_email', contact = '$contact', address = '$address', description = '$description', farmer_select = '$farmer_select' WHERE id = '$admin_id'") or die('query failed');
    
    // Check if the password update section was shown
    if (isset($_POST['password_section_shown']) && $_POST['password_section_shown'] == 'true') {
        $old_pass = mysqli_real_escape_string($conn, $_POST['old_pass']);
        $update_pass = mysqli_real_escape_string($conn, md5($_POST['update_pass']));
        $new_pass = mysqli_real_escape_string($conn, md5($_POST['new_pass']));
        $confirm_pass = mysqli_real_escape_string($conn, md5($_POST['confirm_pass']));
        
        // Validate and update password
        if (!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)) {
            $fetch_pass_query = mysqli_query($conn, "SELECT password FROM `users` WHERE id = '$admin_id'") or die('query failed');
            $fetch_pass = mysqli_fetch_assoc($fetch_pass_query);
            
            if ($update_pass != $fetch_pass['password']) {
                $message[] = 'Old password not matched!';
            } elseif ($new_pass != $confirm_pass) {
                $message[] = 'Confirm password not matched!';
            } else {
                mysqli_query($conn, "UPDATE `users` SET password = '$confirm_pass' WHERE id = '$admin_id'") or die('query failed');
                $message[] = 'Password updated successfully!';
            }
        }
    }

    // Image Upload Logic
    $update_image = $_FILES['update_image']['name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_folder = 'images/' . $update_image;

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $message[] = 'Image is too large';
        } else {
            $image_update_query = mysqli_query($conn, "UPDATE `users` SET image = '$update_image' WHERE id = '$admin_id'") or die('query failed');
            if ($image_update_query) {
                move_uploaded_file($update_image_tmp_name, $update_image_folder);
            }
            $message[] = 'Image updated successfully!';
        }
    }

    // QR Code Upload Logic
    $update_qr = $_FILES['update_qr']['name'];
    $update_qr_size = $_FILES['update_qr']['size'];
    $update_qr_tmp_name = $_FILES['update_qr']['tmp_name'];
    $update_qr_folder = '../qr_codes/' . $update_qr;

    if (!empty($update_qr)) {
        if ($update_qr_size > 2000000) {
            $message[] = 'QR Code image is too large';
        } else {
            // Ensure column name matches your table schema
            $qr_update_query = mysqli_query($conn, "UPDATE `users` SET qr_code = '$update_qr' WHERE id = '$admin_id'") or die('query failed');
            if ($qr_update_query) {
                move_uploaded_file($update_qr_tmp_name, $update_qr_folder);
            }
            $message[] = 'QR Code updated successfully!';
        }
    }
}
?>


<?php include("admin_header.php"); ?>

<?php
$select = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id'") or die('query failed');
if(mysqli_num_rows($select) > 0){
   $fetch = mysqli_fetch_assoc($select);
}
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
          <!-- <li><a href="admin_wall.php"> Wall</a></li>
          <li><a href="post-page-admin.php">Post<span></a></li>
          <li><a href="admin_investment.php">Investments</a></li>
           -->
          <li class="dropdown"><a href="#"><span>Products</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
             
              
              <li><a href="admin_products.php">Add Product</a></li>
              <li><a href="admin_processing_products.php">Processing Product</a></li>
              <li><a href="admin_approved_products.php">Approved Product</a></li>
              <li><a href="admin_reject_products.php">Rejected Product</a></li>
            </ul>
          </li> 
          <li class="dropdown"><a href="#"><span>Orders</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
             
              
              <li><a href="admin_orders.php">Pending Orders</a></li>
              <li><a href="admin_completed.php">Completed Orders</a></li>
              
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
<div class="container m-5">
    <?php
    $select = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id'") or die('query failed');
    if (mysqli_num_rows($select) > 0) {
        $fetch = mysqli_fetch_assoc($select);
    }

    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<div class="alert alert-info">' . $msg . '</div>';
        }
    }
    ?>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-4 text-center">
                <?php
                if ($fetch['image'] == '') {
                    echo '<img src="images/default-avatar.png" class="img-fluid rounded-circle" alt="Profile Image">';
                } else {
                    echo '<img src="images/' . $fetch['image'] . '" class="img-fluid rounded-circle" alt="Profile Image">';
                }
                ?>
            </div>

            <div class="col-md-8">
                <div class="form-group">
                    <label for="update_name">Username:</label>
                    <input type="text" id="update_name" name="update_name" value="<?php echo $fetch['name']; ?>" class="form-control">
                </div>

                <div class="form-group" style="opacity:0">
                    <label for="farmer_select">Select Farmer:</label>
                    <select id="farmer_select" name="farmer_select" class="form-control">
                        <option value="farmer1" <?php if(isset($fetch['farmer_select']) && $fetch['farmer_select'] == 'farmer1') echo 'selected'; ?>>Farmers 1</option>
                        <option value="farmer2" <?php if(isset($fetch['farmer_select']) && $fetch['farmer_select'] == 'farmer2') echo 'selected'; ?>>Farmers 2</option>
                    </select>
                </div>

                <!-- Toggle More Info Button -->
                <button type="button" class="btn btn-secondary m-2" id="toggleMoreInfo">Show More Info</button>
                
                <div id="moreInfoSection" style="display: none;">
                    <div class="form-group">
                        <label for="update_email">Your Email:</label>
                        <input type="email" id="update_email" name="update_email" value="<?php echo $fetch['email']; ?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="update_image">Update Your Pic:</label>
                        <input type="file" id="update_image" name="update_image" accept="image/jpg, image/jpeg, image/png" class="form-control-file">
                    </div>

                    <div class="form-group">
                        <label for="qrcode">Add QR Code (e.g., for GCash):</label>
                        <input type="file" id="qrcode" name="update_qr" accept="image/jpg, image/jpeg, image/png" class="form-control-file">
                    </div>

                    <div class="form-group">
                        <label for="contact">Contact:</label>
                        <input type="text" id="contact" name="contact" value="<?php echo $fetch['contact']; ?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" value="<?php echo $fetch['address']; ?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="description">Description:</label>
                        <input type="text" id="description" name="description" value="<?php echo $fetch['description']; ?>" class="form-control">
                    </div>
                </div>

                <!-- Toggle Change Password Button -->
                <button type="button" class="btn btn-info m-2" id="togglePasswordSection">Change Password</button>

                <div id="passwordSection" style="display: none;">
                    <h4>Change Password</h4>
                    <div class="form-group">
                        <input type="hidden" name="password_section_shown" value="true">
                        <input type="hidden" name="old_pass" value="<?php echo $fetch['password']; ?>">
                        <label for="update_pass">Old Password:</label>
                        <input type="password" id="update_pass" name="update_pass" placeholder="Enter previous password" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="new_pass">New Password:</label>
                        <input type="password" id="new_pass" name="new_pass" placeholder="Enter new password" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="confirm_pass">Confirm Password:</label>
                        <input type="password" id="confirm_pass" name="confirm_pass" placeholder="Confirm new password" class="form-control">
                    </div>
                </div>

                <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('toggleMoreInfo').addEventListener('click', function() {
        var moreInfoSection = document.getElementById('moreInfoSection');
        if (moreInfoSection.style.display === 'none') {
            moreInfoSection.style.display = 'block';
        } else {
            moreInfoSection.style.display = 'none';
        }
    });

    document.getElementById('togglePasswordSection').addEventListener('click', function() {
        var passwordSection = document.getElementById('passwordSection');
        if (passwordSection.style.display === 'none') {
            passwordSection.style.display = 'block';
        } else {
            passwordSection.style.display = 'none';
        }
    });
</script>
<?php include("admin_footer.php"); ?>
