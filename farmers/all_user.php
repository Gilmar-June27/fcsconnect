<?php
include '../config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../login.php');
    exit();
}

// if (isset($_POST['add_to_cart'])) {
//     $product_name = $_POST['product_name'];
//     $product_price = $_POST['product_price'];
//     $product_image = $_POST['product_image'];
//     $product_quantity = $_POST['product_quantity'];

//     // Get product details including available kilo
//     $product_query = mysqli_query($conn, "SELECT admin_id, available_kilo FROM `products` WHERE name = '$product_name'") or die('query failed');
//     $product_data = mysqli_fetch_assoc($product_query);

//     if (!$product_data) {
//         die('Product not found');
//     }

//     $product_admin_id = $product_data['admin_id'];
//     $available_kilo = $product_data['available_kilo'];

//     // Check if the product is out of stock
//     if ($available_kilo <= 0) {
//         // echo "<script>alert('You cannot add this product to the cart because it is out of stock.');</script>";
//         $message_add[] = 'You cannot add this product to the cart because it is out of stock.';
//     } elseif ($product_quantity > $available_kilo) {
//         // echo "<script>alert('The quantity you selected exceeds the available stock.');</script>";
//         $message_add[] = 'The quantity you selected exceeds the available stock.';
//     } else {
//         // Check if the product is already in the cart
//         $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
//         if (mysqli_num_rows($check_cart_numbers) > 0) {
//             // echo "<script>alert('Product already added to cart!');</script>";
//             $message[] = 'Product already added to cart!';
//         } else {
//             mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image, admin_id) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image', '$product_admin_id')") or die('query failed');
//             // echo "<script>alert('Product added to cart!');</script>";
//             $message[] = 'Product added to cart!';
//         }
//     }
// }




if(isset($_POST['send'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = $_POST['number'];
    $msg = mysqli_real_escape_string($conn, $_POST['message']);

    $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

    if(mysqli_num_rows($select_message) > 0){
       $message[] = 'message sent already!';
    } else {
       mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
       $message[] = 'message sent successfully!';
    }
}

?>

<?php @include("admin_header.php") ?>
<?php @include("admin_navbar.php") ?>
<section  class="slider section" data-builder="section" data-colorpreset="cp-dark-background" style="    background: rgb(219 213 213 / 10%);">
      <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <p><span>Buy</span> <span class="description-title">ers</span></p>
    </div><!-- End Section Title -->
  <div class="container section-title" data-aos="fade-up" data-builder="section-title">
 
  <div class="container">
  <div class="row flex-row flex-nowrap overflow-auto">
        <?php
            $select_products = mysqli_query($conn, "SELECT image, id AS user_id, name AS user_name , farmer_select FROM `users` WHERE user_type = 'user'") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
            $user_image = $fetch_products['image'] ? 'images/' . $fetch_products['image'] : 'images/default-avatar.png';
        ?>
            <div class="card custom-card" style="width: 55vh;margin-right: 15px;">
                  <img src="<?php echo $user_image; ?>" alt="user Image" class="card-img-top" alt="..." style="height: 52vh;">
                  <div class="card-body">
                      <h3><a href="admin_user.php?user_id=<?php echo $fetch_products['user_id']; ?>"><?php echo $fetch_products['user_name']; ?></a></h3>
                      <h5 class="card-text"><?php echo $fetch_products['farmer_select']; ?></h5>
                  </div>
              
            </div>
        <?php
          }
        } else {
            echo '<p class="empty">no farmers added yet!</p>';
        }
        ?>
  </div>
</div>

</section>
<style>
    /* width */
::-webkit-scrollbar {
  width: 0px;
}
</style>
<?php @include("admin_footer.php") ?>
