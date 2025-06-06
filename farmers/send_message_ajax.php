<?php
include '../config.php';
session_start();

$admin_id = $_SESSION['admin_id'];
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';

if (!isset($admin_id) || empty($user_id)) {
    echo json_encode(['error' => 'User not found']);
    exit;
}

$message_content = isset($_POST['message']) ? mysqli_real_escape_string($conn, $_POST['message']) : '';
$image_path = '';

if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_name = basename($_FILES['image']['name']);
    $file_target = 'images/' . $file_name;

    if (move_uploaded_file($file_tmp, $file_target)) {
        $image_path = $file_target;
    }
}

if (!empty($message_content) || !empty($image_path)) {
    $insert_query = "INSERT INTO `message` (user_id, message, image, admin_id) VALUES ('$user_id', '$message_content', '$image_path', '$admin_id')";
    if (mysqli_query($conn, $insert_query)) {
        // Return the new message HTML as a JSON response
        $messageHTML = '<div class="d-flex align-items-start mb-4 justify-content-end">
                            <div class="p-3 rounded bg-primary text-white" style="max-width: 70%;">
                                ' . (!empty($image_path) ? '<img src="' . $image_path . '" alt="Image" class="img-fluid mb-2" style="max-width: 100%;">' : '') . '
                                <p class="mb-1">' . htmlspecialchars($message_content) . '</p>
                                <small class="d-block text-muted">' . date('F d, Y h:i A') . '</small>
                            </div>
                            <img src="' . (!empty($admin_data['image']) ? 'images/' . htmlspecialchars($admin_data['image']) : $default_image_url) . '" alt="' . htmlspecialchars($admin_data['name']) . ' image" class="rounded-circle ml-2" style="width: 40px; height: 40px;">
                        </div>';

        echo json_encode(['success' => true, 'messageHTML' => $messageHTML]);
    } else {
        echo json_encode(['error' => 'Failed to send message']);
    }
} else {
    echo json_encode(['error' => 'Message is empty']);
}
