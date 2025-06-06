<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:landing-page.php');
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
    $product_sizes = $_POST['product_sizes'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    // Get product details including available kilo
    $product_query = mysqli_query($conn, "SELECT admin_id, available_kilo FROM `products` WHERE name = '$product_name'") or die('query failed');
    $product_data = mysqli_fetch_assoc($product_query);

    if (!$product_data) {
        die('Product not found');
    }

    $product_admin_id = $product_data['admin_id'];
    $available_kilo = $product_data['available_kilo'];

    // Check if the product is out of stock
    if ($available_kilo <= 0) {
        // echo "<script>alert('You cannot add this product to the cart because it is out of stock.');</script>";
        $message_add[] = 'You cannot add this product to the cart because it is out of stock.';
    } elseif ($product_quantity > $available_kilo) {
        // echo "<script>alert('The quantity you selected exceeds the available stock.');</script>";
        $message_add[] = 'The quantity you selected exceeds the available stock.';
    } else {
        // Check if the product is already in the cart
        $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
        if (mysqli_num_rows($check_cart_numbers) > 0) {
            // echo "<script>alert('Product already added to cart!');</script>";
            $message[] = 'Product already added to cart!';
        } else {
            mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image, admin_id,sizes) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image', '$product_admin_id','$product_sizes')") or die('query failed');
            // echo "<script>alert('Product added to cart!');</script>";
            $message[] = 'Product added to cart!';
        }
    }
}



$show_all_products = true;

if (basename($_SERVER['PHP_SELF']) != 'index.php') {
    $show_all_products = false;
}

?>
<?php @include("header.php") ?>
<!-- Menu Section -->
<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo "
        <div class='message-box' style='background: #efdece; color: green;margin:0 auto;'>
            <span>{$msg}</span>
            <i class='fas fa-times close-btn'></i>
        </div>";
    }
} elseif (isset($message_add)) {
    foreach ($message_add as $msg_add) {
        echo "
        <div class='message-box' style='background: #cfedd6; color: orange;margin:0 auto;'>
            <span>{$msg_add}</span>
            <i class='fas fa-times close-btn'></i>
        </div>";
    }
}
?>
<section id="menu" class="menu section">

<!-- Section Title -->
<div class="container section-title" data-aos="fade-up">
  <p><span>Recommended</span> <span class="description-title">Product</span></p>
</div><!-- End Section Title -->

<div class="container">

  <ul class="nav nav-tabs d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">

  <div class="row gy-5">

            <div class="container">
            <div class="row row-cols-1 row-cols-md-3">
            <?php
        $select_products = mysqli_query($conn, "SELECT products.*, users.name AS admin_name, users.image AS admin_image FROM `products` LEFT JOIN `users` ON products.admin_id = users.id WHERE  products.status = 'approved'") or die('query failed');
        
        $products = [];
        if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                $products[] = $fetch_products;
            }
            
            // Shuffle the products array
            shuffle($products);

            // Display the shuffled products, limiting to 5
            $products = array_slice($products, 0, 9);

            foreach ($products as $fetch_products) {
                $admin_image = $fetch_products['admin_image'] ? 'images/' . $fetch_products['admin_image'] : 'images/default-avatar.png';
                // $date = date('F d, Y h:i A', strtotime($fetch_products['harvest_date']['havest_status'] == 'ready_to_harvest') );
                // $date1 = date('F d, Y ', strtotime($fetch_products['harvest_date']['havest_status'] == 'harvest'));
                $date3 = date('F d, Y h:i A', strtotime($fetch_products['harvest_date']));
                $date4 = date('F d, Y ', strtotime($fetch_products['created_at']));



                // Check if the user has blocked the product's admin
        $admin_id = $fetch_products['admin_id'];
        $check_block_status = mysqli_query($conn, "SELECT * FROM `reports` WHERE reporter_id = '$user_id' AND reported_user_id = '$admin_id' AND blocked = 1") or die('Query failed');
        $is_admin_blocked = mysqli_num_rows($check_block_status) > 0;
    ?>
                <div class="col mb-4">
    <div class="card">
        <form action="" method="post">
            <?php 
                // Explode the images to handle multiple images
                $images = explode(',', $fetch_products['images']);
                $main_image = isset($images[0]) ? $images[0] : 'default-image.jpg'; // Fallback to default image if no image found
            ?>
            <img src="images/<?php echo $main_image; ?>" class="card-img-top" alt="<?php echo $fetch_products['name']; ?>" style="height: 52vh;" data-bs-toggle="modal" data-bs-target="#productModal<?php echo $fetch_products['id']; ?>">
            
            <div class="card-body text-center">
                <h3><?php echo $fetch_products['name']; ?></h3>
                <p class="p">₱<?php echo $fetch_products['price']; ?> </p>
                
                <!-- <p class="p">Status: <?php echo $fetch_products['havest_status']?> </p> -->
                <?php if ($fetch_products['havest_status'] == 'ready_to_harvest') { ?>
                    <p class="p">Status: Ready to Harvest </p>
                    <p class="p">Ready to Harvest: <?php echo $date3; ?></p>
                <?php } elseif ($fetch_products['havest_status'] == 'harvested') { ?>
                    <p class="p">Status: Harvested </p>
                    <p class="p">Harvested: <?php echo $date4; ?></p>
                <?php } else  { ?>
                    <p class="p">Status: Ready to pick up </p>
                    <p class="p">Ready to pick up: <?php echo $date4; ?></p>
                <?php } ?>
               
                <?php
                  if($fetch_products['available_kilo'] == 0){
                      ?>
                         <p class="p">Available <?php echo $fetch_products['sizes']; ?>: Out of Stock</p>
                      <?php
                  }elseif($fetch_products['available_kilo'] <= 20 && $fetch_products['available_kilo'] >= 1 ){
                      ?>
                         <p class="p">Available <?php echo $fetch_products['sizes']; ?>: <?php echo $fetch_products['available_kilo']; ?><?php echo $fetch_products['sizes']; ?> limited stock</p>
                      <?php
                  }else{
                      ?>
                        <p class="p">Available <?php echo $fetch_products['sizes']; ?>: <?php echo $fetch_products['available_kilo']; ?><?php echo $fetch_products['sizes']; ?></p>
                      <?php
                  }
               ?>
                
                <p class="d-inline-block text-truncate" style="width: 90%;">Description: <?php echo $fetch_products['descriptions']; ?></p>
                
                <div class="mb-3">
                    <input type="number" min="1" name="product_quantity" value="1" class="form-control text-center" style="width: 100px; margin: 0 auto;">
                </div>

                <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                <input type="hidden" name="product_sizes" value="<?php echo $fetch_products['sizes']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $main_image; ?>">
                <?php if ($is_admin_blocked) { ?>
                            <input type="submit" value="Blocked" class="btn btn-warning btn-block" disabled>
                        <?php } else { ?>
                            <input type="submit" value="Add to Cart" name="add_to_cart" class="btn btn-success btn-block">
                        <?php } ?>
                <!-- <input type="submit" value="Add to Cart" name="add_to_cart" class="btn btn-success btn-block"> -->
            </div>
        </form>
    </div>
    
    <!-- Modal for viewing product images -->
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
                                    <img src="images/<?php echo $image; ?>" class="d-block w-100" alt="<?php echo $fetch_products['name']; ?>" style="height: 52vh;">
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
                        <div class="card-body text-center">
                    <h3><?php echo $fetch_products['name']; ?></h3>
                    <p class="p">₱<?php echo $fetch_products['price']; ?></p>
                    
                    <p class="p">Status: <?php echo ($fetch_products['havest_status'] == 'ready_to_harvest') ? 'Ready to Harvest' : 'Harvested'; ?></p>
                    <?php if ($fetch_products['havest_status'] == 'ready_to_harvest') { ?>
                        <p class="p">Ready to Harvest: <?php echo $date3; ?></p>
                    <?php } else { ?>
                        <p class="p">Harvested: <?php echo $date4; ?></p>
                    <?php } ?>
                    
                    <?php
                  if($fetch_products['available_kilo'] == 0){
                      ?>
                         <p class="p">Available <?php echo $fetch_products['sizes']; ?>: Out of Stock</p>
                      <?php
                  }elseif($fetch_products['available_kilo'] <= 20 && $fetch_products['available_kilo'] >= 1 ){
                      ?>
                         <p class="p">Available <?php echo $fetch_products['sizes']; ?>: <?php echo $fetch_products['available_kilo']; ?><?php echo $fetch_products['sizes']; ?> limited stock</p>
                      <?php
                  }else{
                      ?>
                        <p class="p">Available <?php echo $fetch_products['sizes']; ?>: <?php echo $fetch_products['available_kilo']; ?><?php echo $fetch_products['sizes']; ?></p>
                      <?php
                  }
               ?>
                    
                    <p class="p">Description: <?php echo $fetch_products['descriptions']; ?></p>
                    
                    <!-- <div class="mb-3">
                        <input type="number" min="1" name="product_quantity" value="1" class="form-control text-center" style="width: 100px; margin: 0 auto;">
                    </div> -->

                    <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                    <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                    <input type="hidden" name="product_image" value="<?php echo $main_image; ?>">
                    <!-- <?php if ($is_admin_blocked) { ?>
                        <input type="submit" value="Blocked" class="btn btn-warning btn-block" disabled>
                    <?php } else { ?>
                        <input type="submit" value="Add to Cart" name="add_to_cart" class="btn btn-success btn-block" onclick="return confirm('Are you sure you want to buy this?');">
                    <?php } ?> -->
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

                
                <?php
                    }
                } else {
                    echo '<p class="empty">no products added yet!</p>';
                }
            ?>
                </div>
            </div>

      </div>

  </ul>

</div>

</section><!-- /Menu Section -->
<style>
    .message-box {
    padding: 10px;
    margin: 10px 0;
    text-align: end;
    border-radius: 5px;
    position: fixed;
    top: 20px;
    right: 0;
    transform: translateX(-50%);
    z-index: 1000;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

.close-btn {
    margin-left: 10px;
    cursor: pointer;
}
.p{
        margin: 1px 0;
}
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messages = document.querySelectorAll('.message-box');

        messages.forEach(function(message) {
            // Fade in effect
            message.style.opacity = '0';
            message.style.transition = 'opacity 1s ease';
            setTimeout(function() {
                message.style.opacity = '1';
            }, 100); // Delay to start the fade-in effect

            // Fade out after 3 seconds and then remove the message
            setTimeout(function() {
                message.style.opacity = '0';
                setTimeout(function() {
                    message.remove();
                }, 1000); // Delay before removing the message element
            }, 3000); // Delay before starting the fade-out
        });

        // Close button functionality
        const closeButtons = document.querySelectorAll('.close-btn');
        closeButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const parent = btn.parentElement;
                parent.style.opacity = '0';
                setTimeout(function() {
                    parent.remove();
                }, 1000); // Same fade-out effect
            });
        });
    });
</script>