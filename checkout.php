<?php
include 'config.php';
session_start();

// Check if user is logged in
$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('Location: landing-page.php');
    exit;
}




// Fetch user information
$user_query = mysqli_query($conn, "SELECT name, email, number, address FROM `users` WHERE id = '$user_id'");
if (!$user_query) {
    die('User query failed: ' . mysqli_error($conn));
}
$user_data = mysqli_fetch_assoc($user_query);

$name = $user_data['name'] ?? '';
$email = $user_data['email'] ?? '';
$number = $user_data['number'] ?? '';
$address = $user_data['address'] ?? '';

// Handle order placement
// Handle order placement
if (isset($_POST['order_btn'])) {
    // Sanitize inputs
    $number = mysqli_real_escape_string($conn, $_POST['number'] ?? '');
    $method = mysqli_real_escape_string($conn, $_POST['method'] ?? '');
    $address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $admin_orders = [];
    $product_sales = [];
    $admin_ids = [];
    $total_quantity_sold = 0;
    $shipping_fee = 0; // Initialize shipping fee

    // Fetch cart items
    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'");
    if (!$cart_query) {
        die('Cart query failed: ' . mysqli_error($conn));
    }

    while ($cart_item = mysqli_fetch_assoc($cart_query)) {
        $product_name = $cart_item['name'] ?? '';
        $quantity = $cart_item['quantity'] ?? 0;
        $price = $cart_item['price'] ?? 0;
        $sub_total = $price * $quantity;

        $cart_total += $sub_total;

        // Fetch product information (admin_id, available_kilo, and image)
        $product_query = mysqli_query($conn, "SELECT id, admin_id, available_kilo, image FROM `products` WHERE name = '" . mysqli_real_escape_string($conn, $product_name) . "'");
        if (!$product_query) {
            die('Product query failed: ' . mysqli_error($conn));
        }

        if (mysqli_num_rows($product_query) > 0) {
            $product_data = mysqli_fetch_assoc($product_query);
            $product_id = $product_data['id'];
            $admin_id = $product_data['admin_id'] ?? null;
            $available_kilo = $product_data['available_kilo'] ?? 0;
            $image = $product_data['image'] ?? 'default_image.jpg'; // Fallback image

            // Check product availability
            if ($available_kilo >= $quantity) {
                // Track admin_id
                if ($admin_id !== null) {
                    $admin_ids[] = $admin_id;
                }

                // Calculate product sales
                if (!isset($product_sales[$product_name])) {
                    $product_sales[$product_name] = 0;
                }
                $product_sales[$product_name] += $quantity;
                $total_quantity_sold += $quantity;

                if (!isset($admin_orders[$admin_id])) {
                    $admin_orders[$admin_id] = [
                        'products' => [],
                        'total_price' => 0,
                        'images' => []
                    ];
                }

                $admin_orders[$admin_id]['products'][] = $product_name . ' (' . $quantity . ')';
                $admin_orders[$admin_id]['total_price'] += $sub_total;

                // Store the image directly linked to this product
                $admin_orders[$admin_id]['images'][] = $image;

                // Update the available kilo for the product
                $new_available_kilo = $available_kilo - $quantity;
                if ($new_available_kilo < 0) {
                    $new_available_kilo = 0;
                }
                $update_product_query = mysqli_query($conn, "UPDATE `products` SET available_kilo = '$new_available_kilo' WHERE id = '$product_id'");
                if (!$update_product_query) {
                    die('Failed to update available kilo: ' . mysqli_error($conn));
                }
            } else {
                $message[] = "Insufficient stock for product: $product_name.";
            }
        }
    }

    // Check if the cart is empty
    if ($cart_total == 0) {
        $message[] = 'Your cart is empty';
    } else {
        // Calculate shipping fee based on distinct admin IDs in the cart
        foreach (array_unique($admin_ids) as $admin_id) {
            $shipping_query = mysqli_query($conn, "SELECT price FROM `shipping_fee` WHERE admin_id = '$admin_id'");
            if (!$shipping_query) {
                die('Shipping query failed: ' . mysqli_error($conn));
            }

            while ($shipping_row = mysqli_fetch_assoc($shipping_query)) {
                $shipping_fee += (float)$shipping_row['price'];
            }
        }

        // Check if the user has scanned a QR code to apply a discount
        // $qr_code_value = 0.00;
        // if (!empty($admin_data['qr_code'])) {
        //     $qr_code_value = $cart_total / 2;  // Apply a 50% discount if QR code exists
        //     $cart_total = $cart_total - $qr_code_value;  // Adjust the total price after discount
        // }
$grand_total = 0;
        $qr_code_value = 0.00;
$admin_query = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id'");
$admin_data = mysqli_fetch_assoc($admin_query) ?? [];

if (!empty($admin_data['qr_code'])) {
    $qr_code_value = $grand_total / 2;  // Apply a 50% discount if QR code exists
    $grand_total -= $qr_code_value;  // Adjust the total price after discount
}

// Final total including shipping and QR discount
$final_total = $grand_total + $shipping_fee;

        // Calculate top sales
        arsort($product_sales);
        $top_sales_product_name = key($product_sales);
        $top_sales_quantity = reset($product_sales);
        $top_sales_percentage = min($top_sales_quantity * 5, 1000);
        $top_sales = $top_sales_product_name . ' (' . number_format($top_sales_percentage, 2) . '%)';

        foreach ($admin_orders as $admin_id => $order_details) {
            $total_products = implode(', ', $order_details['products']);
            $total_price = $order_details['total_price'];
            $images = isset($order_details['images']) ? implode(',', $order_details['images']) : '';
            $main_image = rtrim($images, ',');

            // Insert order into database including shipping fee
            $order_query = "INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price, placed_on, payment_status, top_sales, top_sales_percentage, admin_id, is_hidden, image, shipping_fee, qr_code_value)
                VALUES ('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$total_price', '$placed_on', 'pending', '$top_sales', '$top_sales_percentage', '$admin_id', 0, '$main_image', '$shipping_fee', '$qr_code_value')";
            
            if (!mysqli_query($conn, $order_query)) {
                die('Order insertion failed: ' . mysqli_error($conn));
            }
        }

        // Clear the cart after order placement
        $clear_cart_query = mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'");
        if (!$clear_cart_query) {
            die('Clear cart query failed: ' . mysqli_error($conn));
        }

        $message[] = 'Order placed successfully!';
    }
}


$grand_total = 0;
?>


<?php @include("header.php"); ?>
<?php @include("navbar.php"); ?>

<?php if (!empty($message)) : ?>
    <?php foreach ($message as $msg) : ?>
        <div class='message-box alert alert-success text-center d-flex align-items-center justify-content-between' role='alert'>
            <span><?php echo htmlspecialchars($msg); ?></span>
            <button type='button' class='btn-close' aria-label='Close'></button>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<div class="container">
    <div class="container section-title" data-aos="fade-up">
        <p><span>Check</span><span class="description-title">out</span></p>
    </div>

    <section class="display-order mb-4">
       <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Cost</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grand_total = 0;
                $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'");
                if (!$select_cart) {
                    die('Select cart query failed: ' . mysqli_error($conn));
                }

                if (mysqli_num_rows($select_cart) > 0) {
                    while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                        $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
                        $grand_total += $total_price;
                ?>
                <tr>
                    <td><img src="images/<?php echo htmlspecialchars($fetch_cart['image']); ?>" alt="" style="width: 50px; height: auto;"></td>
                    <td><?php echo htmlspecialchars($fetch_cart['name']); ?></td>
                    <td>₱<?php echo htmlspecialchars($fetch_cart['price']); ?></td>
                    <td><?php echo htmlspecialchars($fetch_cart['quantity']); ?><?php echo $fetch_cart['sizes']; ?></td>
                </tr>
                <?php
                    }
                } else {
                    echo '<tr><td colspan="6" class="empty text-center">Cart is empty</td></tr>';
                }
                ?>
            </tbody>
        </table>
        
        <form action="" method="post">
           
            <div class="row">
                <!-- Payment and Address Fields -->
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="method">Payment Method:</label>
                        <select id="method" name="method"  class="form-select" required onchange="togglePaymentMethod()">
                            <option value="" defult selected>------select payment method------</option>
                            <!-- <option value="gcash">GCASH</option>   -->
                            <option value="cod">COD</option>
                            <option value="pick-up">Pick up</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-3" id="ShippingFee">
                    <div class="form-group" >
                        <label for="method">Shipping Fee</label>
                        <div class="card" id="ShippingView">
                        <div class="card-body d-flex justify-content-center align-items-center text-center p-4">
                            <img src="images/2.png" alt="" class="m-3" style="    width: 155px;">
                            <div class="next">
                                
                                <h1>Shipping Fee: ₱<b id="shipping_fee"><?php
                                    $shipping_total = 0;

                                    // Fetch distinct admin IDs from the cart
                                    $admin_ids_query = mysqli_query($conn, "SELECT DISTINCT admin_id FROM `cart` WHERE user_id = '$user_id'");
                                    if (!$admin_ids_query) {
                                        die('Admin IDs query failed: ' . mysqli_error($conn));
                                    }

                                    while ($admin_id_row = mysqli_fetch_assoc($admin_ids_query)) {
                                        $admin_id = $admin_id_row['admin_id'];

                                        // Now query the shipping fee for each admin_id
                                        $shipping_query = mysqli_query($conn, "SELECT price FROM `shipping_fee` WHERE admin_id = '$admin_id'");
                                        if (!$shipping_query) {
                                            die('Shipping query failed: ' . mysqli_error($conn));
                                        }

                                        while ($shipping_row = mysqli_fetch_assoc($shipping_query)) {
                                            $shipping_total += (float)$shipping_row['price']; // Add the shipping fee to the total
                                        }
                                    }
                                    echo number_format($shipping_total);
                                    ?></b></h1>
                        
    
                        </div>

                        </div>
                       
                    </div>
              
                </div>
                <div class="col-md-6 mb-3" id="GcashFee">
    <div class="form-group">
        <label for="method">QR Code Fee</label>
        <div class="card" id="Gcash">
            <div class="card-body">
                <div class="text-card">
                    <div class="content">
                    </div>


<div class="grand-total mt-3">
Grand Total: <span class="fw-bold fs-3">₱<?php  



//echo number_format($shipping_total); 
  $grand_total += $shipping_total;



echo htmlspecialchars( $grand_total ); ?></span>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


                <!-- Other Billing Details -->
                <div class="col-md-6 mb-3 d-none">
                    <div class="form-group">
                        <label for="number">Contact Number:</label>
                        <input type="text" id="number" name="number" class="form-control" value="<?php echo htmlspecialchars($number); ?>" required>
                    </div>
                </div>

                <div class="col-12 mb-3 d-none">
                    <div class="form-group">
                        <label for="address">Billing Address:</label>
                        <input type="text" id="address" name="address" class="form-control" placeholder="e.g. barangay street, province, zip code" value="<?php echo htmlspecialchars($address); ?>" required>
                    </div>
                </div>
                <div class="grand-total mt-3">
            Grand Total: <span class="fw-bold fs-3">₱<?php echo htmlspecialchars($grand_total); ?></span>
        </div>

        <div class="col-12">
                    <button type="button" class="btn btn-success btn-block" data-bs-toggle="modal" data-bs-target="#productModal">Place Order</button>
                </div>

<?php
// Define the admin type or id for the query
$admin_type = 'admin'; // Or use admin ID if you have a specific admin ID to target

// Fetch admin data based on user_type or ID
$admin_query = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = '$admin_type'");
$admin_data = mysqli_fetch_assoc($admin_query) ?? []; // Fallback to an empty array if no result

$fetch_products = []; // Initialize to avoid undefined variable error
if (isset($product_id)) {
    $product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$product_id'");
    if ($product_query && mysqli_num_rows($product_query) > 0) {
        $fetch_products = mysqli_fetch_assoc($product_query);
    }
}

// Check if the user has already paid half price
$has_paid_half = false;
if (isset($order_id)) {
    $order_check_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE id = '$order_id' AND paid_amount >= " . ($fetch_products['price'] / 2));
    if ($order_check_query && mysqli_num_rows($order_check_query) > 0) {
        $has_paid_half = true;
    }
}
?>

<!-- Order Modal with QR Code -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header text-dark">
                <h5 class="modal-title" id="productModalLabel">Confirm Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?php
                $admin_image = !empty($admin_data['image']) ? 'images/' . $admin_data['image'] : 'images/default-avatar.png';
            ?>
            <div class="modal-body p-4">
                <div class="text-center">
                    <p><b><?php echo $admin_data['name'] ?? 'N/A'; ?></b> Gcash QR Code </p>

                    <?php 
                    // Check if the QR code exists for the admin and display it
                    if (!empty($admin_data['qr_code'])) {
                        echo '<img src="qr_codes/' . $admin_data['qr_code'] . '" class="img-fluid" alt="QR Code" style="max-width: 100%;">';
                    } else {
                        echo '<p>No QR Code available.</p>';
                    }
                    ?>

                    <div class="grand-total mt-3">
                        Grand Total: <span class="fw-bold fs-3">₱<?php echo htmlspecialchars($grand_total); ?></span>
                    </div>
                    <button type="submit" name="order_btn" class="btn btn-primary w-100 mt-3">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>






            </div>
        </form>
    </section>
 
    <section class="checkout p-4">
        
    </section>
</div>

<script>
// JavaScript to handle the payment method change and AJAX call
$(document).ready(function() {
    $('#payment_method').change(function() {
        var paymentMethod = $(this).val(); // Get the selected payment method (gcash or cod)

        // Send AJAX request to calculate the shipping fee
        $.ajax({
            url: 'calculate_shipping.php',  // PHP script to calculate shipping fee
            type: 'POST',
            data: {
                method: paymentMethod,
                user_id: <?php echo $user_id; ?>  // Pass the user_id (ensure it's correctly set)
            },
            success: function(response) {
                // Parse the response from the server (shipping fee and updated grand total)
                var data = JSON.parse(response);
                var shippingFee = parseFloat(data.shipping_fee);
                var cartTotal = parseFloat(data.cart_total);

                // Calculate the new grand total (cart total + shipping fee if COD)
                var grandTotal = (paymentMethod === 'cod') ? (cartTotal + shippingFee) : cartTotal;

                // Update the shipping fee and grand total on the page
                $('#shipping_fee').text(shippingFee.toFixed(2));
                $('#grand_total').text('₱' + grandTotal.toFixed(2));
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed: ' + status + ', ' + error);
            }
        });
    });
});
</script>
<script>
function togglePaymentMethod() {
    var method = document.getElementById('method').value;
    var ShippingFee = document.getElementById('ShippingFee');
    var ShippingView = document.getElementById('ShippingView');
    var GcashFee = document.getElementById('GcashFee');
    var Gcash = document.getElementById('Gcash');

    if (method === 'cod') {
        ShippingFee.style.display = 'block';
    } else{
        ShippingFee.style.display = 'none';
        ShippingView.value = '';
    }

    if (method === 'gcash') {
        GcashFee.style.display = 'block';
    } else{
        GcashFee.style.display = 'none';
        ShippingView.value = '';
    }

}

// Initialize the toggle state on page load
document.addEventListener('DOMContentLoaded', function() {
    togglePaymentMethod();
});
</script>
<?php @include("footer.php"); ?>
