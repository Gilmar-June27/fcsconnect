<?php
include '../config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('Location: ../login.php');
    exit();
}

// if (isset($_POST['add_product'])) {
//    $name = mysqli_real_escape_string($conn, $_POST['name']);
//    $price = $_POST['price'];
//    $available_kilo = $_POST['available_kilo'];
//    $image = $_FILES['image']['name'];
//    $image_size = $_FILES['image']['size'];
//    $image_tmp_name = $_FILES['image']['tmp_name'];
//    $image_folder = 'images/' . $image;
//    $descriptions = mysqli_real_escape_string($conn, $_POST['descriptions']);
//    $category = $_POST['category'];
//    $havest_status = $_POST['havest_status'];
//    $harvest_date = $_POST['dates'];

//    if ($image_size > 2000000) {
//        $_SESSION['message'] = 'Image size is too large';
//    } else {
//        $add_product_query = mysqli_query($conn, "INSERT INTO `products` (name, price, available_kilo, image, descriptions, category, havest_status, admin_id, status, harvest_date) VALUES('$name', '$price', '$available_kilo', '$image', '$descriptions', '$category', '$havest_status', '$admin_id', 'processing', '$harvest_date')");

//        if ($add_product_query) {
//            move_uploaded_file($image_tmp_name, $image_folder);
//            $_SESSION['message'] = 'Product added successfully! Waiting for approval.';
//        } else {
//            $_SESSION['message'] = 'Product could not be added!';
//        }
//    }

//    header('Location: admin_products.php');
//    exit();
// }
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $available_kilo = $_POST['available_kilo'];
    $descriptions = mysqli_real_escape_string($conn, $_POST['descriptions']);
    $category = $_POST['category'];
    $sizes = $_POST['sizes'];
    $havest_status = $_POST['havest_status'];
    $harvest_date = $_POST['dates'];
    
    // Handle multiple image uploads
    $image_names = [];
    $total_images = count($_FILES['images']['name']);
 
    for ($i = 0; $i < $total_images; $i++) {
        $image = $_FILES['images']['name'][$i];
        $image_size = $_FILES['images']['size'][$i];
        $image_tmp_name = $_FILES['images']['tmp_name'][$i];
        $image_folder = '../images/' . $image;
 
        if ($image_size > 2000000) {
            $_SESSION['message'] = 'One or more images exceed the size limit of 2MB.';
            header('Location: admin_products.php');
            exit();
        }
 
        // Move the uploaded image
        if (move_uploaded_file($image_tmp_name, $image_folder)) {
            $image_names[] = $image; // Store the image name in the array
        } else {
            $_SESSION['message'] = 'Failed to upload one or more images.';
            header('Location: admin_products.php');
            exit();
        }
    }
 
    // Convert the array of image names to a comma-separated string
    $image_names_str = implode(',', $image_names);
 
    // Insert product data into the database
    $add_product_query = mysqli_query($conn, "INSERT INTO `products` (name, price, available_kilo, images, descriptions, category,sizes, havest_status, admin_id, status, harvest_date) VALUES('$name', '$price', '$available_kilo', '$image_names_str', '$descriptions', '$category','$sizes' , '$havest_status', '$admin_id', 'approved', '$harvest_date')");
 
    if ($add_product_query) {
        $_SESSION['message'] = 'Product added successfully with multiple images!.';
    } else {
        $_SESSION['message'] = 'Product could not be added!';
    }
 
    header('Location: admin_products.php');
    exit();
 }
 



if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'");
    $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
    unlink('images/' . $fetch_delete_image['image']);
    mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'");
    header('Location: admin_products.php');
    exit();
}

if (isset($_POST['update_product'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_name = $_POST['update_name'];
    $update_price = $_POST['update_price'];
    $update_category = $_POST['update_category'];
    $update_sizes = $_POST['update_sizes'];
    $update_descriptions = $_POST['update_descriptions'];
    $update_havest_status = $_POST['update_havest_status'];
    $update_harvest_date = $_POST['dates'];

    mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price', category = '$update_category', sizes = '$update_sizes', descriptions = '$update_descriptions', havest_status = '$update_havest_status', harvest_date = '$update_harvest_date' WHERE id = '$update_p_id'");

    $update_image = $_FILES['update_image']['name'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_folder = 'images/' . $update_image;
    $update_old_image = $_POST['update_old_image'];

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $_SESSION['message'] = 'Image file size is too large';
        } else {
            mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'");
            move_uploaded_file($update_image_tmp_name, $update_folder);
            unlink('images/' . $update_old_image);
        }
    }

    header('Location: admin_products.php');
    exit();
}

if (isset($_GET['approve'])) {
    $approve_id = $_GET['approve'];
    mysqli_query($conn, "UPDATE `products` SET status = 'approved' WHERE id = '$approve_id'");
    header('Location: admin_products.php');
    exit();
}

if (isset($_GET['reject'])) {
    $reject_id = intval($_GET['reject']);
    mysqli_query($conn, "DELETE FROM `products` WHERE id = '$reject_id'");
    header('Location: admin_products.php');
    exit();
}
?>
<?php
@include("admin_header.php");
@include("admin_navbar.php");
?>

<!-- Product CRUD section starts -->
<div class="container my-5">
    <h1 class="text-center mb-4">Shop Products</h1>

    <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-info text-center">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
    }
    ?>

    <div class="card p-4 mb-5">
        <h3 class="mb-3">Add Product</h3>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter product name" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" name="price" min="0" class="form-control" placeholder="Enter product price" required>
            </div>
            <div class="mb-3">
                <label for="available_kilo" class="form-label">Available Kilos & Heads</label>
                <input type="number" name="available_kilo" min="0" class="form-control" placeholder="Enter available kilos & heads" required>
            </div>
            <div class="mb-3">
                <label for="sizes_kl_heads" class="form-label">Size</label>
                <select name="sizes" class="form-select">
                    <option value="kg">kg</option>
                    <option value="pcs">pcs</option>
                    <option value="tray">tray</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="descriptions" class="form-label">Description</label>
                <textarea type="text" name="descriptions" class="form-control" placeholder="Enter product description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option value="sea_foods">AquaCulture</option>
                    <option value="vegetables">Vegetables</option>
                    <option value="livestock">Livestock</option>
                    <option value="crops">Crops</option>
                    <option value="fruits">Fruits</option>
                    <option value="poultry">Poultry</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="havest_status" class="form-label">Harvest Status</label>
                <select name="havest_status" id="harvestStatus" class="form-select" onchange="toggleDateInput()">
                    <option value="ready_to_harvest">Ready to Harvest</option>
                    <option value="harvested">Harvested</option>
                    <option value="ready_to_pick_up">Ready to pick up</option>
                </select>
            </div>
            <div class="mb-3" id="dateInputDiv">
                <label for="dates" class="form-label">Estimated Harvest Date</label>
                <input type="date" name="dates" id="harvestDateInput" class="form-control">
            </div>
            <!-- <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" name="image" class="form-control" accept="image/jpg, image/jpeg, image/png" required>
            </div> -->
            <div class="mb-3">
    <label for="image" class="form-label">Product Images</label>
    <input type="file" name="images[]" class="form-control" accept="image/jpg, image/jpeg, image/png" multiple required>
</div>
            <div class="d-grid">
                <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
            </div>
        </form>
    </div>

    </div> 







    <!-- Products Loop -->
<!-- <?php
$query = mysqli_query($conn, "SELECT * FROM `products` WHERE admin_id = '$admin_id'");
if (mysqli_num_rows($query) > 0) {
    while ($product = mysqli_fetch_assoc($query)) {
        $images = explode(',', $product['images']); // Assuming 'images' is stored as a comma-separated string
        ?>
        
        <div class="card mb-4">
            <img src="../images/<?php echo $images[0]; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>" style="height: 200px; object-fit: cover;" data-bs-toggle="modal" data-bs-target="#productModal<?php echo $product['id']; ?>">
            <div class="card-body">
                <h5 class="card-title"><?php echo $product['name']; ?></h5>
                <p class="card-text">Price: $<?php echo $product['price']; ?></p>
                <p class="card-text"><?php echo $product['descriptions']; ?></p>
            </div>
        </div>

       
        <div class="modal fade" id="productModal<?php echo $product['id']; ?>" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel"><?php echo $product['name']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="carouselExample<?php echo $product['id']; ?>" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php
                                $active = 'active';
                                foreach ($images as $image) {
                                    ?>
                                    <div class="carousel-item <?php echo $active; ?>">
                                        <img src="../images/<?php echo $image; ?>" class="d-block w-100" alt="...">
                                    </div>
                                    <?php
                                    $active = ''; // Reset active after the first image
                                }
                                ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample<?php echo $product['id']; ?>" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample<?php echo $product['id']; ?>" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>

                   
                    <div class="modal-footer justify-content-center">
                        <button class="btn btn-secondary prev-slide-btn" data-carousel-id="carouselExample<?php echo $product['id']; ?>">Previous</button>
                        <button class="btn btn-primary next-slide-btn" data-carousel-id="carouselExample<?php echo $product['id']; ?>">Next</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }
} else {
    echo "<p class='text-center'>No products found.</p>";
}
?> -->












<script>
    document.addEventListener('DOMContentLoaded', function () {
        var carouselElements = document.querySelectorAll('.carousel');

        carouselElements.forEach(function (carousel) {
            let touchStartX = 0;
            let touchEndX = 0;

            carousel.addEventListener('touchstart', function (event) {
                touchStartX = event.changedTouches[0].screenX;
            });

            carousel.addEventListener('touchend', function (event) {
                touchEndX = event.changedTouches[0].screenX;
                handleSwipeGesture(carousel);
            });

            function handleSwipeGesture(carouselElement) {
                if (touchEndX < touchStartX - 50) {
                    bootstrap.Carousel.getInstance(carouselElement).next();
                }
                if (touchEndX > touchStartX + 50) {
                    bootstrap.Carousel.getInstance(carouselElement).prev();
                }
            }
        });

        // Slide buttons event listeners
        document.querySelectorAll('.prev-slide-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                var carouselId = this.getAttribute('data-carousel-id');
                var carouselElement = document.getElementById(carouselId);
                bootstrap.Carousel.getInstance(carouselElement).prev();
            });
        });

        document.querySelectorAll('.next-slide-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                var carouselId = this.getAttribute('data-carousel-id');
                var carouselElement = document.getElementById(carouselId);
                bootstrap.Carousel.getInstance(carouselElement).next();
            });
        });
    });


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
