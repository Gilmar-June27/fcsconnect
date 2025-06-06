<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:landing-page.php');
    exit;
}

// Handle delete request
if (isset($_GET['delete_admin_id'])) {
    $delete_admin_id = $_GET['delete_admin_id'];
    mysqli_query($conn, "DELETE FROM `added_admins` WHERE user_id = '$user_id' AND admin_id = '$delete_admin_id'") or die('query failed');
    header('location: investment.php'); // Replace with your actual page name
    exit;
}

// Fetch admins
$select_admins = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed');

// Handle add admin request
if (isset($_POST['add_admin_id'])) {
    $admin_id = $_POST['add_admin_id'];

    // Check if the admin has already been added by the user
    $check_admin = mysqli_query($conn, "SELECT * FROM `added_admins` WHERE user_id = '$user_id' AND admin_id = '$admin_id'") or die('query failed');
    if (mysqli_num_rows($check_admin) == 0) {
        mysqli_query($conn, "INSERT INTO `added_admins` (user_id, admin_id) VALUES ('$user_id', '$admin_id')") or die('query failed');
        $message_add[] = "Added successfully";
    }
}

// Fetch the added admins
$added_admins_query = mysqli_query($conn, "SELECT admin_id FROM `added_admins` WHERE user_id = '$user_id'") or die('query failed');
$added_admins = [];
while ($row = mysqli_fetch_assoc($added_admins_query)) {
    $added_admins[] = $row['admin_id'];
}
?>

<?php @include("header.php"); ?>
<?php @include("navbar.php"); ?>

<!-- Display messages -->
 <!-- Message Display -->
 <?php if (!empty($message_add)): ?>
        <div class="message-box" style="background: #efdece; color: green;">
            <?php foreach ($message_add as $msg): ?>
                <span><?php echo htmlspecialchars($msg); ?></span>
            <?php endforeach; ?>
            <i class="fas fa-times close-btn" style="cursor: pointer;"></i>
        </div>
    <?php endif; ?>

<!-- Section Title -->
<div class="container section-title" data-aos="fade-up">
  <p><span>Invest</span><span class="description-title">ment</span></p>
</div><!-- End Section Title -->

<div class="container">
<div class="row">
    <?php
    if (mysqli_num_rows($select_admins) > 0) {
        while ($fetch_admin = mysqli_fetch_assoc($select_admins)) {
            $contact = !empty($fetch_admin['contact']) ? htmlspecialchars($fetch_admin['contact']) : 'No contact';
            $address = !empty($fetch_admin['address']) ? htmlspecialchars($fetch_admin['address']) : 'No address';
            $description = !empty($fetch_admin['description']) ? htmlspecialchars($fetch_admin['description']) : 'No description';
            $admin_image = $fetch_admin['image'] ? 'images/' . $fetch_admin['image'] : 'images/default-avatar.png';
           ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <img src="<?php echo $admin_image; ?>" srcset="" class="rounded-circle w-25 m-auto">
                        <h5 class="card-title"><?php echo htmlspecialchars($fetch_admin['name']); ?></h5>
                        <p class="card-text"><strong>Contact:</strong> <?php echo $contact; ?></p>
                        <p class="card-text"><strong>Address:</strong> <?php echo $address; ?></p>
                        <p class="card-text"><strong>Description:</strong> <?php echo $description; ?></p>
                        <form method="post">
                            <button class="btn btn-outline-primary" type="submit" name="add_admin_id" value="<?php echo htmlspecialchars($fetch_admin['id']); ?>">
                                Add
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo '<div class="col-12 text-center"><p>No admins found!</p></div>';
    }
    ?>
</div>

</div>
<section class="light-background">
<div class="container">
    <input class="form-control mr-sm-1 w-25" type="search" name="search_add_investment" id="searchAdmin" placeholder="Search" aria-label="Search">
</div>
<!-- Display Added Admins -->
<div class="container admin-results">
    <?php
    foreach ($added_admins as $admin_id) {
        $admin_query = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id' AND user_type = 'admin'") or die('query failed');
        $admin_data = mysqli_fetch_assoc($admin_query);

        if ($admin_data) {
            $investment_query = mysqli_query($conn, "SELECT * FROM `investments` WHERE admin_id = '$admin_id' ORDER BY id DESC LIMIT 1") or die('query failed');
            $investment_data = mysqli_fetch_assoc($investment_query);

            $select_posts = mysqli_query($conn, "SELECT * FROM `posts` WHERE admin_id = '$admin_id'") or die('query failed');

            $breakdown_query = mysqli_query($conn, "SELECT * FROM `breakdowns` WHERE admin_id = '$admin_id'") or die('query failed');
            $breakdown_total = 0;
            $breakdowns = [];
            while ($breakdown_data = mysqli_fetch_assoc($breakdown_query)) {
                $breakdowns[] = $breakdown_data;
                $breakdown_total += (int)$breakdown_data['money_invest'];
            }
            ?>
            <div class="card my-4">
                <div class="card-body">
                    <h5 class="card-title" style="font-size:25px;font-weight:bolder;"><?= htmlspecialchars($admin_data['name']); ?></h5>
                    <p class="card-text"><strong>Contact:</strong> <?= htmlspecialchars($admin_data['contact'] ?? 'N/A'); ?></p>
                    <p class="card-text"><strong>Address:</strong> <?= htmlspecialchars($admin_data['address'] ?? 'N/A'); ?></p>
                    <p class="card-text"><strong>Description:</strong> <?= htmlspecialchars($admin_data['description'] ?? 'N/A'); ?></p>
                    <p class="card-text"><strong>Fundings:</strong> <?= htmlspecialchars($investment_data['funding'] ?? 'N/A'); ?></p>
                    <h6 class="mt-3" style="font-size:25px;font-weight:bolder;">Breakdown</h6>
                    <?php if (!empty($breakdowns)) { ?>
                        <ul class="list-group">
                            <?php foreach ($breakdowns as $breakdown) { ?>
                                <li class="list-group-item">
                                    <strong><?= htmlspecialchars($breakdown['breakdown']); ?>:</strong> <?= htmlspecialchars(number_format($breakdown['money_invest'])); ?>
                                </li>
                            <?php } ?>
                            <li class="list-group-item"><strong>Total Investment:</strong> <?= number_format($breakdown_total); ?></li>
                        </ul>
                    <?php } else { ?>
                        <p>No breakdown available.</p>
                    <?php } ?>

                    <h6 class="mt-3" style="font-size:25px;font-weight:bolder;">Profit Sharing</h6>
                    <p style="font-weight:bolder"><?= htmlspecialchars($investment_data['profit_sharing_percentage'] ?? 'N/A'); ?></p>

                    <h6 class="mt-3" style="font-size:25px;font-weight:bolder;">Plans</h6>
                    <div class="overflow-auto" style="max-height: 200px;">
                    <?php
                        if (mysqli_num_rows($select_posts) > 0) {
                            while ($fetch_posts = mysqli_fetch_assoc($select_posts)) {
                                $date = date('F d, Y h:i A', strtotime($fetch_posts['created_at']));
                                $image = $fetch_posts['image'];
                                ?>
                                <div class="card p-3 m-3" style="border:1px solid lightgray">
                                    <?php if (!empty($image)) { ?>
                                        <img src="images/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($fetch_posts['title']); ?>" style="width: 222px; aspect-ratio: 1;">
                                    <?php } ?>
                                    <div class="card-content">
                                        <h2 class="card-title" style="font-size:25px;font-weight:bolder;"><?php echo htmlspecialchars($fetch_posts['title']); ?></h2>
                                        <p class="card-description"><?php echo htmlspecialchars($fetch_posts['content']); ?></p>
                                        <p class="card-description"><?php echo htmlspecialchars($date); ?></p>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<p class="empty">No posts available!</p>';
                        }
                    ?>
                    </div>
                    <a href="?delete_admin_id=<?= htmlspecialchars($admin_id); ?>" class="btn btn-outline-danger mt-3" onclick="return confirm('Are you sure you want to delete this admin?');">Delete</a>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#searchAdmin').on('input', function() {
        let query = $(this).val();
        
        $.ajax({
            url: 'search_admins.php',
            method: 'POST',
            data: { query: query },
            success: function(response) {
                $('.container.admin-results').html(response);
            }
        });
    });
});
</script>

<?php @include("footer.php"); ?>