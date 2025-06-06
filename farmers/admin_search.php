<?php
include '../config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../login.php');
    exit();
}

if (isset($_POST['add_to_cart'])) {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
    $product_image = mysqli_real_escape_string($conn, $_POST['product_image']);
    $product_quantity = mysqli_real_escape_string($conn, $_POST['product_quantity']);

    // Get the admin_id of the new product
    $product_query = mysqli_query($conn, "SELECT admin_id FROM `products` WHERE name = '$product_name'") or die('Query failed');
    $product_data = mysqli_fetch_assoc($product_query);

    if ($product_data) {
        $product_admin_id = $product_data['admin_id'];

        // Check if the product is already in the cart
        $check_cart_query = "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'";
        $check_cart = mysqli_query($conn, $check_cart_query) or die('Query failed');
        
        if (mysqli_num_rows($check_cart) > 0) {
            $message[] = 'Product already added to cart!';
        } else {
            $insert_cart_query = "INSERT INTO `cart` (user_id, name, price, quantity, image, admin_id) VALUES ('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image', '$product_admin_id')";
            mysqli_query($conn, $insert_cart_query) or die('Query failed');
            $message_add[] = 'Product added to cart!';
        }
    } else {
        die('Product not found');
    }
}

if (isset($_POST['submit'])) {
    $search_item = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['search']));

    // Store search history
    $insert_history_query = "INSERT INTO `search_history` (search_term) VALUES ( '$search_item')";
    mysqli_query($conn, $insert_history_query) or die('Query failed');

    // Search products and users
    $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%$search_item%'") or die('Query failed');
    $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE name LIKE '%$search_item%' OR username LIKE '%$search_item%'") or die('Query failed');
}

if (isset($_POST['delete_history'])) {
    $history_id = mysqli_real_escape_string($conn, $_POST['history_id']);
    mysqli_query($conn, "DELETE FROM `search_history` WHERE id = '$history_id' ") or die('Query failed');
}

if (isset($_POST['all_delete_history'])) {
    mysqli_query($conn, "DELETE FROM `search_history`") or die('Query failed');
}


$select = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id'") or die('query failed');
if(mysqli_num_rows($select) > 0){
   $fetch = mysqli_fetch_assoc($select);
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
   

    

    <div class="d-flex justify-content-center mb-4">
        <form action="" method="post" class="d-flex w-100" style="max-width: 600px;">
            <input type="text" name="search" id="search" class="form-control me-2" placeholder="Search Buyers..." autocomplete="off">
            <button type="submit" name="submit" class="btn btn-primary">Search</button>
        </form>
        <div id="suggestions" class="position-absolute bg-white border rounded shadow-lg" style="width: 100%; max-width: 600px; display: none;top: 266px;z-index:1000;"></div>
    </div>

    <!-- Search results -->
    <div class="row">
        <div class="col-12">
            <?php if (isset($_POST['submit'])): ?>
                <?php if (mysqli_num_rows($select_products) > 0 || mysqli_num_rows($select_users) > 0): ?>
                    <div class="row">
                        

                        <!-- Display users if any -->
                        <?php if (mysqli_num_rows($select_users) > 0): ?>
                            <div class="col-12 mt-4">
                                
                                <div class="row">
                                    <?php while ($fetch_user = mysqli_fetch_assoc($select_users)): ?>
                                        <div class="col-md-4 mb-4">
                                            <div class="card">
                                                <img src="images/<?= $fetch_user['image']; ?>" class="card-img-top" alt="User Image" style="height: 52vh;">
                                                <div class="card-body">
                                                <p><a href="users.php?user_id=<?= $fetch_user['id']; ?>"><?= $fetch_user['name']; ?></a></p>
                                                    <p class="card-text">Username: <?= $fetch_user['username']; ?></p>
                                                    <p class="card-text">Email: <?= $fetch_user['email']; ?></p>
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
        <button type="submit" name="all_delete_history" class="btn btn-danger">Delete All</button>
    </form>
    <?php
    $select_history = mysqli_query($conn, "SELECT * FROM `search_history`  ORDER BY search_time DESC LIMIT 10") or die('Query failed');
    if (mysqli_num_rows($select_history) > 0):
        while ($fetch_history = mysqli_fetch_assoc($select_history)):
    ?>
            <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                <p class="mb-0 history-item" data-search-term="<?= htmlspecialchars($fetch_history['search_term']); ?>"><?= htmlspecialchars($fetch_history['search_term']); ?></p>
                <form action="" method="post" class="mb-0">
                    <input type="hidden" name="history_id" value="<?= $fetch_history['id']; ?>">
                    <button type="submit" name="delete_history" class="btn btn-danger btn-sm">Delete</button>
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
                        suggestionsList += '<img src="images/' + item.image + '" class="rounded-circle me-2" style="width: 30px; height: 30px;">';
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

<?php include("admin_footer.php"); ?>
