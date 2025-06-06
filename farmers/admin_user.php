<?php
include '../config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../login.php');
}


// Fetch user details
$user_query = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id' AND user_type = 'user'") or die('Query failed');
$user_data = mysqli_fetch_assoc($user_query);

if (!$user_data) {
    echo 'User not found!';
    exit;
}

$status_class = $user_data['status'] == 'active' ? 'success' : 'danger';

// Check if the current user has blocked this user
$check_block_status = mysqli_query($conn, "SELECT * FROM `reports` WHERE reporter_id = '{$_SESSION['admin_id']}' AND reported_admin_id = '$user_id' AND blocked = 1") or die('Query failed');
$is_blocked = mysqli_num_rows($check_block_status) > 0;

if ($is_blocked) {
    $block_data = mysqli_fetch_assoc($check_block_status);
    $block_date = new DateTime($block_data['block_date']);
    $current_date = new DateTime();
    $interval = $current_date->diff($block_date);
    $days_blocked = $interval->days;
    $can_unblock = $days_blocked >= 5;
    $remaining_days = 5 - $days_blocked;
} else {
    $can_unblock = false;
    $remaining_days = 0;
}

?>

<?php @include("header.php") ?>
<?php @include("navbar.php") ?>

<main class="container mt-4">
    <section class="row mb-4">
        <div class="col-md-4 text-center">
            <?php 
                $user_image = $user_data['image'] ? 'images/' . $user_data['image'] : 'images/default-avatar.png';
            ?>
            <img src="<?php echo $user_image; ?>" alt="User Image" class="img-fluid rounded-circle" style="width: 150px; height: 150px;">
            <div class="mt-2">
                <span class="badge <?php echo 'bg-' . $status_class; ?>" style="width: 20px; transform: translateY(-35px); border: 2px solid #fff; height: 20px; display: inline-flex; margin-top: -182px; margin-left: 100px;"></span>
            </div>
        </div>
        <!-- <div class="col-md-8">
            <h2><?php echo $user_data['name']; ?></h2>
            <p><?php echo $user_data['bio']; ?></p>
            <div class="d-flex align-items-center mb-3">
                <?php if (!$is_blocked) { ?>
                    <a href="chatsystem.php?user_id=<?php echo $user_data['id']; ?>" class="btn btn-primary me-2">Message</a>
                    <button class="btn btn-secondary" id="reportButton">Report</button>
                <?php } else { ?>
                    <form action="" method="post">
                        <input type="hidden" name="reported_user_id" value="<?php echo $user_data['id']; ?>">
                        <button type="submit" name="unblock_user" class="btn btn-warning" <?php echo !$can_unblock ? 'disabled' : ''; ?>>Unblock</button>
                        <?php if (!$can_unblock) { ?>
                            <p class="text-danger">You can unblock this user after <?php echo $remaining_days; ?> day(s).</p>
                        <?php } else { ?>
                            <p class="text-success">You can now unblock this user.</p>
                        <?php } ?>
                    </form>
                <?php } ?>
            </div>
        </div> -->
    </section>
</main>

<?php
// PHP code for handling reporting, blocking, and unblocking actions can be added here.
?>
