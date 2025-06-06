<?php
include '../config.php';
session_start();

$super_admin_id = $_SESSION['super_admin_id'];
if (!isset($super_admin_id)) {
   header('location:login.php');
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('query failed');
}

if (isset($_GET['block'])) {
    $block_id = $_GET['block'];
    mysqli_query($conn, "UPDATE `users` SET block = 'yes' WHERE id = '$block_id'") or die('query failed');
}

if (isset($_GET['unblock'])) {
    $unblock_id = $_GET['unblock'];
    mysqli_query($conn, "UPDATE `users` SET block = 'no' WHERE id = '$unblock_id'") or die('query failed');
}

if (isset($_GET['approve'])) {
    $approve_id = intval($_GET['approve']);
    mysqli_query($conn, "UPDATE `products` SET status = 'approved' WHERE id = '$approve_id'") or die('query failed');
}

if (isset($_GET['approve_all'])) {
    mysqli_query($conn, "UPDATE `products` SET status = 'approved' WHERE status = 'processing'") or die('query failed');
}

if (isset($_GET['reject'])) {
    $reject_id = intval($_GET['reject']);
    mysqli_query($conn, "DELETE FROM `products` WHERE id = '$reject_id'") or die('query failed');
}







if (!$conn) {
    echo "Database connection error: " . mysqli_error();
} else {
    // Function to get user and admin counts for a specific month
    function getCountsByMonth($conn, $month, $year) {
        $user_count_sql = "SELECT DATE(created_at) AS date, COUNT(*) as count FROM users 
                           WHERE user_type='user' AND MONTH(created_at) = $month AND YEAR(created_at) = $year 
                           GROUP BY date ORDER BY date";
        
        $admin_count_sql = "SELECT DATE(created_at) AS date, COUNT(*) as count FROM users 
                            WHERE user_type='admin' AND MONTH(created_at) = $month AND YEAR(created_at) = $year 
                            GROUP BY date ORDER BY date";

        $user_count_result = mysqli_query($conn, $user_count_sql);
        $admin_count_result = mysqli_query($conn, $admin_count_sql);

        $user_counts = [];
        $admin_counts = [];
        $dates = [];

        while ($row = mysqli_fetch_assoc($user_count_result)) {
            $dates[] = $row['date'];
            $user_counts[$row['date']] = $row['count'];
        }

        while ($row = mysqli_fetch_assoc($admin_count_result)) {
            if (!isset($user_counts[$row['date']])) {
                $dates[] = $row['date'];
            }
            $admin_counts[$row['date']] = $row['count'];
        }

        // Generate full date range for the month
        function generateDateRange($startDate, $endDate) {
            $dateRange = [];
            $currentDate = strtotime($startDate);
            $endDate = strtotime($endDate);
        
            while ($currentDate <= $endDate) {
                $dateRange[] = date('Y-m-d', $currentDate);
                $currentDate = strtotime("+1 day", $currentDate);
            }
        
            return $dateRange;
        }
        
        // Prepare data for JSON
        $user_data = [];
        $admin_data = [];
        foreach ($dates as $date) {
            $user_data[] = isset($user_counts[$date]) ? $user_counts[$date] : 0;
            $admin_data[] = isset($admin_counts[$date]) ? $admin_counts[$date] : 0;
        }

        return [
            'dates' => $dates,
            'user_data' => $user_data,
            'admin_data' => $admin_data
        ];
    }

    $month = isset($_POST['month']) ? intval($_POST['month']) : date('m');
    $year = isset($_POST['year']) ? intval($_POST['year']) : date('Y');
    $chart_data = getCountsByMonth($conn, $month, $year);

    $dates = json_encode($chart_data['dates']);
    $user_data = json_encode($chart_data['user_data']);
    $admin_data = json_encode($chart_data['admin_data']);
}
?>