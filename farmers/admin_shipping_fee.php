<?php
include '../config.php'; // Include the database connection file
session_start(); // Start the session

// Check if admin_id is set in the session
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('Location: ../login.php'); // Redirect to login if not authenticated
    exit();
}

// Initialize variables
$price = '';
$message = '';
$inserted_fee = '';
$fee_id = ''; // Variable to hold the fee ID for edit/delete operations

if (isset($_POST['submit'])) {
    $from_location = mysqli_real_escape_string($conn, $_POST['from_location']);
    $to_location = mysqli_real_escape_string($conn, $_POST['to_location']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    // Check if a shipping fee already exists for this route and admin
    $check_fee_sql = "SELECT * FROM shipping_fee WHERE admin_id = '$admin_id' AND from_location = '$from_location' AND to_location = '$to_location'";
    $check_fee_result = mysqli_query($conn, $check_fee_sql);

    if (mysqli_num_rows($check_fee_result) > 0) {
        $message = "You can only add one shipping fee for each route.";
    } else {
        $sql = "INSERT INTO shipping_fee (price, from_location, to_location, admin_id) VALUES ('$price', '$from_location', '$to_location', '$admin_id')";
        if (mysqli_query($conn, $sql)) {
            $message = "Shipping fee added successfully.";
            $inserted_fee = $price;
            $fee_id = mysqli_insert_id($conn);
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}


// Check if delete request is made
if (isset($_POST['delete'])) {
    $fee_id = $_POST['fee_id'];

    // SQL to delete the shipping fee
    $delete_sql = "DELETE FROM shipping_fee WHERE id='$fee_id' AND admin_id='$admin_id'";

    if (mysqli_query($conn, $delete_sql)) {
        $message = "Shipping fee deleted successfully.";
        $inserted_fee = ''; // Clear the inserted fee after deletion
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Check if the edit form is submitted
if (isset($_POST['edit'])) {
    $fee_id = $_POST['fee_id'];
    $new_price = mysqli_real_escape_string($conn, $_POST['new_price']);

    // SQL to update the shipping fee
    $update_sql = "UPDATE shipping_fee SET price='$new_price' WHERE id='$fee_id' AND admin_id='$admin_id'";

    if (mysqli_query($conn, $update_sql)) {
        $message = "Shipping fee updated successfully.";
        $inserted_fee = $new_price; // Update the displayed fee
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Include admin header and navbar
@include("admin_header.php");
@include("admin_navbar.php");
?>

<!-- Product CRUD section starts -->
<div class="container my-5">
    <h1 class="text-center mb-4">Manage Shipping Fees</h1>

    <?php
    if (isset($message)) {
        echo '<div class="alert alert-info text-center">' . htmlspecialchars($message) . '</div>';
    }
    ?>

<form method="POST" action="">
    <div class="mb-3">
        <label for="from_location" class="form-label">From Location:</label>
        <input type="text" class="form-control" name="from_location" id="from_location" required>
    </div>
    <div class="mb-3">
        <label for="to_location" class="form-label">To Location:</label>
        <input type="text" class="form-control" name="to_location" id="to_location" required>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Shipping Fee Price:</label>
        <input type="text" class="form-control" name="price" id="price" required>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Add Shipping Fee</button>
</form>


    <!-- Display recently added shipping fees based on admin_id -->
    <!-- <h2 class="text-center mt-4">Your Shipping Fee:</h2> -->
    <h2 class="text-center mt-4">Your Shipping Fees:</h2>
<div class="mt-4">
    <?php
    $select_fees = mysqli_query($conn, "SELECT * FROM `shipping_fee` WHERE admin_id = '$admin_id'");
    if (mysqli_num_rows($select_fees) > 0) {
        while ($fetch_fees = mysqli_fetch_assoc($select_fees)) {
            $fee_id = $fetch_fees['id'];
            $fee_price = $fetch_fees['price'];
            $from_location = $fetch_fees['from_location'];
            $to_location = $fetch_fees['to_location'];
    ?>
            <div class="card mb-3 shadow">
                <div class="card-body">
                    <h5 class="card-title">Shipping Fee: $<?php echo htmlspecialchars($fee_price); ?></h5>
                    <p>From: <?php echo htmlspecialchars($from_location); ?> To: <?php echo htmlspecialchars($to_location); ?></p>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editShippingFeeModal<?php echo $fee_id; ?>">Edit</button>
                    <form method="POST" action="" class="d-inline">
                        <input type="hidden" name="fee_id" value="<?php echo $fee_id; ?>">
                        <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
    <?php
        }
    } else {
        echo '<p class="text-center">No shipping fees found.</p>';
    }
    ?>
</div>

</div>

<?php
// Include footer or any other necessary components if needed
@include("admin_footer.php");
?>
