<?php
include '../config.php';
session_start();

$admin_id = $_SESSION['admin_id'];
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';

if (!isset($admin_id) || empty($user_id)) {
    header('location:../login.php');
    exit;
}

// Mark unread messages as read
$update_messages = mysqli_query($conn, "UPDATE `message` SET is_read = 1 WHERE admin_id = '$admin_id' AND is_read = 0") or die('Query failed: ' . mysqli_error($conn));

// Fetch user details
$user_query = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('Query failed');
$user_data = mysqli_fetch_assoc($user_query);

if (!$user_data) {
    echo 'User not found!';
    exit;
}

// Fetch admin details
$admin_query = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id'") or die('Query failed');
$admin_data = mysqli_fetch_assoc($admin_query);

// Check if 'number' exists in the user data
$user_number = isset($user_data['number']) ? $user_data['number'] : '';

// Handle file upload
$image_path = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_name = basename($_FILES['image']['name']);
    $file_target = 'images/' . $file_name;

    // Validate file type and size if necessary
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    if (in_array($file_extension, $allowed_extensions)) {
        if (move_uploaded_file($file_tmp, $file_target)) {
            $image_path = $file_target;
        } else {
            echo 'Image upload failed!';
        }
    } else {
        echo 'Invalid file type!';
    }
}

// Handle form submission
if (isset($_POST['send_message']) && (!empty($_POST['message']) || !empty($image_path))) {
    $message_content = mysqli_real_escape_string($conn, $_POST['message']);

    // Insert message into the database
    $insert_query = "INSERT INTO `message` (user_id, name, email, number, message, image, admin_id) VALUES ('$admin_id', '{$admin_data['name']}', '{$admin_data['email']}', '$user_number', '$message_content', '$image_path', '$user_id')";
    mysqli_query($conn, $insert_query) or die('Query failed: ' . mysqli_error($conn));

    // Redirect to avoid form resubmission on page reload
    header('Location: ' . $_SERVER['PHP_SELF'] . '?user_id=' . urlencode($user_id));
    exit;
}

// Fetch existing messages
$messages_query = mysqli_query($conn, "
    SELECT * FROM `message`
    WHERE (user_id = '$admin_id' AND admin_id = '$user_id')
    OR (user_id = '$user_id' AND admin_id = '$admin_id')
    ORDER BY created_at DESC
") or die('Query failed: ' . mysqli_error($conn));

// Define default image URL
$default_image_url = 'images/default-avatar.png'; // Replace with the path to your default image
?>

<?php @include("admin_header.php") ?>
<?php @include("admin_navbar.php") ?>

<main class="container py-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <img src="<?php echo !empty($user_data['image']) ? 'images/' . htmlspecialchars($user_data['image']) : $default_image_url; ?>" alt="<?php echo htmlspecialchars($user_data['name']); ?>'s image" class="rounded-circle mr-3" style="width: 50px; height: 50px;">
                    <h5 class="mb-0"><?php echo htmlspecialchars($user_data['name']); ?></h5>
                </div>
                <div class="card-body message-display p-4" style="height: 400px; overflow-y: auto;">
                    <?php
                    if (mysqli_num_rows($messages_query) > 0) {
                        while ($message = mysqli_fetch_assoc($messages_query)) {
                            $is_admin_message = $message['user_id'] == $admin_id;
                            $message_class = $is_admin_message ? 'bg-primary text-white' : 'bg-secondary text-white';
                            ?>
                            <div class="d-flex align-items-start mb-4 <?php echo $is_admin_message ? 'justify-content-end' : ''; ?>">
                                <?php if (!$is_admin_message): ?>
                                    <img src="<?php echo !empty($user_data['image']) ? 'images/' . htmlspecialchars($user_data['image']) : $default_image_url; ?>" alt="<?php echo htmlspecialchars($user_data['name']); ?>'s image" class="rounded-circle m-2" style="width: 40px; height: 40px;">
                                <?php endif; ?>
                                <div class="p-3 rounded <?php echo $message_class; ?>" style="max-width: 70%;">
                                    <?php if (!empty($message['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($message['image']); ?>" alt="Image" class="img-fluid mb-2" style="max-width: 100%;">
                                    <?php endif; ?>
                                    <p class="mb-1"><?php echo htmlspecialchars($message['message']); ?></p>
                                    <small class="d-block text-muted" data-time="<?php echo $message['created_at']; ?>">
                                            <?php echo date('F d, Y h:i A', strtotime($message['created_at'])); ?>
                                        </small>
                                </div>
                                <?php if ($is_admin_message): ?>
                                    <img src="<?php echo !empty($admin_data['image']) ? 'images/' . htmlspecialchars($admin_data['image']) : $default_image_url; ?>" alt="<?php echo htmlspecialchars($admin_data['name']); ?>'s image" class="rounded-circle ml-2" style="width: 40px; height: 40px;">
                                <?php endif; ?>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<p class="text-center text-muted">No messages found.</p>';
                    }
                    ?>
                </div>
                <div class="card-footer bg-light">
                    <form action="" method="POST" enctype="multipart/form-data" class="d-flex">
                        <textarea name="message" class="form-control mr-2" rows="2" placeholder="Type your message here..."></textarea>
                        
                        <img id="imagePreview" src="#" alt="Image Preview" style="display: none; width: 50px; height: 50px; border-radius: 50%; margin-right: 10px;">
                        <label for="imageUpload" class="file-input-icon mr-2"></label>
                        <input type="file" name="image" id="imageUpload" style="display: none;" accept="image/*" onchange="previewImage(event)">
                        <button type="submit" name="send_message" class="btn btn-primary ml-2">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function previewImage(event) {
    var imagePreview = document.getElementById('imagePreview');
    var file = event.target.files[0];

    if (file) {
        var reader = new FileReader();

        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
        };

        reader.readAsDataURL(file);
    } else {
        imagePreview.style.display = 'none';
    }
}




  function updateTimestamps() {
        const timeElements = document.querySelectorAll('[data-time]');
        timeElements.forEach(element => {
            const timeString = element.getAttribute('data-time');
            if (timeString) {
                const time = new Date(timeString);
                const now = new Date();
                const diffMs = now - time; // Difference in milliseconds
                const diffMins = Math.floor(diffMs / 60000); // Difference in minutes
                const diffHrs = Math.floor(diffMins / 60); // Difference in hours
                const diffDays = Math.floor(diffHrs / 24); // Difference in days

                let displayTime = '';

                if (diffDays > 0) {
                    displayTime = `${diffDays} day ago`;
                } else if (diffHrs > 0) {
                    displayTime = `${diffHrs} hour ago`;
                } else if (diffMins > 0) {
                    displayTime = `${diffMins} minute ago`;
                } else {
                    displayTime = 'Just now';
                }

                element.textContent = displayTime;
            }
        });
    }

    // Update timestamps on page load
    updateTimestamps();

    // Update timestamps every minute
    setInterval(updateTimestamps, 60000);
</script>

<?php @include("admin_footer.php") ?>

<style>
    .message-display {
        height: 400px;
        overflow-y: auto;
        background-color: #f9f9f9;
        padding: 20px;
    }

    .message-display .bg-primary {
        background-color: #007bff !important;
    }

    .message-display .bg-secondary {
        background-color: #6c757d !important;
    }
 /* Style the label to look like an image icon */
.file-input-icon {
    display: inline-block;
    width: 40px;
    margin:10px;
    height: 40px;
    background-image: url('../images/image.png'); /* Replace with your image icon path */
    background-size: cover;
    background-position: center;
    cursor: pointer;
    border-radius: 50%; /* Optional: Makes the icon circular */
}
