<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
$admin_id = isset($_GET['admin_id']) ? $_GET['admin_id'] : '';

if (!isset($user_id) || empty($admin_id)) {
    header('Location: landing-page.php');
    exit;
}


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
            exit;
        }
    } else {
        echo 'Invalid file type!';
        exit;
    }
}

// Handle form submission
if (isset($_POST['message']) && !empty($_POST['message']) || !empty($image_path)) {
    $message_content = mysqli_real_escape_string($conn, $_POST['message']);

    // Insert message into the database
    $insert_query = "INSERT INTO `message` (user_id, name, email, number, message, image, admin_id) VALUES ('$user_id', '{$_SESSION['name']}', '{$_SESSION['email']}', '{$_SESSION['number']}', '$message_content', '$image_path', '$admin_id')";
    if (mysqli_query($conn, $insert_query)) {
        // Fetch updated messages
        $messages_query = mysqli_query($conn, "
            SELECT * FROM `message`
            WHERE (user_id = '$user_id' AND admin_id = '$admin_id')
            OR (user_id = '$admin_id' AND admin_id = '$user_id')
            ORDER BY created_at DESC
        ");

        // Generate message HTML
        $output = '';
        while ($message = mysqli_fetch_assoc($messages_query)) {
            $is_user_message = $message['user_id'] == $user_id;
            $message_class = $is_user_message ? 'bg-primary text-white' : 'bg-secondary text-white';
            $image_html = !empty($message['image']) ? '<img src="' . htmlspecialchars($message['image']) . '" alt="Image" class="img-fluid mb-2" style="max-width: 100%;">' : '';
            $output .= '
            <div class="d-flex align-items-start mb-4 ' . ($is_user_message ? 'justify-content-end' : '') . '">
                ' . (!$is_user_message ? '<img src="' . (!empty($admin_data['image']) ? 'images/' . htmlspecialchars($admin_data['image']) : 'images/default.png') . '" alt="' . htmlspecialchars($admin_data['name']) . '\'s image" class="rounded-circle m-2" style="width: 40px; height: 40px;">' : '') . '
                <div class="p-3 rounded ' . $message_class . '" style="max-width: 70%;">
                    ' . $image_html . '
                    <p class="mb-1">' . htmlspecialchars($message['message']) . '</p>
                    <small class="d-block text-muted">' . date('F d, Y h:i A', strtotime($message['created_at'])) . '</small>
                </div>
                ' . ($is_user_message ? '<img src="' . (!empty($user_data['image']) ? 'images/' . htmlspecialchars($user_data['image']) : 'images/default.png') . '" alt="' . htmlspecialchars($user_data['name']) . '\'s image" class="rounded-circle ml-2" style="width: 40px; height: 40px;">' : '') . '
            </div>';
        }

        echo $output;
    } else {
        echo 'Message insertion failed!';
    }
} else {
    echo 'Message or image not provided!';
}
?>
