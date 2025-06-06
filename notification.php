<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:landing-page.php');
    exit;
}
?>

<?php @include("header.php") ?>
<?php @include("navbar.php") ?>

<div class="container my-5">
  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
  <p><span>Notifi</span><span class="description-title">cations</span></p>
</div><!-- End Section Title -->

    <div class="m-2">
        <?php
        // Mark notifications as read for the current user
        mysqli_query($conn, "UPDATE `notifications` SET is_read = 1 WHERE user_id = '$user_id' AND is_read = 0") or die('query failed');

        // Fetch notifications for the current user
        $select_notifications = mysqli_query($conn, "SELECT * FROM `notifications` WHERE user_id = '$user_id' ORDER BY created_at DESC") or die('query failed');

        // Check if there are any notifications
        if (mysqli_num_rows($select_notifications) > 0) {
            // Output notifications
            while ($notification = mysqli_fetch_assoc($select_notifications)) {
                // Convert date format
                $date = date('F d, Y h:i A', strtotime($notification['created_at']));
        ?>
                <div class="col">
                    <div class="card h-100 shadow-sm m-2">
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($notification['message']); ?></h5>
                            <p class="card-text text-muted">by: <?= htmlspecialchars($notification['msg']); ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <small class="text-muted"><?= $date; ?></small>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo '<p class="text-center text-muted w-100 btn btn-outline-danger">No notifications available.</p>';
        }
        ?>
    </div>
</div>

<?php @include("footer.php") ?>

<style>
/* Hide scrollbar */
::-webkit-scrollbar {
    width: 0px;
}
</style>
