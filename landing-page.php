<?php
include 'config.php';



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>FSC</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Amatic+SC:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <!-- =======================================================
  * Template Name: Yummy
  * Template URL: https://bootstrapmade.com/yummy-bootstrap-restaurant-website-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page" style="overflow-x:hidden">


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
    
     
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php" class="active">Home<br></a></li>
          <li><a href="register.php">Register</a></li>
          <li><a href="login.php">Login<span></span></a></li>
    
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

   
      
    </div>
  </header>
  






  <main class="main">

 


<!-- Hero Section -->
<section id="hero" class="hero section light-background">

  <div class="container">
    <div class="row gy-4 justify-content-center justify-content-lg-between align-items-center">
      <div class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center text-center text-lg-left">
        <h1 data-aos="fade-up">From Farm Fresh Veggies<br>To the Finest Seafood</h1>
        <p data-aos="fade-up" data-aos-delay="100">Experience the true taste of nature with our locally sourced vegetables and premium seafood. Brought to you by dedicated farmers and fishermen who care about quality.</p>
        <div class="d-flex flex-column flex-md-row justify-content-center justify-content-lg-start" data-aos="fade-up" data-aos-delay="200">
          <a href="login.php" class="btn-get-started">Get Started</a>
          <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox btn-watch-video d-flex align-items-center mt-3 mt-md-0 ml-md-3"><i class="bi bi-play-circle"></i><span>Watch Our Journey</span></a>
        </div>
      </div>
      <div class="col-lg-5 order-1 order-lg-2 hero-img" data-aos="zoom-out">
        <img src="images/1.jpg" class="img-fluid animated" alt="Farm fresh vegetables and seafood">
      </div>
    </div>
  </div>
</section>
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



        //         // Check if the user has blocked the product's admin
        // $admin_id = $fetch_products['admin_id'];
        // $check_block_status = mysqli_query($conn, "SELECT * FROM `reports` WHERE reporter_id = '$user_id' AND reported_user_id = '$admin_id' AND blocked = 1") or die('Query failed');
        // $is_admin_blocked = mysqli_num_rows($check_block_status) > 0;
    ?>
                <div class="col mb-4">
    <div class="card">
        <form action="" method="post">
            <?php 
                // Explode the images to handle multiple images
                $images = explode(',', $fetch_products['images']);
                $main_image = isset($images[0]) ? $images[0] : 'default-image.jpg'; // Fallback to default image if no image found
            ?>
            <img src="images/<?php echo $main_image; ?>" class="card-img-top" alt="<?php echo $fetch_products['name']; ?>" style="height: 52vh;" >
            
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
                
                            <button type="button" value="Add to Cart" name="add_to_cart" class="btn btn-success btn-block" data-bs-toggle="modal" data-bs-target="#productModal<?php echo $fetch_products['id']; ?>">Add to Cart</button>
                        
                <!-- <input type="submit" value="Add to Cart" name="add_to_cart" class="btn btn-success btn-block"> -->
            </div>
        </form>
    </div>
    
    <!-- Modal for viewing product images -->
    <div class="modal fade" id="productModal<?php echo $fetch_products['id']; ?>" tabindex="-1" aria-labelledby="productModalLabel<?php echo $fetch_products['id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header text-dark">
                <h5 class="modal-title" id="productModalLabel<?php echo $fetch_products['id']; ?>">
                    <?php echo $fetch_products['name']; ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center">
                    <p class="lead mb-4">If you want to buy, please register first.</p>
                    <button name="report_user" class="btn btn-primary btn-lg px-5">
                        <a href="register.php" class="text-white text-decoration-none">Register</a>
                    </button>
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

</section>


  </main>
  
<footer id="footer" class="footer dark-background">

<div class="container">
  <div class="row gy-3">
    <div class="col-lg-3 col-md-6 d-flex">
      <i class="bi bi-geo-alt icon"></i>
      <div class="address">
        <h4>Address</h4>
        <p>Buenavista Bohol</p>
        <p>Buenavista Community College</p>
        <p></p>
      </div>

    </div>

    <div class="col-lg-3 col-md-6 d-flex">
      <i class="bi bi-telephone icon"></i>
      <div>
        <h4>Contact</h4>
        <p>
          <strong>Phone:</strong> <span>+1 5589 55488 55</span><br>
          <strong>Email:</strong> <span>fsc-onnect@gmail.com</span><br>
        </p>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 d-flex">
      <i class="bi bi-clock icon"></i>
      <div>
        <h4>Opening Hours</h4>
        <p>
          <strong>Mon-Sat:</strong> <span>11AM - 23PM</span><br>
          <strong>Sunday</strong>: <span>Closed</span>
        </p>
      </div>
    </div>

    <div class="col-lg-3 col-md-6">
      <h4>Follow Us</h4>
      <div class="social-links d-flex">
  
        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
        <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
        <a href="#" class="linkedin"><i class="bi bi-youtube"></i></a>
      </div>
    </div>

  </div>
</div>

<div class="container copyright text-center mt-4">
  <p>© <span>Copyright</span> <strong class="px-1 sitename">FCs-Connect</strong> <span>All Rights Reserved</span></p>
  <div class="credits">
    <!-- All the links in the footer should remain intact. -->
    <!-- You can delete the links only if you've purchased the pro version. -->
    <!-- Licensing information: https://bootstrapmade.com/license/ -->
    <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
    Developed by <a href="#">Gilmar Aparece</a>
  </div>
</div>

</footer>

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Preloader -->
<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

<!-- Main JS File -->
<script src="assets/js/main.js"></script>
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
</body>

</html>
