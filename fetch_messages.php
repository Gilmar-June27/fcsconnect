<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];
$admin_id = isset($_GET['admin_id']) ? $_GET['admin_id'] : '';

if (!isset($user_id) || empty($admin_id)) {
    http_response_code(400);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$query = "
    SELECT m.*, u.image as user_image, a.image as admin_image
    FROM `message` m
    LEFT JOIN users u ON m.user_id = u.id
    LEFT JOIN users a ON m.admin_id = a.id
    WHERE (m.user_id = '$user_id' AND m.admin_id = '$admin_id')
    OR (m.user_id = '$admin_id' AND m.admin_id = '$user_id')
    ORDER BY m.created_at DESC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed: ' . mysqli_error($conn)]);
    exit;
}

$messages = [];
while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = $row;
}

header('Content-Type: application/json');
echo json_encode($messages);
?>
