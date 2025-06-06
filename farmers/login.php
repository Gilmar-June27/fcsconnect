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
      if ($row['block'] == 'yes') {
        $message[] = 'Your account has been blocked by the super admin.';
    } else {
        // Update the status to 'activate' upon successful login
        $user_id = $row['id'];
      // Update the status to 'activate' upon successful login
      $update_query = "UPDATE `users` SET status = 'activate' WHERE id = '$user_id'";
      mysqli_query($conn, $update_query) or die('query failed');

      if($row['user_type'] == 'admin'){
         $_SESSION['admin_name'] = $row['name'];
         $_SESSION['admin_email'] = $row['email'];
         $_SESSION['admin_id'] = $row['id'];
         header('location:admin_dashboard.php');

      }
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
                    if (isset($message)) {
                        foreach ($message as $msg) {
                            echo '
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ' . $msg . '
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            ';
                        }
                    }
                    ?>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="text" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>
                        <button type="submit" name="submit" class="btn btn-success btn-block">Login</button>
                        <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register</a></p>
                    </form>
                    </div>
    </div>
</script>
</body>
</html>

