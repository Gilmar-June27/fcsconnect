<?php
include '../config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Fetch the current details of the breakdown
    $breakdown_query = mysqli_query($conn, "SELECT * FROM `breakdowns` WHERE id = '$id' AND admin_id = '$admin_id'") or die('query failed');
    if (mysqli_num_rows($breakdown_query) > 0) {
        $breakdown_data = mysqli_fetch_assoc($breakdown_query);
    } else {
        $message[] = 'Invalid request!';
        header('location: admin_investment.php');
        exit();
    }
}

if (isset($_POST['update_breakdown'])) {
    $breakdown = mysqli_real_escape_string($conn, $_POST['breakdown']);
    $money_invest = mysqli_real_escape_string($conn, $_POST['money_invest']);

    $update_query = mysqli_query($conn, "UPDATE `breakdowns` SET breakdown = '$breakdown', money_invest = '$money_invest' WHERE id = '$id' AND admin_id = '$admin_id'") or die('query failed');

    if ($update_query) {
        $message[] = 'Breakdown updated successfully!';
        header('location: admin_investment.php');
        exit();
    } else {
        $message[] = 'Breakdown could not be updated!';
    }
}
?>

<?php @include("admin_header.php"); ?>
<?php @include("admin_navbar.php"); ?>

<div class="container">
    <div class="form-section">
        <h2>Edit Breakdown</h2>
        <form action="" method="post" class="styled-form">
            <input type="text" name="breakdown" placeholder="Breakdown" value="<?php echo htmlspecialchars($breakdown_data['breakdown']); ?>" required>
            <input type="text" name="money_invest" placeholder="Money Invest" value="<?php echo htmlspecialchars($breakdown_data['money_invest']); ?>" required>
            <input type="submit" name="update_breakdown" value="Update Breakdown" class="btn btn-primary">
        </form>
    </div>
</div>

<?php @include("admin_footer.php"); ?>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    width: 80%;
    margin: 0 auto;
    padding: 20px;
}

.form-section {
    background-color: #fff;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    border-radius: 8px;
}

h2 {
    margin-bottom: 20px;
    color: #333;
}

.styled-form input[type="text"], 
.styled-form input[type="number"], 
.styled-form input[type="submit"] {
    width: calc(100% - 20px);
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
}

/* .styled-form input[type="submit"] {
    background-color: #28a745;
    color: #fff;
    border: none;
    cursor: pointer;
}

.styled-form input[type="submit"]:hover {
    background-color: #218838;
} */
</style>
