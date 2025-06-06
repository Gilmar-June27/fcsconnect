<?php

include 'config.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
    $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
    $user_type = $_POST['user_type'];
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $address = isset($_POST['flat'], $_POST['street'], $_POST['city'], $_POST['country'], $_POST['pin_code'])
        ? mysqli_real_escape_string($conn, 'flat no. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code'])
        : '';

    // Image handling
    $image = '';
    if ($user_type == 'admin' && isset($_FILES['image'])) {
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'images/' . $image;

        if ($image_size > 2000000) {
            $message[] = 'Image size is too large!';
        } else {
            if (!move_uploaded_file($image_tmp_name, $image_folder)) {
                $message[] = 'Failed to upload image!';
            }
        }
    }

    // Farmers Id image handling
    $farmers_id_image = '';
    if ($user_type == 'admin' && isset($_FILES['farmers_id'])) {
        $farmers_id_image = $_FILES['farmers_id']['name'];
        $farmers_id_size = $_FILES['farmers_id']['size'];
        $farmers_id_tmp_name = $_FILES['farmers_id']['tmp_name'];
        $farmers_id_folder = 'images/' . $farmers_id_image;

        if ($farmers_id_size > 2000000) {
            $message[] = 'Farmers ID image size is too large!';
        } else {
            if (!move_uploaded_file($farmers_id_tmp_name, $farmers_id_folder)) {
                $message[] = 'Failed to upload Farmers ID image!';
            }
        }
    }

    // Check if user exists
    $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');
    if (mysqli_num_rows($select_users) > 0) {
        $message[] = 'User already exists!';
    } else {
        // Check if passwords match
        if ($pass != $cpass) {
            $message[] = 'Confirm password not matched!';
        } else {
            // Insert data into the database
            $insert_query = "INSERT INTO `users`(name, email, username, password, user_type, image, number, address, farmers_id) 
                            VALUES('$name', '$email', '$username', '$cpass', '$user_type', '$image', '$number', '$address', '$farmers_id_image')";
            if (mysqli_query($conn, $insert_query)) {
                $message[] = 'Registered successfully!';
                header('location:login.php');
            } else {
                $message[] = 'Failed to register user!';
            }
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body style="background-image: url(images/1.jpg); background-repeat: no-repeat; background-size: cover;">
<div class="container">
    <div class="row min-vh-100 d-flex align-items-center">
        <div class="col-md-6" id="content" >
            <div class="content text-left text-white">
                <h1>Welcome to FCs-Connect</h1>
                <p>Your gateway to seamless and efficient connections.</p>
                <p>Discover, connect, and manage your engagements effortlessly.</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4">
                <h1 class="text-center mb-4">Register</h1>

                <?php
                if(isset($message)){
                    foreach($message as $msg){
                        echo '
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span>'.$msg.'</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        ';
                    }
                }
                ?>

                <form id="registerForm" action="?" method="POST" enctype="multipart/form-data">
                    <div class="boxes">
                    <div class="form-group">
                        <label for="fullName">Full Name:</label>
                        <input type="text" class="form-control" id="fullName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
    <label for="user_type">User Type:</label>
    <select name="user_type" id="method" class="form-control" onchange="togglePaymentMethod()">
        <option value="user">Buyers</option>
        <option value="admin">Farmers</option>
    </select>
</div>

<div class="form-group" id="adminId" style="display: none;">
    <label for="method">Farmers Id: </label>
    <div class="card" id="admin">
        <div class="card-body">
            <div class="text-card">
                <div class="content">
                    <input type="file" name="farmers_id" accept="image/jpg, image/jpeg, image/png">
                </div>
            </div>
        </div>
    </div>
</div>


                    <div class="form-group">
                        <label for="">Phone Number</label>
                        <input type="text" id="number" name="number" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Purok/Street/Barangay</label>
                        <input type="text" id="flat" name="flat" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Municipality</label>
                        <input type="text" id="street" name="street" class="form-control" required >
                    </div>
                    <div class="form-group">
                        <label for="">City</label>
                        <input type="text" id="city" name="city" class="form-control" required >
                    </div>
                    <div class="form-group">
                        <label for="">Country</label>
                        <input type="text" id="country" name="country" class="form-control" required >
                    </div>
                    <div class="form-group">
                        <label for="">Pin Code</label>
                        <input type="text" id="pin_code" name="pin_code" class="form-control" required >
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password:</label>
                        <input type="password" class="form-control" id="confirmPassword" name="cpassword" required>
                    </div>
                    <div class="form-group" style="display:none">
                        <label for="image">Upload Image:</label>
                        <input type="file" class="form-control" name="image" accept="image/jpg, image/jpeg, image/png">
                    </div>
                    <!--<div class="g-recaptcha" data-sitekey="6LcLw3gqAAAAAHyBwtZUsTvWv_Oy1IqO9qDnBjCa"></div>--->

                    </div>
                    <button type="submit" name="submit" class="btn btn-success btn-block mt-4">Register</button>
                    <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('registerForm').addEventListener('submit', function(event) {
    var response = grecaptcha.getResponse();
    if(response.length == 0){
        alert("please verify you're not a robot");
        event.preventDefault(); // Prevent form submission
    }
});
</script>


<script>
function togglePaymentMethod() {
    var userType = document.getElementById("method").value; // Get selected value from the dropdown
    var adminSection = document.getElementById("adminId"); // Get the section to show/hide

    if (userType === "admin") {
        adminSection.style.display = "block"; // Show the section if 'Farmers' is selected
    } else {
        adminSection.style.display = "none"; // Hide the section if 'Buyers' is selected
    }
}

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
