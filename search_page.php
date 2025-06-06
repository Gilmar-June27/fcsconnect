<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:landing-page.php');
    exit();
}

if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
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
            mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image, admin_id) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image', '$product_admin_id')") or die('query failed');
            // echo "<script>alert('Product added to cart!');</script>";
            $message[] = 'Product added to cart!';
        }
    }
}

if (isset($_POST['submit'])) {
    $search_item = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['search']));

    // Store search history
    $insert_history_query = "INSERT INTO `search_history` (user_id, search_term) VALUES ('$user_id', '$search_item')";
    mysqli_query($conn, $insert_history_query) or die('Query failed');

    // Search products and users
    $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%$search_item%'") or die('Query failed');
    $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE name LIKE '%$search_item%' OR username LIKE '%$search_item%'") or die('Query failed');
}

if (isset($_POST['delete_history'])) {
    $history_id = mysqli_real_escape_string($conn, $_POST['history_id']);
    mysqli_query($conn, "DELETE FROM `search_history` WHERE id = '$history_id' AND user_id = '$user_id'") or die('Query failed');
}

if (isset($_POST['all_delete_history'])) {
    mysqli_query($conn, "DELETE FROM `search_history` WHERE user_id = '$user_id'") or die('Query failed');
}
?>

<!-- Include header -->
<?php include("header.php"); ?>
<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
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
              <li><a href="logout.php">Logout</a></li>
            </ul>
          </li> 
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

   
      
    </div>
  </header>
<!-- Display messages -->
<?php if (isset($message)): ?>
    <?php foreach ($message as $msg): ?>
        <div class='message-box' style='background: #efdece; color: orange;'>
            <span><?= $msg; ?></span>
            <i class='fas fa-times close-btn'></i>
        </div>
    <?php endforeach; ?>
<?php elseif (isset($message_add)): ?>
    <?php foreach ($message_add as $msg_add): ?>
        <div class='message-box' style='background: #cfedd6; color: green;'>
            <span><?= $msg_add; ?></span>
            <i class='fas fa-times close-btn'></i>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Search section -->
<div class="container my-5">
   

    <div class="container section-title" data-aos="fade-up">
        <p><span>Search</span> <span class="description-title">Page</span></p>
    </div>

    <div class="d-flex justify-content-center mb-4">
        <form action="" method="post" class="d-flex w-100" style="max-width: 600px;">
            <input type="text" name="search" id="search" class="form-control me-2" placeholder="Search products or users..." autocomplete="off">
            <button type="submit" name="submit" class="btn btn-outline-primary">Search</button>
        </form>
        <div id="suggestions" class="position-absolute bg-white border rounded shadow-lg" style="width: 100%; max-width: 600px; display: none;top: 320px;z-index:1000;"></div>
    </div>

    <!-- Search results -->
    <div class="row">
        <div class="col-12">
            <?php if (isset($_POST['submit'])): ?>
                <?php if (mysqli_num_rows($select_products) > 0 || mysqli_num_rows($select_users) > 0): ?>
                    <div class="row">
                        <?php while ($fetch_product = mysqli_fetch_assoc($select_products)): 
                         $admin_image = $fetch_product['image'] ? 'images/' . $fetch_product['image'] : 'images/default-avatar.png';
                                      
                            ?>
                                 <div class="col-md-4 mb-4">
    <div class="card">
        <form action="" method="post">
            <?php 
                // Explode the images to handle multiple images
                $images = explode(',', $fetch_product['images']);
                $main_image = isset($images[0]) ? $images[0] : 'default-image.jpg'; // Fallback to default image if no image found
            ?>
            <img src="images/<?php echo $main_image; ?>" class="card-img-top" alt="<?php echo $fetch_product['name']; ?>" style="height: 52vh;" data-bs-toggle="modal" data-bs-target="#productModal<?php echo $fetch_product['id']; ?>">
            
            <div class="card-body text-center">
                <h3><?php echo $fetch_product['name']; ?></h3>
                <p>₱<?php echo $fetch_product['price']; ?> </p>
                
                <p>Status: <?php echo ($fetch_product['havest_status'] == 'ready_to_harvest') ? 'Ready to Harvest' : 'Harvested'; ?> </p>
                <!-- <?php if ($fetch_product['havest_status'] == 'ready_to_harvest') { ?>
                    <p>Ready to Harvest: <?php echo $date3; ?></p>
                <?php } else { ?>
                    <p>Harvested: <?php echo $date; ?></p>
                <?php } ?> -->
               
                <p>Available kilo: <?php echo $fetch_product['available_kilo']; ?>kg</p>
                <p>Description: <?php echo $fetch_product['descriptions']; ?></p>
                
                <div class="mb-3">
                    <input type="number" min="1" name="product_quantity" value="1" class="form-control text-center" style="width: 100px; margin: 0 auto;">
                </div>

                <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $main_image; ?>">

                <input type="submit" value="Add to Cart" name="add_to_cart" class="btn btn-outline-success btn-block">
            </div>
        </form>
    </div>
    
    <!-- Modal for viewing product images -->
    <div class="modal fade" id="productModal<?php echo $fetch_product['id']; ?>" tabindex="-1" aria-labelledby="productModalLabel<?php echo $fetch_product['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel<?php echo $fetch_product['id']; ?>"><?php echo $fetch_product['name']; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="carouselExample<?php echo $fetch_product['id']; ?>" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $active = 'active';
                            foreach ($images as $image) {
                                ?>
                                <div class="carousel-item <?php echo $active; ?>">
                                    <img src="images/<?php echo $image; ?>" class="d-block w-100" alt="<?php echo $fetch_product['name']; ?>" style="height: 52vh;">
                                </div>
                                <?php
                                $active = ''; // Remove active after the first image
                            }
                            ?>
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample<?php echo $fetch_product['id']; ?>" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample<?php echo $fetch_product['id']; ?>" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                        <?php endwhile; ?>

                        <!-- Display users if any -->
                        <?php if (mysqli_num_rows($select_users) > 0): ?>
                            <div class="col-12 mt-4">
                                
                                <div class="row">
                                    <?php while ($fetch_user = mysqli_fetch_assoc($select_users)): 
                                          $admin_image = $fetch_user['image'] ? 'images/' . $fetch_user['image'] : 'images/default-avatar.png';
                                        ?>
                                        <div class="col-md-4 mb-4">
                                            <div class="card">
                                                <img src="<?php echo $admin_image; ?>" class="card-img-top" alt="User Image" style="height: 52vh;">
                                                <div class="card-body">
                                                <!-- <p><a href="users.php?user_id=<?= $fetch_user['id']; ?>"><?= $fetch_user['name']; ?></a></p> -->
                                                <a href="admin-sale_product.php?admin_id=<?= $fetch_user['id']; ?>"><?= $fetch_user['name']; ?></a>
                                                    <p class="card-text">Username: <?= $fetch_user['username']; ?></p>
                                                    <p class="card-text">Email: <?= $fetch_user['email']; ?></p>
                                                    <a href="chatsystem.php?admin_id=<?php echo $fetch_user['id']; ?>&scroll_to_user=<?php echo $fetch_user['id']; ?>" class="btn btn-primary">Message</a>
                                                    <!-- <form action="" method="post">
                        <input type="hidden" name="reported_user_id" value="<?php echo $fetch_user['id']; ?>">
                        <button type="submit" name="unblock_user" class="btn btn-warning" <?php echo !$can_unblock ? 'disabled' : ''; ?>>Unblock</button>
                        <?php if (!$can_unblock) { ?>
                            <p class="text-danger">You can unblock this user after <?php echo $remaining_days; ?> day(s).</p>
                        <?php } else { ?>
                            <p class="text-success">You can now unblock this user.</p>
                        <?php } ?>
                    </form> -->
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted">No results found!</p>
                <?php endif; ?>
            <?php else: ?>
                <p class="text-center text-muted">Search something!</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Search history section -->
<div class="mt-4">
    <h4>Search History</h4>
    <form action="" method="post" class="mb-3">
        <button type="submit" name="all_delete_history" class="btn btn-outline-danger">Delete All</button>
    </form>
    <?php
    $select_history = mysqli_query($conn, "SELECT * FROM `search_history` WHERE user_id = '$user_id' ORDER BY search_time DESC LIMIT 10") or die('Query failed');
    if (mysqli_num_rows($select_history) > 0):
        while ($fetch_history = mysqli_fetch_assoc($select_history)):
    ?>
            <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                <p class="mb-0 history-item" data-search-term="<?= htmlspecialchars($fetch_history['search_term']); ?>"><?= htmlspecialchars($fetch_history['search_term']); ?></p>
                <form action="" method="post" class="mb-0">
                    <input type="hidden" name="history_id" value="<?= $fetch_history['id']; ?>">
                    <button type="submit" name="delete_history" class="btn btn-outline-danger btn-sm">Delete</button>
                </form>
            </div>
    <?php
        endwhile;
    else:
        echo '<p class="text-center text-muted">No search history found.</p>';
    endif;
    ?>
</div>

</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Existing search functionality

    // New functionality for search history click
    $(document).on('click', '.history-item', function() {
        let searchTerm = $(this).data('search-term');
        $('#search').val(searchTerm);
        $('form').submit(); // Submit the form to perform the search
    });

    // Existing AJAX search suggestions
});



$(document).ready(function() {
    $('#search').on('keyup', function() {
        let query = $(this).val();
        if (query.length > 0) {
            $.ajax({
                url: 'search_suggestions.php',
                method: 'POST',
                data: { query: query },
                success: function(data) {
                    let suggestions = JSON.parse(data);
                    let suggestionsList = '';
                    suggestions.forEach(function(item) {
                        suggestionsList += '<div class="d-flex align-items-center p-2 suggestion-item">';
                        suggestionsList += '<img src="' + item.image + '" class="rounded-circle me-2" style="width: 30px; height: 30px;">';
                        suggestionsList += '<a href="#" class="text-decoration-none">' + item.name + '</a>';
                        suggestionsList += '</div>';
                    });
                    $('#suggestions').html(suggestionsList).show();
                }
            });
        } else {
            $('#suggestions').hide();
        }
    });

    $(document).on('click', '.suggestion-item', function() {
        $('#search').val($(this).text());
        $('#suggestions').hide();
    });
});
</script>

<?php include("footer.php"); ?>
