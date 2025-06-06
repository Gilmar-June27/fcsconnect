<?php
include '../config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location: ../login.php');
    exit;
}

$update_messages = mysqli_query($conn, "UPDATE `message` SET is_read = 1 WHERE admin_id = '$admin_id' AND is_read = 0") or die('Query failed: ' . mysqli_error($conn));

// Query to get the latest message for each user
$query = "
    SELECT u.id, u.name, u.email, u.user_type, u.image, u.status, m.message, m.created_at
    FROM users u
    JOIN message m ON u.id = m.user_id
    WHERE u.user_type = 'user' AND m.admin_id = '$admin_id'
    AND m.created_at = (
        SELECT MAX(created_at)
        FROM message
        WHERE user_id = u.id AND admin_id = '$admin_id'
    )
    ORDER BY m.created_at DESC
";

$select_users = mysqli_query($conn, $query) or die('Query failed: ' . mysqli_error($conn));

// Define a default image URL
$default_image_url = 'images/default-avatar.png'; // Replace with the path to your default image
?>

<?php @include("admin_header.php") ?>
<?php @include("admin_navbar.php") ?>

<main class="py-5">
    <div class="container">
        <!-- Section Title -->
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title"><span class="text-primary">Mes</span><span class="text-secondary">sage</span></h2>
        </div>
        <!-- End Section Title -->

        <div class="d-flex flex-column align-items-center">
            <?php
            while ($fetch_users = mysqli_fetch_assoc($select_users)) {
                // Determine which image to display
                $status_class = $fetch_users['status'] == 'activate' ? 'text-success' : 'text-danger';
                $user_image = !empty($fetch_users['image']) ? 'images/' . htmlspecialchars($fetch_users['image']) : $default_image_url;

                // Fetch the last message for the current user
                $user_id = $fetch_users['id'];
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
                <a href="admin-chatsystem.php?user_id=<?php echo htmlspecialchars($fetch_users['id']); ?>">
                    <div class="card-body d-flex align-items-center">
                        <img src="<?php echo $user_image; ?>" alt="<?php echo htmlspecialchars($fetch_users['name']); ?>'s image" class="rounded-circle me-3" width="60" height="60">
                        <div class="flex-grow-1">
                            <h5 class="mb-0"><?php echo htmlspecialchars($fetch_users['name']); ?></h5>
                            <p class="small text-muted mb-0">
                                <?php
                                if ($last_message) {
                                    $last_message_sender_name = $last_message['admin_id'] == $user_id ? 'You: ' : '';
                                    echo "<strong>{$last_message_sender_name}</strong> " . htmlspecialchars($last_message['message']);
                                } else {
                                    echo 'No messages yet!';
                                }
                                ?>
                            </p>
                            <small class="text-muted">
                                <?php echo $last_message ? date('F d, Y h:i A', strtotime($last_message['created_at'])) : ''; ?>
                            </small>
                        </div>
                        <span class="ms-3 <?php echo $status_class; ?>">
                            <i class="bi bi-circle-fill"></i> <?php echo $fetch_users['status'] == 'activate' ? 'Active' : 'Inactive'; ?>
                        </span>
                    </div>
                </a>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</main>

<?php @include("admin_footer.php") ?>

<style>
    .section-title {
        font-size: 2.5rem;
        font-weight: bold;
        color: #2e8b57;
    }

    .text-primary {
        color: #007bff !important;
    }

    .text-secondary {
        color: #6c757d !important;
    }
</style>
