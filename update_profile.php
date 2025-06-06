<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (isset($_POST['update_profile'])) {
    $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
    $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    mysqli_query($conn, "UPDATE `users` SET name = '$update_name', email = '$update_email', contact = '$contact', address = '$address', description = '$description' WHERE id = '$user_id'") or die('Query failed');

    $old_pass = $_POST['old_pass'];
    $update_pass = mysqli_real_escape_string($conn, md5($_POST['update_pass']));
    $new_pass = mysqli_real_escape_string($conn, md5($_POST['new_pass']));
    $confirm_pass = mysqli_real_escape_string($conn, md5($_POST['confirm_pass']));

    if (!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)) {
        if ($update_pass != $old_pass) {
            $message[] = 'Old password does not match!';
        } elseif ($new_pass != $confirm_pass) {
            $message[] = 'New password and confirmation do not match!';
        } else {
            mysqli_query($conn, "UPDATE `users` SET password = '$confirm_pass' WHERE id = '$user_id'") or die('Query failed');
            $message[] = 'Password updated successfully!';
        }
    }

    $update_image = $_FILES['update_image']['name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_folder = 'images/' . $update_image;

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $message[] = 'Image is too large';
        } else {
            $image_update_query = mysqli_query($conn, "UPDATE `users` SET image = '$update_image' WHERE id = '$user_id'") or die('Query failed');
            if ($image_update_query) {
                move_uploaded_file($update_image_tmp_name, $update_image_folder);
            }
            $message[] = 'Image updated successfully!';
        }
    }
}
?>

<?php @include("header.php") ?>
<?php @include("navbar.php") ?>

<div class="container my-5">
    <?php
    $select = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('Query failed');
    if (mysqli_num_rows($select) > 0) {
        $fetch = mysqli_fetch_assoc($select);
    }
    ?>

    <form action="" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
        <div class="row">
            <div class="col-md-4 text-center mb-3">
                <?php
                if ($fetch['image'] == '') {
                    echo '<img src="images/default-avatar.png" class="img-fluid rounded-circle" alt="Profile Image">';
                } else {
                    echo '<img src="images/' . $fetch['image'] . '" class="img-fluid rounded-circle" alt="Profile Image" style="width: 200px;aspect-ratio:1">';
                }
                ?>
                <?php
                if (isset($message)) {
                    foreach ($message as $msg) {
                        echo '<div class="alert alert-info mt-2">' . htmlspecialchars($msg) . '</div>';
                    }
                }
                ?>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Update Profile</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="update_name">Username:</label>
                                    <input type="text" id="update_name" name="update_name" value="<?php echo htmlspecialchars($fetch['name']); ?>" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="update_email">Email:</label>
                                    <input type="email" id="update_email" name="update_email" value="<?php echo htmlspecialchars($fetch['email']); ?>" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="update_image">Update Profile Picture:</label>
                                    <input type="file" id="update_image" name="update_image" accept="image/jpg, image/jpeg, image/png" class="form-control-file">
                                </div>
                                <div class="form-group">
                                    <label for="contact">Contact:</label>
                                    <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($fetch['contact']); ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="address">Address:</label>
                                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($fetch['address']); ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description:</label>
                                    <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($fetch['description']); ?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="hidden" name="old_pass" value="<?php echo htmlspecialchars($fetch['password']); ?>">
                                <div class="form-group">
                                    <label for="update_pass">Old Password:</label>
                                    <input type="password" id="update_pass" name="update_pass" placeholder="Enter old password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="new_pass">New Password:</label>
                                    <input type="password" id="new_pass" name="new_pass" placeholder="Enter new password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="confirm_pass">Confirm New Password:</label>
                                    <input type="password" id="confirm_pass" name="confirm_pass" placeholder="Confirm new password" class="form-control">
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php @include("footer.php") ?>
