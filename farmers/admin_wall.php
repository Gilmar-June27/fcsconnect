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

// Fetch the most recent investment
$investment_query = mysqli_query($conn, "SELECT * FROM `investments` WHERE admin_id = '$admin_id' ORDER BY id DESC LIMIT 1") or die('query failed');
$investment_data = mysqli_fetch_assoc($investment_query);

$select_posts = mysqli_query($conn, "SELECT * FROM `posts` WHERE admin_id = '$admin_id' ") or die('Query failed');

// Fetch all breakdowns and calculate the total money invested
$breakdown_query = mysqli_query($conn, "SELECT * FROM `breakdowns` WHERE admin_id = '$admin_id'") or die('query failed');
$breakdown_total = 0;
$breakdowns = [];

while ($breakdown_data = mysqli_fetch_assoc($breakdown_query)) {
    $breakdowns[] = $breakdown_data;
    $breakdown_total += (float)$breakdown_data['money_invest'];
}
?>

<?php @include("admin_header.php"); ?>
<?php @include("admin_navbar.php"); ?>
<!-- Section Title -->
<div class="container section-title aos-init aos-animate" data-aos="fade-up">
        <p><span>Wa</span><span class="description-title">ll</span></p>
    </div><!-- End Section Title -->
<div class="container mt-4">
    <div class="row">
        <!-- Admin Information Section -->
        <div class="col-md-6 mb-4">
            <div class="card p-3 shadow-sm">
                <h2 class="h4">Admin Information</h2>
                <div class="mb-2">
                    <strong>Fullname:</strong> <?php echo htmlspecialchars($admin_data['name'] ?? 'N/A'); ?>
                </div>
                <div class="mb-2">
                    <strong>Contact:</strong> <?php echo htmlspecialchars($admin_data['contact'] ?? 'N/A'); ?>
                </div>
                <div class="mb-2">
                    <strong>Address:</strong> <?php echo htmlspecialchars($admin_data['address'] ?? 'N/A'); ?>
                </div>
                <div class="mb-2">
                    <strong>Description:</strong> <?php echo htmlspecialchars($admin_data['description'] ?? 'N/A'); ?>
                </div>
                <div class="mb-2">
                    <strong>Fundings:</strong> <?php echo htmlspecialchars($investment_data['funding'] ?? 'N/A'); ?>
                </div>
            </div>
        </div>

        <!-- Fundings Section -->
        <div class="col-md-6 mb-4">
            <div class="card p-3 shadow-sm">
                <h3 class="h5">Breakdown</h3>
                <?php if (!empty($breakdowns)) { ?>
                    <div class="border border-primary rounded p-3 mb-2">
                        <?php foreach ($breakdowns as $breakdown) { ?>
                            <p><strong><?php echo htmlspecialchars($breakdown['breakdown']); ?>:</strong> <?php echo htmlspecialchars($breakdown['money_invest']); ?></p>
                        <?php } ?>
                        <p><strong>Total Investment:</strong> <?php echo number_format($breakdown_total, 2); ?></p>
                    </div>
                <?php } else { ?>
                    <p class="btn btn-outline-danger m-auto">No breakdown available.</p>
                <?php } ?>
            </div>

            <div class="card p-3 shadow-sm mt-4">
                <h3 class="h5">Profit Sharing</h3>
                <p>
                    <?php if ($investment_data) {
                        echo htmlspecialchars($investment_data['profit_sharing_percentage']) . '%';
                    } else {
                        echo '<p class="btn btn-outline-danger m-auto"> No investment data available.</p>';
                    }
                    ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Posts Section -->
    <section  class="section light-background mb-5 rounded">

<div class="container">
<h3 class="h4 mt-4">Posts</h3>
  <div class="row mb-4 light-background">
      <div class="col-12" >
          <section class="show-posts">
              <div class="d-flex flex-wrap">
                  <?php
                  if (mysqli_num_rows($select_posts) > 0) {
                      while ($fetch_posts = mysqli_fetch_assoc($select_posts)) {
                          $date = date('F d, Y h:i A', strtotime($fetch_posts['created_at']));
                          $image = $fetch_posts['image'];
                          ?>
                          <div class="card m-2" style="width: 18rem;">
                              <?php if (!empty($image)) { ?>
                                  <img src="images/<?php echo htmlspecialchars($image); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($fetch_posts['title']); ?>" style="height:30vh">
                              <?php } ?>
                              <div class="card-body">
                                  <h5 class="card-title"><?php echo htmlspecialchars($fetch_posts['title']); ?></h5>
                                  <p class="card-text"><?php echo htmlspecialchars($fetch_posts['content']); ?></p>
                                  <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($date); ?></small></p>
                              </div>
                          </div>
                          <?php
                      }
                  } else {
                      echo '<p class="btn btn-outline-danger m-auto">No posts available!</p>';
                  }
                  ?>
              </div>
          </section>
      </div>
  </div>
  </div>
</section>

</div>

<?php @include("admin_footer.php"); ?>
