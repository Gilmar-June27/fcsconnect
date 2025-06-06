<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:landing-page.php');
}

// if(isset($_POST['update_cart'])){
//    $cart_id = $_POST['cart_id'];
//    $cart_quantity = $_POST['cart_quantity'];
//    mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
//    $message[] = 'cart quantity updated!';
// }
if (isset($_POST['update_cart'])) {
   $cart_id = $_POST['cart_id'];
   $cart_quantity = $_POST['cart_quantity'];

   // Get the product details associated with the cart item, including available stock
   $cart_query = mysqli_query($conn, "SELECT p.available_kilo FROM `cart` c JOIN `products` p ON c.name = p.name WHERE c.id = '$cart_id'") or die('query failed');
   $product_data = mysqli_fetch_assoc($cart_query);

   if ($product_data) {
       $available_kilo = $product_data['available_kilo'];

       // Check if the quantity exceeds the available stock
       if ($cart_quantity > $available_kilo) {
           $message[] = 'The quantity you selected exceeds the available stock.';
       } else {
           // Proceed with updating the cart
           mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
           $message[] = 'Cart quantity updated!';
       }
   } else {
       $message[] = 'Product not found!';
   }
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
   $message_add[] = 'deleted successfully!';
   
}

if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   $message_add[] = 'deleted all successfully!';
   
}

?>
<?php @include("header.php")   ?>
<?php @include("navbar.php")   ?>


  
<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo "
        <div class='message-box' style='background: #efdece; color: green;'>
            <span>{$msg}</span>
            <i class='fas fa-times close-btn'></i>
        </div>";
    }
} elseif (isset($message_add)) {
    foreach ($message_add as $msg_add) {
        echo "
        <div class='message-box' style='background: #cfedd6; color: orange;'>
            <span>{$msg_add}</span>
            <i class='fas fa-times close-btn'></i>
        </div>";
    }
}
?>

<div class="container">

   <!-- Section Title -->
   <div class="container section-title" data-aos="fade-up">
  <p><span>Products</span> <span class="description-title">Added</span></p>
</div><!-- End Section Title -->

<section class="display-order mb-4">
   <div class="container">
      <div class="table-responsive">
         <table class="table table-striped table-bordered">
            <thead>
               <tr>
                  <th scope="col">Product Image</th>
                  <th scope="col">Product Name</th>
                  <th scope="col">Price</th>
                  <th scope="col">Quantity</th>
                  <th scope="col">Sub Total</th>
                  <th scope="col">Action</th>
               </tr>
            </thead>
            <tbody>
            <?php
               $grand_total = 0;
               $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
               if(mysqli_num_rows($select_cart) > 0){
                  while($fetch_cart = mysqli_fetch_assoc($select_cart)){   
            ?>
               <tr>
                  <td><img src="images/<?php echo $fetch_cart['image']; ?>" alt="" style="width: 100px;"></td>
                  <td><?php echo $fetch_cart['name']; ?></td>
                  <td>₱<?php echo $fetch_cart['price']; ?></td>
                  <td>
                     <form action="" method="post">
                        <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                        <div class="d-flex" style="display: flex;
    align-items: center;">
                        <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>" style="border:    -moz-appearance: textfield; border: none;outline: none; width: 31px; background: transparent;" class="form-control transparent-input"><?php echo $fetch_cart['sizes']; ?>
                        <input type="submit" name="update_cart" value="Update" class="btn btn-outline-success my-2 my-sm-0 m-2">
                        </div>
                     </form>
                  </td>
                  <td>₱<?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?></td>
                  <td><a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>"   onclick="return confirm('Delete this from cart?');" class="btn btn-outline-danger my-2 my-sm-0 m-2 ">Delete</a></td>
               </tr>
            <?php
            $grand_total += $sub_total;
               }
            }else{
               echo '<tr><td colspan="6" class="empty text-center">Your cart is empty</td></tr>';
            }
            ?>
            </tbody>
         </table>
      </div>
   </div>

   <div style="margin-top: 2rem; text-align:center;">
      <a href="cart.php?delete_all" class="btn btn-outline-danger my-2 my-sm-0 m-2  <?php echo ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('Delete all from cart?');">Delete All</a>
   </div>

   <div class="cart-total ">
      <p>Grand total : <span style="font-size: 1.9rem; font-weight: 900;">₱<?php echo $grand_total; ?></span></p>
      <div class="flex d-flex justify-content-center m-2">
         <a href="browse_products.php" class="btn btn-outline-warning my-2 my-sm-0 m-2">Continue Shopping</a>
         <a href="checkout.php"  class="btn btn-outline-success my-2 my-sm-0 m-2 <?php echo ($grand_total > 1)?'':'disabled'; ?>">Proceed to Checkout</a>
      </div>
   </div>
   </section>

   </div>
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


input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
  border:none;
  outline:none;
}
</style>
<?php @include("footer.php")   ?>