<?php
include 'config.php';
session_start();

$admin_id = $_GET['admin_id'] ?? '';

if (!isset($admin_id)) {
    header('Location:landing-page.php');
    exit;
}

$admin_query = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id' AND user_type = 'admin'") or die('Query failed');
$admin_data = mysqli_fetch_assoc($admin_query);
$status_class = $admin_data['status'] == 'activate' ? 'success' : 'danger';

if (!$admin_data) {
    echo 'Admin not found!';
    exit;
}

// Check if the current user has blocked the admin
$check_block_status = mysqli_query($conn, "SELECT * FROM `reports` WHERE reporter_id = '{$_SESSION['user_id']}' AND reported_user_id = '$admin_id' AND blocked = 1") or die('Query failed');
$is_blocked = mysqli_num_rows($check_block_status) > 0;

//determine the days when and expired the blocked users
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

    //this else statement can unblock the users if the remaining days already done
    $can_unblock = false;
    $remaining_days = 0;
}


//this is for adding a product 
if (isset($_POST['add_to_cart']) && !$is_blocked) {
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
        $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '{$_SESSION['user_id']}'") or die('query failed');
        if (mysqli_num_rows($check_cart_numbers) > 0) {
            // echo "<script>alert('Product already added to cart!');</script>";
            $message[] = 'Product already added to cart!';
        } else {
            mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image, admin_id) VALUES('{$_SESSION['user_id']}', '$product_name', '$product_price', '$product_quantity', '$product_image', '$product_admin_id')") or die('query failed');
            // echo "<script>alert('Product added to cart!');</script>";
            $message[] = 'Product added to cart!';
        }
    }
}


//optional conditon f the user is block
if (isset($_POST['block_user'])) {
    $reported_user_id = $_POST['reported_user_id'] ?? '';
    $reporter_id = $_SESSION['user_id'];
    $current_date = date('Y-m-d H:i:s');

    // Check if the user is already blocked
    $check_existing_block = mysqli_query($conn, "SELECT * FROM `reports` WHERE reporter_id = '$reporter_id' AND reported_user_id = '$reported_user_id' AND blocked = 1") or die('Query failed');

    if (mysqli_num_rows($check_existing_block) == 0) {
        mysqli_query($conn, "INSERT INTO `reports` (reporter_id, reported_user_id, report_type, description, blocked, block_date) VALUES ('$reporter_id', '$reported_user_id', 'blocked', 'has been blocked', 1, '$current_date')") or die('Query failed');
    } else {
        mysqli_query($conn, "UPDATE `reports` SET blocked = 1, block_date = '$current_date' WHERE reporter_id = '$reporter_id' AND reported_user_id = '$reported_user_id'") or die('Query failed');
    }

    echo "<script>alert('User has been blocked successfully.');</script>";
    header('Location: ' . $_SERVER['PHP_SELF'] . '?admin_id=' . $admin_id);
    exit;
}

//this the statement can unblock the users
if (isset($_POST['unblock_user'])) {
    if ($can_unblock) {
        $reported_user_id = $_POST['reported_user_id'] ?? '';
        $reporter_id = $_SESSION['user_id'];

        // Unblock the user
        mysqli_query($conn, "UPDATE `reports` SET blocked = 0 WHERE reporter_id = '$reporter_id' AND reported_user_id = '$reported_user_id'") or die('Query failed');

        echo "<script>alert('User has been unblocked successfully.');</script>";
        header('Location: ' . $_SERVER['PHP_SELF'] . '?admin_id=' . $admin_id);
        exit;
    } else {
        echo "<script>alert('You can only unblock the user after 5 days.');</script>";
    }
}


//this statement can report the users
if (isset($_POST['report_user']) && !isset($_POST['block_user'])) {
    $report_reason = $_POST['report_reason'] ?? '';
    $report_description = $_POST['report_description'] ?? '';
    $reported_user_id = $_POST['reported_user_id'] ?? '';
    $reporter_id = $_SESSION['user_id'];

    if ($report_reason && $report_description) {
        mysqli_query($conn, "INSERT INTO `reports` (reporter_id, reported_user_id, report_type, description) VALUES ('$reporter_id', '$reported_user_id', '$report_reason', '$report_description')") or die('Query failed');
        echo "<script>alert('Report submitted successfully.');</script>";
    } else {
        echo "<script>alert('Please fill out all fields.');</script>";
    }
}
?>

<?php @include("header.php") ?>
<?php @include("navbar.php") ?>

<?php

//this statement it trigger to display a message 
if (isset($message)) {
    foreach ($message as $msg) {
        echo "
        <div class='message-box' style='background: #efdece; color: orange;'>
            <span>{$msg}</span>
            <i class='fas fa-times close-btn'></i>
        </div>";
    }
} elseif (isset($message_add)) {
    foreach ($message_add as $msg_add) {
        echo "
        <div class='message-box' style='background: #cfedd6; color: green;'>
            <span>{$msg_add}</span>
            <i class='fas fa-times close-btn'></i>
        </div>";
    }
}
?>
<!---this statement display all the information of each users they can update--->
<main class="container mt-4">
    <section class="row mb-4">
        <div class="col-md-4 text-center">
            <?php 
                $admin_image = $admin_data['image'] ? 'images/' . $admin_data['image'] : 'images/default-avatar.png';
            ?>
            <img src="<?php echo $admin_image; ?>" alt="Admin Image" class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
            <div class="mt-2">
                <span class="badge rounded-circle <?php echo 'bg-' . $status_class; ?>" style="width: 20px;transform: translateY(-35px);border: 2px solid #fff;height: 20px;display: inline-flex;margin-top: -182px;margin-left: 100px;"></span>
            </div>
        </div>
        <div class="col-md-8">
            <h2><?php echo $admin_data['name']; ?></h2>
            <p class="me-3"><?php echo $admin_data['farmer_select']; ?></p></br>
            <div class="d-flex align-items-center mb-3">
                
                <?php if (!$is_blocked) { ?>
                    <a href="chatsystem.php?admin_id=<?php echo $admin_data['id']; ?>&scroll_to_user=<?php echo $admin_data['id']; ?>" class="btn btn-primary me-2">Message</a>
                    <button class="btn btn-secondary" id="reportButton">Report</button>
                    <button type="button" class="btn btn-secondary m-2" data-bs-toggle="modal" data-bs-target="#qrCodeModal">Show Gcash QR Code</button>
                    
                            <!-- QR Code Modal -->
                        <div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="qrCodeModalLabel">QR Code</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <?php
                                if (!empty($admin_data['qr_code'])) {
                                    echo '<img src="qr_codes/' . $admin_data['qr_code'] . '" class="img-fluid" alt="QR Code" style="max-width: 100%;">';
                                } else {
                                    echo '<p>No QR Code available.</p>';
                                }
                                ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                            </div>
                        </div>
                        </div>
                <?php } else { ?>
                    <form action="" method="post">
                        <input type="hidden" name="reported_user_id" value="<?php echo $admin_data['id']; ?>">
                        <button type="submit" name="unblock_user" class="btn btn-warning" <?php echo !$can_unblock ? 'disabled' : ''; ?>>Unblock</button>
                        <?php if (!$can_unblock) { ?>
                            <p class="text-danger">You can unblock this user after <?php echo $remaining_days; ?> day(s).</p>
                        <?php } else { ?>
                            <p class="text-success">You can now unblock this user.</p>
                        <?php } ?>
                    </form>
                <?php } ?>
            </div>
        </div>
    </section>
<!---show the product----->
    <h2 class="text-center text-success">Products</h2>
    <section >
    <div class="row">

<div class="container">
<div class="row row-cols-1 row-cols-md-3">
        <?php  
            $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE admin_id = '$admin_id' AND status = 'approved'") or die('Query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                    $date3 = date('F d, Y h:i A', strtotime($fetch_products['harvest_date']));
                    $date4 = date('F d, Y ', strtotime($fetch_products['created_at']));
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
                <p>₱<?php echo $fetch_products['price']; ?> </p>
                
                <p>Status: <?php echo ($fetch_products['havest_status'] == 'ready_to_harvest') ? 'Ready to Harvest' : 'Harvested'; ?> </p>
                <?php if ($fetch_products['havest_status'] == 'ready_to_harvest') { ?>
                    <p>Ready to Harvest: <?php echo $date3; ?></p>
                <?php } else { ?>
                    <p>Harvested: <?php echo $date4; ?></p>
                <?php } ?>

               
                <p>Available kilo: <?php echo $fetch_products['available_kilo']; ?>kg</p>
                <p>Description: <?php echo $fetch_products['descriptions']; ?></p>
                
                <div class="mb-3">
                    <input type="number" min="1" name="product_quantity" value="1" class="form-control text-center" style="width: 100px; margin: 0 auto;">
                </div>

                <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $main_image; ?>">
                <?php if (!$is_blocked) { ?>
                    <input type="submit" value="Add to Cart" name="add_to_cart" class="btn btn-success btn-block">
                <?php } else { ?>
                    <input type="submit" value="Blocked" name="blocked" class="btn btn-success btn-block" disabled>
                <?php } ?>
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
                    </div>
                </div>
            </div>
        </div>
        <?php if (!$can_unblock) { ?>
                            <p class="text-danger">You can unblock this user after <?php echo $remaining_days; ?> day(s).</p>
                        <?php } else { ?>
                            <p class="text-success">You can now unblock this user.</p>
                        <?php } ?>
    </div>
</div>
        <?php 
                }
            } else {
                if ($is_blocked) {
                echo '<p class="empty btn btn-outline-danger m-auto">No products available because you block this user.</p>';
                } else {
                echo '<p class="empty btn btn-outline-danger m-auto">No products available.</p>';
                 }
            }
        ?>
        </div>
        </div>
        </div>
    </section>
</main>

<!-- <button type="submit" name="add_to_cart" class="btn btn-primary" <?php echo $is_blocked ? 'disabled' : ''; ?>>
                                <?php echo $is_blocked ? 'Blocked' : 'Add to Cart'; ?>
                            </button> -->
    <!-- Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Report User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <input type="hidden" name="reported_user_id" value="<?php echo $admin_id; ?>">
                        <div class="mb-3">
                            <label for="report_reason" class="form-label">Reason:</label>
                            <select name="report_reason" id="report_reason" class="form-select">
                                <option value="Spam">Spam</option>
                                <option value="Abuse">Abuse</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="report_description" class="form-label">Description:</label>
                            <textarea name="report_description" id="report_description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <button type="submit" name="report_user" class="btn btn-primary me-2">Submit Report</button>
                            <button type="submit" name="block_user" class="btn btn-danger">Block User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
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
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
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

document.getElementById('reportButton').addEventListener('click', function() {
    var reportModal = new bootstrap.Modal(document.getElementById('reportModal'));
    reportModal.show();
});




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
</script>
