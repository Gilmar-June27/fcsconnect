<?php

include '../config.php';
session_start();

if(isset($_POST['submit'])){

   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){

      $row = mysqli_fetch_assoc($select_users);
      $user_id = $row['id'];

      // Update the status to 'activate' upon successful login
      $update_query = "UPDATE `users` SET status = 'activate' WHERE id = '$user_id'";
      mysqli_query($conn, $update_query) or die('query failed');

     if($row['user_type'] == 'super_admin'){
         $_SESSION['super_admin_name'] = $row['name'];
         $_SESSION['super_admin_email'] = $row['email'];
         $_SESSION['super_admin_id'] = $row['id'];
         header('location:super-admin-dashboard.php');

      }

   }else{
      $message[] = 'incorrect email or password!';
   }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Login</title>
    <link rel="stylesheet" href="../css/signed-form.css">
</head>
<body>
<div class="container">
        <div class="content">
            <h1>Welcome to FCs-Connect</h1>
            <p>Your gateway to seamless and efficient connections.</p>
            <p>Discover, connect, and manage your engagements effortlessly.</p>
        </div>
        <div class="form">
            <h1>Admin Login</h1>
            <?php
                if(isset($message)){
                foreach($message as $message){
                    echo '
                    <div class="message" style="
                    border: 1px solid red;
                    padding: 8px 10px;
                    background: #fdd4d4e8;
                    border-radius: 5px;
                ">
                        <span>'.$message.'</span>
                        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                    </div>
                    ';
                }
                }
                ?>
                
            <form action="" method="POST">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" required>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                
                <label>
                    <input type="checkbox" name="remember"> Remember Me
                </label>
                
                <button type="submit" name="submit">Login</button>
              
            </form>
        </div>
    </div>
</body>
</html>
