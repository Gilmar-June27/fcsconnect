<?php
include '../config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../login.php');
    exit();
}

// Fetch admin details
$admin_query = mysqli_query($conn, "SELECT name, contact, address, description FROM `users` WHERE id = '$admin_id'") or die('query failed');
$admin_data = mysqli_fetch_assoc($admin_query);

// Add Investment
if (isset($_POST['add_invest'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $profit_sharing = mysqli_real_escape_string($conn, $_POST['profit_sharing']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $funding = mysqli_real_escape_string($conn,$_POST['funding']);
    $profit_sharing_percentage = mysqli_real_escape_string($conn,$_POST['profit_sharing_percentage']);

    $add_invest_query = mysqli_query($conn, "INSERT INTO `investments` (admin_id, name, profit_sharing, address, description, funding, profit_sharing_percentage) VALUES ('$admin_id', '$name', '$profit_sharing', '$address', '$description', '$funding', '$profit_sharing_percentage')") or die('query failed');

    if ($add_invest_query) {
        $message[] = 'Investment added successfully!';
    } else {
        $message[] = 'Investment could not be added!';
    }
}

// Add Breakdown
if (isset($_POST['add_breakdown'])) {
    $breakdown = mysqli_real_escape_string($conn, $_POST['breakdown']);
    $money_invest = mysqli_real_escape_string($conn, $_POST['money_invest']);

    $add_breakdown_query = mysqli_query($conn, "INSERT INTO `breakdowns` (admin_id, breakdown, money_invest) VALUES ('$admin_id', '$breakdown', '$money_invest')") or die('query failed');

    if ($add_breakdown_query) {
        $message[] = 'Breakdown added successfully!';
    } else {
        $message[] = 'Breakdown could not be added!';
    }
}

// Fetch all breakdowns
$breakdowns_query = mysqli_query($conn, "SELECT * FROM `breakdowns` WHERE admin_id = '$admin_id'") or die('query failed');
?>

<?php @include("admin_header.php"); ?>
<?php @include("admin_navbar.php"); ?>

<?php if (isset($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ' . htmlspecialchars($msg) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
} ?>


<!-- Section Title -->
<div class="container section-title aos-init aos-animate" data-aos="fade-up">
        <p><span>Invest</span><span class="description-title">ment</span></p>
    </div><!-- End Section Title -->


<!-- Section Title -->
<div class="container mt-4 mb-4">
    

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Add Investment
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Name" value="<?php echo htmlspecialchars($admin_data['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact" class="form-label">Contact</label>
                            <input type="text" name="contact" class="form-control" placeholder="Contact" value="<?php echo htmlspecialchars($admin_data['contact']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="profit_sharing" class="form-label">Profit Sharing</label>
                            <input type="text" name="profit_sharing" class="form-control" placeholder="Profit Sharing" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" placeholder="Address" value="<?php echo htmlspecialchars($admin_data['address']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" name="description" class="form-control" placeholder="Description" value="<?php echo htmlspecialchars($admin_data['description']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="funding" class="form-label">Funding</label>
                            <input type="text" name="funding" class="form-control" placeholder="Funding" required>
                        </div>
                        <div class="mb-3">
                            <label for="profit_sharing_percentage" class="form-label">Profit Sharing %</label>
                            <input type="text" name="profit_sharing_percentage" class="form-control" placeholder="Profit Sharing %" required>
                        </div>
                        <button type="submit" name="add_invest" class="btn btn-primary">Add Investment</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Add Breakdown
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="breakdown" class="form-label">Breakdown</label>
                            <input type="text" name="breakdown" class="form-control" placeholder="Breakdown" required>
                        </div>
                        <div class="mb-3">
                            <label for="money_invest" class="form-label">Money Invest</label>
                            <input type="text" name="money_invest" class="form-control" placeholder="Money Invest" required>
                        </div>
                        <button type="submit" name="add_breakdown" class="btn btn-primary">Add Breakdown</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            All Breakdowns
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Breakdown</th>
                        <th>Money Invest</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($breakdowns_query)) {
                        $date = date('F d, Y h:i A', strtotime($row['created_at']));
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['breakdown']); ?></td>
                            <td><?php echo htmlspecialchars($row['money_invest']); ?></td>
                            <td><?php echo $date; ?></td>
                            <td>
                                <a href="edit_breakdown.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="delete_breakdown.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this breakdown?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php @include("admin_footer.php"); ?>
