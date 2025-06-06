<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('Location: landing-page.php');
    exit;
}

$select = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('Query failed');
if (mysqli_num_rows($select) > 0) {
    $fetch = mysqli_fetch_assoc($select);
}
?>

<?php @include("header.php") ?>
<?php @include("navbar.php") ?>
<div class="container my-5">
<div class="">
    <div class="row">
        <div class="col-md-4 text-center">
            <?php
            if ($fetch['image'] == '') {
                echo '<img src="images/default-avatar.png" class="img-fluid rounded-circle" alt="Profile Image">';
            } else {
                echo '<img src="images/' . $fetch['image'] . '" class="img-fluid rounded-circle" alt="Profile Image" style="aspect-ratio:1;width:15vw">';
            }
            ?>
            <h3 class="mt-2"><?php echo htmlspecialchars($fetch['name']); ?></h3>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Profile Details</h4>
                    <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($fetch['email']); ?></p>
                    <p class="card-text"><strong>Username:</strong> <?php echo htmlspecialchars($fetch['name']); ?></p>
                    <p class="card-text"><strong>User Type:</strong> <?php echo htmlspecialchars($fetch['user_type']); ?></p>
                    <?php
                    if (empty($fetch['contact']) && empty($fetch['address']) && empty($fetch['description'])) {
                    ?>
                        <p class="text-muted">No additional data available.</p>
                    <?php
                    } else {
                    ?>
                        <p class="card-text"><strong>Contact:</strong> <?php echo htmlspecialchars($fetch['contact']); ?></p>
                        <p class="card-text"><strong>Address:</strong> <?php echo htmlspecialchars($fetch['address']); ?></p>
                        <p class="card-text"><strong>Description:</strong> <?php echo htmlspecialchars($fetch['description']); ?></p>
                    <?php
                    }
                    ?>
                    <a href="update_profile.php" class="btn btn-primary">Update Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php @include("footer.php") ?>
