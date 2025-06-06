<?php
include 'config.php';
session_start();

if (isset($_POST['submit'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

    $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

    if (mysqli_num_rows($select_users) > 0) {

        $row = mysqli_fetch_assoc($select_users);

        if ($row['block'] == 'yes') {
            $message[] = 'Your account has been deactivated by the admin.';
        } else {
            $user_id = $row['id'];
            $update_query = "UPDATE `users` SET status = 'activate' WHERE id = '$user_id'";
            mysqli_query($conn, $update_query) or die('query failed');

            if ($row['user_type'] == 'user') {
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_id'] = $row['id'];
                header('location:index.php');
            } elseif ($row['user_type'] == 'admin') {
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['admin_email'] = $row['email'];
                $_SESSION['admin_id'] = $row['id'];
                header('location:farmers/admin_dashboard.php');
            } elseif ($row['user_type'] == 'super_admin') {
                $_SESSION['super_admin_name'] = $row['name'];
                $_SESSION['super_admin_email'] = $row['email'];
                $_SESSION['super_admin_id'] = $row['id'];
                header('location:admin/super-admin-dashboard.php');
            }
        }

    } else {
        $message[] = 'Incorrect email or password!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Register</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .password-toggle {
            cursor: pointer;
        }
    </style>
</head>
<body style="background-image: url(images/1.jpg); background-repeat: no-repeat; background-size: cover;">
<div class="container">
    <div class="row min-vh-100 d-flex align-items-center">
        <div class="col-md-6">
            <!-- Left Side Content -->
            <div class="content text-left text-white">
                <h1>Welcome to FCs-Connect</h1>
                <p>Your gateway to seamless and efficient connections.</p>
                <p>Discover, connect, and manage your engagements effortlessly.</p>
            </div>
        </div>
        <div class="col-md-6">
            <!-- Right Side Form -->
            <div class="card p-4">
                <h1 class="text-center mb-4">Login</h1>

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
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="input-group-append">
                                <span class="input-group-text password-toggle" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" name="submit" class="btn btn-success btn-block">Login</button>
                    <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.querySelector('#togglePassword');
        const passwordField = document.querySelector('#password');
        togglePassword.addEventListener('click', function (e) {
            // Toggle the type attribute
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            
            // Toggle the eye icon
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });
</script>
</body>
</html>
