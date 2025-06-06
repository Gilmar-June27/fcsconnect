<?php
include '../config.php';
session_start();
$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../login.php');
    exit;
}

$hasData = false; // Flag to check if there's data

if (!$conn) {
    echo "Database connection error.";
} else {
    $sql = "SELECT * FROM orders WHERE admin_id='$admin_id'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $total_price[] = $row['total_price'];
        $placed_on[] = $row['order_at'];
        $top_sales_percentage[] = $row['top_sales_percentage'];
        $top_sales[] = $row['top_sales'];
        $hasData = true; // Set flag to true if data is present
    }
}
?>

<?php include("admin_header.php"); ?>
<?php
$select = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$admin_id'") or die('query failed');
if(mysqli_num_rows($select) > 0){
   $fetch = mysqli_fetch_assoc($select);
}
?>
<?php @include("admin_header.php") ?>
<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center justify-content-between">

      <a href="admin_dashboard.php" class="logo d-flex align-items-center me-auto me-xl-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <img src="../images/logo.jpeg" style="max-height: 36px; border-radius: 100px;margin-right: 8px;transform: scale(1.5);" alt="logo" srcset="">
        <span>.</span>



        <div class="d-flex align-items-center p-2 ml-2">
        
        </div>
      </a>
      
      <nav id="navmenu" class="navmenu d-flex">
          <a href="admin_search.php">
            <input class="form-control mr-sm-1" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </a>
      </nav>
    
      <?php
    // Fetch unread messages count
$select_messages = mysqli_query($conn, "SELECT COUNT(*) AS count FROM `message` WHERE admin_id = '$admin_id' AND is_read = 0") or die('query failed');
$message_count = mysqli_fetch_assoc($select_messages)['count'];
    ?>
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="admin_dashboard.php" class="active">Home<br></a></li>
           <!--- <li><a href="all_user.php"> Buyers</a></li>
         <li><a href="post-page-admin.php">Post<span></a></li>
          <li><a href="admin_investment.php">Investments</a></li> -->
          
          <li class="dropdown"><a href="#"><span>Products</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
             
              
              <li><a href="admin_products.php">Add Product</a></li>
              <li><a href="admin_shipping_fee.php">Add Shipping Fee</a></li>
              <li><a href="admin_processing_products.php">Ready To Harvest Product</a></li>
              <li><a href="admin_approved_products.php">Harvested Product</a></li>

              <li><a href="admin_reject_products.php">Ready to pick up Product</a></li>
            </ul>
          </li> 
          <li class="dropdown"><a href="#"><span>Orders</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
             
              
              <li><a href="admin_orders.php">Pending Orders</a></li>
              <li><a href="admin_completed.php">Completed Orders</a></li>
              <li><a href="admin_processing_order.php">Processing Orders</a></li>
              <li><a href="out_of_deliver_order.php">Out of Delivery Orders</a></li>
              <li><a href="admin_cancelled.php">Cancelled Orders</a></li>
            </ul>
          </li> 
         
          <li><a href="admin_analytics.php">Analytics</a></li>
          <li><a href="admin_msg.php">Messages<span><?php echo $message_count; ?></span></a></li>
          <li class="dropdown"><a href="#"><span>
            <?php
           if($fetch['image'] == ''){
              echo '<img src="images/default-avatar.png" style="width: 30px;
                                                                aspect-ratio: 1;
                                                                border-radius: 100px;">';
           } else {
              echo '<img src="images/'.$fetch['image'].'" style="width: 30px;
                                                                aspect-ratio: 1;
                                                                border-radius: 100px;">';
           }
          ?></span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
             
              
              <li><a href="admin_profile.php">Profile</a></li>
              <li><a href="../logout.php">Logout</a></li>
            </ul>
          </li> 
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

   
      
    </div>
  </header>


<!-- Bootstrap Container for Main Content -->
<div class="container my-5">
    <h2 class="text-center text-success mb-4">Total Orders by Date</h2>
    
    <!-- Dropdown to Filter by Month and Year -->
    <div class="mb-4">
        <label for="monthFilter" class="form-label">Filter by Month:</label>
        <select id="monthFilter" class="form-select">
            <option value="">All Months</option>
            <option value="01">January</option>
            <option value="02">February</option>
            <option value="03">March</option>
            <option value="04">April</option>
            <option value="05">May</option>
            <option value="06">June</option>
            <option value="07">July</option>
            <option value="08">August</option>
            <option value="09">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
        </select>
    </div>

    <div class="mb-4">
        <label for="yearFilter" class="form-label">Filter by Year:</label>
        <select id="yearFilter" class="form-select">
            <option value="">All Years</option>
            <?php
            $currentYear = date("Y");
            for ($year = $currentYear; $year >= 2000; $year--) {
                echo "<option value='$year'>$year</option>";
            }
            ?>
        </select>
    </div>
    
    <section>
        <canvas id="chartjs_line"></canvas>
        <div class="alert alert-danger text-center mt-3" id="no-data-message-line" style="display: <?php echo $hasData ? 'none' : 'block'; ?>;">
            No orders available.
        </div>
    </section>
</div>



<script src="../js/jquery-1.9.1.js"></script>
<script src="../js/Chart.min.js"></script>
<script type="text/javascript">
    var ctxLine = document.getElementById("chartjs_line").getContext('2d');
   

    var totalPrices = <?php echo json_encode($total_price); ?>;
    var placedOn = <?php echo json_encode($placed_on); ?>;
    var topSales = <?php echo json_encode($top_sales); ?>;
    var topSalesPercentage = <?php echo json_encode($top_sales_percentage); ?>;

    var hasData = totalPrices.length > 0;

    if (hasData) {
        var lineChart = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: placedOn,
                datasets: [{
                    label: 'Orders Over Time',
                    borderColor: "#5969ff",
                    fill: false,
                    data: totalPrices,
                    lineTension: 0,
                    borderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: { display: true }
                    },
                    y: {
                        ticks: { beginAtZero: true }
                    }
                }
            }
        });

     }

    // Handle filtering data by month and year
    document.getElementById('monthFilter').addEventListener('change', updateChart);
    document.getElementById('yearFilter').addEventListener('change', updateChart);

    function updateChart() {
        var selectedMonth = document.getElementById('monthFilter').value;
        var selectedYear = document.getElementById('yearFilter').value;

        // Filter the data based on the selected month and year
        var filteredData = totalPrices.filter(function(price, index) {
            var orderDate = placedOn[index];
            var orderMonth = orderDate.substring(5, 7);
            var orderYear = orderDate.substring(0, 4);

            return (!selectedMonth || orderMonth === selectedMonth) && (!selectedYear || orderYear === selectedYear);
        });

        var filteredLabels = placedOn.filter(function(date, index) {
            var orderMonth = date.substring(5, 7);
            var orderYear = date.substring(0, 4);

            return (!selectedMonth || orderMonth === selectedMonth) && (!selectedYear || orderYear === selectedYear);
        });

        // Update line chart with filtered data
        lineChart.data.labels = filteredLabels;
        lineChart.data.datasets[0].data = filteredData;
        lineChart.update();

        // If you want to update the bar chart, you can add similar logic here
        // If the data for topSales and topSalesPercentage depends on the month/year filters, add logic here
        //barChart.update();
    }
</script>



<?php include("admin_footer.php"); ?>
