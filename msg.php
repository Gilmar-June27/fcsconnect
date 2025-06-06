<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: landing-page.php');
    exit;
}

// Fetch current user's details
$user_query = mysqli_query($conn, "SELECT name FROM users WHERE id = '$user_id'") or die('Query failed: ' . mysqli_error($conn));
$user_data = mysqli_fetch_assoc($user_query);
$current_user_name = htmlspecialchars($user_data['name']);

// Mark messages as read
$update_messages = mysqli_query($conn, "UPDATE `message` SET is_read = 1 WHERE user_id = '$user_id' AND is_read = 0") or die('Query failed: ' . mysqli_error($conn));

// Fetch users who have exchanged messages with the current user
$query = "
    SELECT u.id, u.name, u.email, u.user_type, u.image, u.status, MAX(m.created_at) as last_message_time
    FROM users u
    JOIN message m ON (u.id = m.admin_id AND m.user_id = '$user_id') OR (u.id = m.user_id AND m.admin_id = '$user_id')
    WHERE u.id != '$user_id'
    GROUP BY u.id, u.name, u.email, u.user_type, u.image, u.status
    ORDER BY last_message_time DESC
";

$select_users = mysqli_query($conn, $query) or die('Query failed: ' . mysqli_error($conn));

// Define a default image URL
$default_image_url = 'images/default-avatar.png'; // Replace with the path to your default image
?>

<?php @include("header.php") ?>
<?php @include("navbar.php") ?>

<main class="py-5">
    <div class="container">
            <!-- Section Title -->
<div class="container section-title" data-aos="fade-up">
  <p><span>Mes</span><span class="description-title">sage</span></p>
</div><!-- End Section Title -->

        <div class="d-flex flex-column align-items-center">
            <?php
            if (mysqli_num_rows($select_users) > 0) {
                while ($fetch_users = mysqli_fetch_assoc($select_users)) {
                    $status_class = $fetch_users['status'] == 'activate' ? 'text-success' : 'text-danger';
                    $admin_image = !empty($fetch_users['image']) ? 'images/' . htmlspecialchars($fetch_users['image']) : $default_image_url;

                    $admin_id = $fetch_users['id'];
                    $last_message_query = mysqli_query($conn, "
                        SELECT * FROM `message`
                        WHERE (user_id = '$user_id' AND admin_id = '$admin_id')
                        OR (user_id = '$admin_id' AND admin_id = '$user_id')
                        ORDER BY created_at DESC
                        LIMIT 1
                    ") or die('Query failed: ' . mysqli_error($conn));
                    $last_message = mysqli_fetch_assoc($last_message_query);
            ?>
             <div class="card w-100 mb-3">
            <a href="chatsystem.php?admin_id=<?php echo $fetch_users['id']; ?>&scroll_to_user=<?php echo $fetch_users['id']; ?>">
           
                <div class="card-body d-flex align-items-center">
                    <img src="<?php echo $admin_image; ?>" alt="<?php echo htmlspecialchars($fetch_users['name']); ?>'s image" class="rounded-circle me-3" width="60" height="60">
                    <div class="flex-grow-1">
                        <h5 class="mb-0"><?php echo htmlspecialchars($fetch_users['name']); ?></h5>
                        <p class="small text-muted mb-0">
                            <?php
                                if ($last_message) {
                                    $last_message_sender_name = $last_message['user_id'] == $user_id ? 'You: ' : '';
                                    echo "<strong>{$last_message_sender_name}</strong> " . htmlspecialchars($last_message['message']);
                                }
                            ?>
                        </p>
                        <small class="text-muted">
                            <?php echo $last_message ? date('F d, Y h:i A', strtotime($last_message['created_at'])) : ''; ?>
                        </small>
                    </div>
                    <!-- <a href="chatsystem.php?admin_id=<?php echo $fetch_users['id']; ?>&scroll_to_user=<?php echo $fetch_users['id']; ?>" class="btn btn-outline-primary btn-sm ms-3">Chat</a> -->
                    <span class="<?php echo $status_class; ?>"><i class="bi bi-circle-fill"></i> <?php echo $fetch_users['status'] == 'activate' ? 'Active' : 'Inactive'; ?></span>
                </div>
                </a>
            </div>
            
            <?php
                }
            } else {
                echo '<p class="text-center btn btn-outline-danger">No messages yet! You can message first the others.</p>';
            }
            ?>
        </div>
    </div>
</main>

<?php @include("footer.php") ?>
