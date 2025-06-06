<?php
include '../config.php';
session_start();
$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../login.php');
    exit;
}  

header('Content-Type: application/json');

// Get counts by day
$query = "SELECT DATE(created_at) as date, user_type, COUNT(*) as count 
          FROM users 
          WHERE created_at >= DATE(NOW()) - INTERVAL 7 DAY 
          GROUP BY DATE(created_at), user_type";
$result = mysqli_query($conn, $query);
$day_data = ['admin' => [], 'user' => [], 'super_admin' => []];
while ($row = mysqli_fetch_assoc($result)) {
    $day_data[$row['user_type']][] = ['date' => $row['date'], 'count' => (int) $row['count']];
}

// Get counts by week
$query = "SELECT YEARWEEK(created_at, 1) as week, user_type, COUNT(*) as count 
          FROM users 
          WHERE created_at >= DATE(NOW()) - INTERVAL 4 WEEK 
          GROUP BY YEARWEEK(created_at, 1), user_type";
$result = mysqli_query($conn, $query);
$week_data = ['admin' => [], 'user' => [], 'super_admin' => []];
while ($row = mysqli_fetch_assoc($result)) {
    $week_data[$row['user_type']][] = ['week' => $row['week'], 'count' => (int) $row['count']];
}

// Get counts by month
$query = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, user_type, COUNT(*) as count 
          FROM users 
          WHERE created_at >= DATE(NOW()) - INTERVAL 6 MONTH 
          GROUP BY DATE_FORMAT(created_at, '%Y-%m'), user_type";
$result = mysqli_query($conn, $query);
$month_data = ['admin' => [], 'user' => [], 'super_admin' => []];
while ($row = mysqli_fetch_assoc($result)) {
    $month_data[$row['user_type']][] = ['month' => $row['month'], 'count' => (int) $row['count']];
}

// Output JSON
$data = [
    'day_data' => $day_data,
    'week_data' => $week_data,
    'month_data' => $month_data
];
echo json_encode($data);
?>
