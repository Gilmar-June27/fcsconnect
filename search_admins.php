<?php
require_once 'config.php';

if (isset($_POST['query'])) {
    $query = mysqli_real_escape_string($conn, $_POST['query']);
    
    // Retrieve matching admins
    $admin_query = mysqli_query($conn, "SELECT * FROM `users` WHERE `name` LIKE '%$query%' AND `user_type` = 'admin'");
    
    if (mysqli_num_rows($admin_query) > 0) {
        while ($admin_data = mysqli_fetch_assoc($admin_query)) {
            $admin_id = $admin_data['id'];
            
            // Fetch the latest investment, posts, and breakdown data for each admin
            $investment_query = mysqli_query($conn, "SELECT * FROM `investments` WHERE `admin_id` = '$admin_id' ORDER BY `id` DESC LIMIT 1");
            $investment_data = mysqli_fetch_assoc($investment_query);

            $select_posts = mysqli_query($conn, "SELECT * FROM `posts` WHERE `admin_id` = '$admin_id'");
            
            $breakdown_query = mysqli_query($conn, "SELECT * FROM `breakdowns` WHERE `admin_id` = '$admin_id'");
            $breakdown_total = 0;
            $breakdowns = [];
            while ($breakdown_data = mysqli_fetch_assoc($breakdown_query)) {
                $breakdowns[] = $breakdown_data;
                $breakdown_total += (int)$breakdown_data['money_invest'];
            }
            ?>
            <div class="card my-4">
                <div class="card-body">
                    <h5 class="card-title" style="font-size:25px;font-weight:bolder;"><?= htmlspecialchars($admin_data['name']); ?></h5>
                    <p class="card-text"><strong>Contact:</strong> <?= htmlspecialchars($admin_data['contact'] ?? 'N/A'); ?></p>
                    <p class="card-text"><strong>Address:</strong> <?= htmlspecialchars($admin_data['address'] ?? 'N/A'); ?></p>
                    <p class="card-text"><strong>Description:</strong> <?= htmlspecialchars($admin_data['description'] ?? 'N/A'); ?></p>
                    <p class="card-text"><strong>Fundings:</strong> <?= htmlspecialchars($investment_data['funding'] ?? 'N/A'); ?></p>
                    <h6 class="mt-3" style="font-size:25px;font-weight:bolder;">Breakdown</h6>
                    <?php if (!empty($breakdowns)) { ?>
                        <ul class="list-group">
                            <?php foreach ($breakdowns as $breakdown) { ?>
                                <li class="list-group-item">
                                    <strong><?= htmlspecialchars($breakdown['breakdown']); ?>:</strong> <?= htmlspecialchars(number_format($breakdown['money_invest'])); ?>
                                </li>
                            <?php } ?>
                            <li class="list-group-item"><strong>Total Investment:</strong> <?= number_format($breakdown_total); ?></li>
                        </ul>
                    <?php } else { ?>
                        <p>No breakdown available.</p>
                    <?php } ?>

                    <h6 class="mt-3" style="font-size:25px;font-weight:bolder;">Profit Sharing</h6>
                    <p style="font-weight:bolder"><?= htmlspecialchars($investment_data['profit_sharing_percentage'] ?? 'N/A'); ?></p>

                    <h6 class="mt-3" style="font-size:25px;font-weight:bolder;">Plans</h6>
                    <div class="overflow-auto" style="max-height: 200px;">
                    <?php
                        if (mysqli_num_rows($select_posts) > 0) {
                            while ($fetch_posts = mysqli_fetch_assoc($select_posts)) {
                                $date = date('F d, Y h:i A', strtotime($fetch_posts['created_at']));
                                $image = $fetch_posts['image'];
                                ?>
                                <div class="card p-3 m-3" style="border:1px solid lightgray">
                                    <?php if (!empty($image)) { ?>
                                        <img src="images/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($fetch_posts['title']); ?>" style="width: 222px; aspect-ratio: 1;">
                                    <?php } ?>
                                    <div class="card-content">
                                        <h2 class="card-title" style="font-size:25px;font-weight:bolder;"><?php echo htmlspecialchars($fetch_posts['title']); ?></h2>
                                        <p class="card-description"><?php echo htmlspecialchars($fetch_posts['content']); ?></p>
                                        <p class="card-description"><?php echo htmlspecialchars($date); ?></p>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<p class="empty">No posts available!</p>';
                        }
                    ?>
                    </div>
                    <a href="?delete_admin_id=<?= htmlspecialchars($admin_id); ?>" class="btn btn-outline-danger mt-3" onclick="return confirm('Are you sure you want to delete this admin?');">Delete</a>
                </div>
            </div>
            <?php
        }
    } else {
        echo '<p class="text-center mt-4">No admins found!</p>';
    }
}
?>
