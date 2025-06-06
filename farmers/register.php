<?php

include '../config.php';

if(isset($_POST['submit'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $username = mysqli_real_escape_string($conn, $_POST['username']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
   $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);

   if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
       $image = $_FILES['image']['name'];
       $image_size = $_FILES['image']['size'];
       $image_tmp_name = $_FILES['image']['tmp_name'];
       $image_folder = 'images/'.$image;
   } else {
       $image = '';
       $image_size = 0;
       $image_tmp_name = '';
       $image_folder = '';
   }

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){
      $message[] = 'User already exists!';
   } else {
      if($pass != $cpass){
         $message[] = 'Passwords do not match!';
      } elseif($image_size > 2000000){
         $message[] = 'Image size is too large!';
      } else {
         mysqli_query($conn, "INSERT INTO `users`(name, email, username, password, user_type, image) VALUES('$name', '$email', '$username', '$pass', '$user_type', '$image')") or die('query failed');
         $message[] = 'Registered successfully!';
         if ($image_tmp_name) {
             move_uploaded_file($image_tmp_name, $image_folder);
         }
         header('location:login.php');
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
    <link rel="stylesheet" href="../css/signed-form.css">
    <script src="https://www.google.com/recaptcha/api.js"></script>
</head>
<body>
<div class="container">
        <div class="content" id="content">
            <h1>Welcome to FCs-Connect</h1>
            <p>Your gateway to seamless and efficient connections.</p>
            <p>Discover, connect, and manage your engagements effortlessly.</p>
        </div>
        <div class="form">
            <h1>Register</h1>

            <?php
                if(isset($message)){
                    foreach($message as $message){
                        echo '
                        <div class="message">
                            <span>'.$message.'</span>
                            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                        </div>
                        ';
                    }
                }
            ?>
            <form id="registerForm" action="?" method="POST" enctype="multipart/form-data">
                <label for="fullName">Full Name:</label>
                <input type="text" id="fullName" name="name" required>
                
               

                <label for="email">Email Address:</label>
                <input type="text" id="email" name="email" required>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="cpassword" required>
                <div class="g-recaptcha" data-sitekey="6LcROx0qAAAAAI1e8_5UYQQ6H1p8wifMle_pReAc"></div>
                <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png" style="opacity:0">
                <select name="user_type" class="box" style="opacity:0">
                    <option value="user">Buyers</option>
                   
                 
                </select>


                <label>
                    <input type="checkbox" name="remember"> I accept the Terms and Conditions
                </label>
                
                <button type="submit" name="submit" id="register">Login</button>
                <p>Already have an account? <a href="login.php">Login</a></p>
            </form>
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
</body>
</html>

