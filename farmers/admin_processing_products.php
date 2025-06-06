<?php
include '../config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../login.php');
   exit();
}


if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('Query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('images/'.$fetch_delete_image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('Query failed');
   
}

// Define harvest_status (could be from a form or set directly)
$harvest_status = isset($_GET['harvest_status']) ? $_GET['harvest_status'] : '';  // Default empty or set a default status like 'approved'


// if (isset($_POST['update_product'])) {
//    $update_p_id = $_POST['update_p_id'];
//    $update_name = $_POST['update_name'];
//    $update_price = $_POST['update_price'];
//    $update_available_kilo = $_POST['update_available_kilo'];
//    $update_category = $_POST['update_category'];
//    $update_descriptions = $_POST['update_descriptions'];
//    $update_havest_status = $_POST['update_havest_status'];
//    $update_harvest_date = $_POST['dates'];

//    mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price', available_kilo = '$update_available_kilo', category = '$update_category', descriptions = '$update_descriptions', havest_status = '$update_havest_status', harvest_date = '$update_harvest_date' WHERE id = '$update_p_id'");

//    $update_image = $_FILES['update_image']['name'];
//    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
//    $update_image_size = $_FILES['update_image']['size'];
//    $update_folder = 'images/' . $update_image;
//    $update_old_image = $_POST['update_old_image'];

//    if (!empty($update_image)) {
//        if ($update_image_size > 2000000) {
//            $_SESSION['message'] = 'Image file size is too large';
//        } else {
//            mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'");
//            move_uploaded_file($update_image_tmp_name, $update_folder);
//            unlink('images/' . $update_old_image);
//        }
//    }

//    header('Location: admin_products.php');
//    exit();
// }
if (isset($_POST['update_product'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_name = $_POST['update_name'];
    $update_price = $_POST['update_price'];
    $update_available_kilo = $_POST['update_available_kilo'];
    $update_category = $_POST['update_category'];
    $update_descriptions = $_POST['update_descriptions'];
    $update_havest_status = $_POST['update_havest_status'];
    $update_harvest_date = $_POST['dates'];

    // Update product details first
    mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price', available_kilo = '$update_available_kilo', category = '$update_category', descriptions = '$update_descriptions', havest_status = '$update_havest_status', harvest_date = '$update_harvest_date' WHERE id = '$update_p_id'") or die('Query failed');

    // Handle image updates
    $update_images = $_FILES['update_images']['name'];
    $update_images_tmp_name = $_FILES['update_images']['tmp_name'];
    $update_images_size = $_FILES['update_images']['size'];
    
    $image_folder = '../images/';
    $image_array = [];
    
    // Delete old images
    $old_image_query = mysqli_query($conn, "SELECT images FROM `products` WHERE id = '$update_p_id'") or die('Query failed');
    $fetch_old_images = mysqli_fetch_assoc($old_image_query);
    $old_images = explode(',', $fetch_old_images['images']);
    
    foreach ($old_images as $old_image) {
        // unlink($image_folder . $old_image);
    }

    // If new images are uploaded
    if (!empty($update_images[0])) {
        foreach ($update_images as $key => $image_name) {
            $image_tmp_name = $update_images_tmp_name[$key];
            $image_size = $update_images_size[$key];
            
            // Check file size (2MB limit)
            if ($image_size > 2000000) {
                $_SESSION['message'] = 'One or more image file sizes are too large';
                header('Location: admin_products.php');
                exit();
            } else {
                $new_image_name = time() . '_' . $image_name;
                move_uploaded_file($image_tmp_name, $image_folder . $new_image_name);
                $image_array[] = $new_image_name; // Store new image name in array
            }
        }
        
        // Update database with new images
        $new_images = implode(',', $image_array);
        mysqli_query($conn, "UPDATE `products` SET images = '$new_images' WHERE id = '$update_p_id'") or die('Query failed');
    }

   
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
  
 <!-- Show products -->

<!-- Section Title --><div class="container section-title" data-aos="fade-up">
      <p><span>Ready to Harvest </span> <span class="description-title">Product</span></p>
    </div><!-- End Section Title -->
 <!-- Processing Products Section -->
<section class="show-products py-5">
    <div class="container">
 <div class="row">
        <?php
    $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE admin_id = '$admin_id' AND havest_status = 'ready_to_harvest'");

        if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                $product_id = $fetch_products['id'];
                $images = explode(',', $fetch_products['images']);
        ?>
        <div class="col-md-4 mb-4">
    <div class="card">
        <?php 
            // Explode the images to handle multiple images
            $images = explode(',', $fetch_products['images']);
            $main_image = isset($images[0]) ? $images[0] : 'default-image.jpg'; // Fallback to default image if no image found
        ?>
        <img src="../images/<?php echo $main_image; ?>" class="card-img-top" alt="<?php echo $fetch_products['name']; ?>" style="height: 200px; object-fit: cover;" data-bs-toggle="modal" data-bs-target="#productModal<?php echo $fetch_products['id']; ?>">
        <div class="card-body">
            <h5 class="card-title"><?php echo $fetch_products['name']; ?></h5>
            <p class="card-text">₱<?php echo $fetch_products['price']; ?></p>
            <p class="card-text"><?php echo $fetch_products['available_kilo']; ?> <?php echo $fetch_products['sizes']; ?></p>
            <p class="card-text"><?php echo $fetch_products['descriptions']; ?></p>
            <p class="card-text"><?php echo $fetch_products['category']; ?></p>
            <p class="card-text"><?php echo $fetch_products['havest_status']; ?></p>
            <div class="d-flex justify-content-between">
                <?php if ($fetch_products['status'] == 'approved'): ?>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProductModal<?php echo $fetch_products['id']; ?>">Update</button>
                    <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this product?');">Delete</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal with Carousel for viewing product images -->
<div class="modal fade" id="productModal<?php echo $fetch_products['id']; ?>" tabindex="-1" aria-labelledby="productModalLabel<?php echo $fetch_products['id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel<?php echo $fetch_products['id']; ?>"><?php echo $fetch_products['name']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="carouselExample<?php echo $fetch_products['id']; ?>" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $active = 'active';
                        foreach ($images as $image) {
                            ?>
                            <div class="carousel-item <?php echo $active; ?>">
                                <img src="../images/<?php echo $image; ?>" class="d-block w-100" alt="<?php echo $fetch_products['name']; ?>" style="height: 400px; object-fit: cover;">
                            </div>
                            <?php
                            $active = ''; // Remove active after the first image
                        }
                        ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample<?php echo $fetch_products['id']; ?>" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample<?php echo $fetch_products['id']; ?>" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <!-- Slide Buttons -->
            <div class="modal-footer justify-content-center">
                <button class="btn btn-secondary prev-slide-btn" data-carousel-id="carouselExample<?php echo $fetch_products['id']; ?>">Previous</button>
                <button class="btn btn-primary next-slide-btn" data-carousel-id="carouselExample<?php echo $fetch_products['id']; ?>">Next</button>
            </div>
        </div>
    </div>
</div>

        <?php
            }
        } else {
            echo '<div class="col-12"><p class="text-center text-muted">No products added yet!</p></div>';
        }
        ?>
    </div>
</div>
      </div>
      </section>
<!-- Edit Product Form Modal -->
<?php
$select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE admin_id = '$admin_id'");
while ($fetch_products = mysqli_fetch_assoc($select_products)) {
    $product_id = $fetch_products['id'];
?>
<div class="modal fade" id="editProductModal<?php echo $product_id; ?>" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    
                    <input type="hidden" name="update_p_id" value="<?php echo $fetch_products['id']; ?>">
                    <div class="mb-3">
                        <label for="update_name" class="form-label">Product Name</label>
                        <input type="text" name="update_name" class="form-control" value="<?php echo $fetch_products['name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_price" class="form-label">Price</label>
                        <input type="number" name="update_price" min="0" class="form-control" value="<?php echo $fetch_products['price']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_available_kilo" class="form-label">Available Kilo</label>
                        <input type="number" name="update_available_kilo" min="0" class="form-control" value="<?php echo $fetch_products['available_kilo']; ?>" required>

                    </div>
                    <div class="mb-3">
                        <label for="update_category" class="form-label">Category</label>
                        <select name="update_category" class="form-select">
                            <option value="sea_foods" <?php echo $fetch_products['category'] == 'sea_foods' ? 'selected' : ''; ?>>Sea Foods</option>
                            <option value="vegetables" <?php echo $fetch_products['category'] == 'vegetables' ? 'selected' : ''; ?>>Vegetables</option>
                            <option value="crops" <?php echo $fetch_products['category'] == 'crops' ? 'selected' : ''; ?>>Crops</option>
                            <option value="fruits" <?php echo $fetch_products['category'] == 'fruits' ? 'selected' : ''; ?>>Fruits</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="update_descriptions" class="form-label">Description</label>
                        <input type="text" name="update_descriptions" class="form-control" value="<?php echo $fetch_products['descriptions']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_havest_status" class="form-label">Harvest Status</label>
                        <select name="update_havest_status" class="form-select">
                            <option value="ready_to_harvest" <?php echo $fetch_products['havest_status'] == 'ready_to_harvest' ? 'selected' : ''; ?>>Ready to Harvest</option>
                            <option value="harvested" <?php echo $fetch_products['havest_status'] == 'harvested' ? 'selected' : ''; ?>>Harvested</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="update_dates" class="form-label">Estimated Harvest Date</label>
                        <input type="date" name="dates" class="form-control" value="<?php echo $fetch_products['harvest_date']; ?>">
                    </div>
                    <div class="mb-3">
        <label for="update_images" class="form-label">Product Images</label>
        <input type="file" name="update_images[]" class="form-control" multiple>
        <input type="hidden" name="update_old_images" value="<?php echo $fetch_products['images']; ?>">
    </div>
                    
                    <div class="d-grid">
                        <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
}
?>

<!-- JavaScript to toggle the harvest date input -->
<script>
function toggleDateInput() {
    var harvestStatus = document.getElementById('harvestStatus').value;
    var dateInputDiv = document.getElementById('dateInputDiv');
    var harvestDateInput = document.getElementById('harvestDateInput');

    if (harvestStatus === 'ready_to_harvest') {
        dateInputDiv.style.display = 'block';
    } else {
        dateInputDiv.style.display = 'none';
        harvestDateInput.value = '';
    }
}

// Initialize the toggle state on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleDateInput();
});
</script>

<?php
@include("admin_footer.php");
?>
