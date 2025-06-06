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
    $sql = "SELECT o.*, u.name AS user_name 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.admin_id = '$admin_id'";
    $result = mysqli_query($conn, $sql);

    // Initialize arrays to store data for the new chart
    $user_names = [];
    $order_counts = [];
    $order_dates = []; // Initialize the order_dates array
    $user_names_per_date = []; // Initialize the user_names_per_date array

    // Temporary associative array to count orders per user
    $user_order_count = [];

    while ($row = mysqli_fetch_array($result)) {
        $total_price[] = $row['total_price'];
        $placed_on[] = $row['placed_on'];
        $top_sales_percentage[] = $row['top_sales_percentage'];
        $top_sales[] = $row['top_sales'];

        // Count orders by user
        $user_name = $row['user_name'];
        $date = date('Y-m-d', strtotime($row['placed_on'])); // Get the date
        
        if (!isset($user_order_count[$user_name])) {
            $user_order_count[$user_name] = 0;
        }
        $user_order_count[$user_name]++;
        
        // Populate the order_dates and user_names_per_date arrays
        if (!in_array($date, $order_dates)) {
            $order_dates[] = $date;
            $user_names_per_date[$date] = []; // Initialize for this date
        }
        $user_names_per_date[$date][] = $user_name; // Add user name for this date

        $hasData = true;
    }

    // Prepare arrays for chart
    $user_names = array_keys($user_order_count);
    $order_counts = array_values($user_order_count);
}






// Assuming you have a database connection already established
$sql = "SELECT DATE(order_at) AS order_date, 
               GROUP_CONCAT(name) AS order_names 
        FROM orders 
        WHERE admin_id = '$admin_id'
        GROUP BY order_date 
        ORDER BY order_date ASC";

$result = mysqli_query($conn, $sql);

$orderDates = [];
$orderNames = [];

while ($row = mysqli_fetch_assoc($result)) {
    $orderDates[] = $row['order_date'];
    $orderNames[] = $row['order_names'];
}

// Convert the arrays to JSON for use in JavaScript
$orderDatesJson = json_encode($orderDates);
$orderNamesJson = json_encode($orderNames);






$sql_ratings = "SELECT DATE(created_at) AS rating_date, AVG(rating) AS average_rating
                FROM ratings
                WHERE admin_id = '$admin_id'
                GROUP BY rating_date
                ORDER BY rating_date ASC";
$result_ratings = mysqli_query($conn, $sql_ratings);

// Arrays for chart data
$rating_dates = [];
$average_ratings = [];

while ($row = mysqli_fetch_assoc($result_ratings)) {
    $rating_dates[] = $row['rating_date'];
    $average_ratings[] = $row['average_rating'];
}

// Convert the arrays to JSON for use in JavaScript
$ratingDatesJson = json_encode($rating_dates);
$averageRatingsJson = json_encode($average_ratings);

?>


<!-- Your HTML and remaining PHP code -->


<?php @include("admin_header.php") ?>
<?php @include("admin_navbar.php") ?>

<section id="menu" class="menu section">

    <!-- Section Title -->
    <div class="container section-title aos-init aos-animate" data-aos="fade-up">
        <p><span>Dash</span><span class="description-title">board</span></p>
    </div><!-- End Section Title -->

    <div class="container">
        <ul class="nav nav-tabs d-flex justify-content-center aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
            <div class="row gy-5">
                <div class="container">
                    <div class="row row-cols-1 row-cols-md-4">
                        <div class="col mb-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                            <div class="card text-center" >
                                <div class="card-body">
                                    <i  class="fa-solid fa-money-check-dollar fa-3x mb-3 animated-icon" style="color: #28a745;"></i>
                                    <?php
                                        $pending_count = 0; 
                                        $select_pending = mysqli_query($conn, "SELECT COUNT(*) AS pending_orders FROM `orders` WHERE admin_id='$admin_id' AND payment_status='pending'") or die('query failed');

                                        // Fetch the count of pending orders
                                        if (mysqli_num_rows($select_pending) > 0) {
                                            $fetch_pending = mysqli_fetch_assoc($select_pending);
                                            $pending_count = $fetch_pending['pending_orders'];  // Get the count from the query result
                                        }
                                        ?>

                                        <!-- Display the number of pending orders -->
                                        <p class="card-text"><?php echo number_format($pending_count); ?></p>
                                        <p class="card-title">Pending</p>
                                        <a href="admin_orders.php"><button class="btn btn-primary">See Pending Orders</button></a>

                                </div>
                            </div>
                        </div>

                        <div class="col mb-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                            <div class="card text-center" >
                                <div class="card-body">
                                    <i class="fas fa-check-circle fa-3x mb-3 animated-icon" style="color: #17a2b8;"></i>
                                    <?php
                                    $completed_count = 0; 
                                    $select_completed = mysqli_query($conn, "SELECT COUNT(*) AS completed_orders FROM `orders` WHERE admin_id='$admin_id' AND payment_status='completed'") or die('query failed');

                                    // Fetch the count of completed orders
                                    if (mysqli_num_rows($select_completed) > 0) {
                                        $fetch_completed = mysqli_fetch_assoc($select_completed);
                                        $completed_count = $fetch_completed['completed_orders'];  // Get the count from the query result
                                    }
                                    ?>
                                    <p class="card-text"><?php echo number_format( $completed_count); ?></p>
                                    <p class="card-title"> Completed</p>
                                    <a href="admin_completed.php"><button class="btn btn-primary">See Completed Orders</button></a>
                                </div>
                            </div>
                        </div>

                        <div class="col mb-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">
                            <div class="card text-center" >
                                <div class="card-body">
                                    <i class="fas fa-list fa-3x mb-3 animated-icon" style="color: #ffc107;"></i>
                                    <?php
                                    $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE admin_id='$admin_id'") or die('query failed');
                                    $number_of_orders = mysqli_num_rows($select_orders);
                                    ?>
                                    <p class="card-text"><?php echo $number_of_orders; ?></p>
                                    <p class="card-title"> Orders</p>
                                    <a href="admin_orders.php"><button class="btn btn-primary">See Orders</button></a>
                                </div>
                            </div>
                        </div>

                        <div class="col mb-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">
                            <div class="card text-center" >
                                <div class="card-body">
                                <i class="fa-solid fa-envelope fa-3x mb-3 animated-icon" style="color:red"></i>
                                    <?php
                                    $select_orders = mysqli_query($conn, "SELECT * FROM `message` WHERE admin_id='$admin_id'") or die('query failed');
                                    $number_of_orders = mysqli_num_rows($select_orders);
                                    ?>
                                    <p class="card-text"><?php echo $number_of_orders; ?></p>
                                    <p class="card-title"> Message</p>
                                    <a href="admin_msg.php"><button class="btn btn-primary">See Message</button></a>
                                </div>
                            </div>
                        </div>
  
                    </div>
                </div>
            </div>
        </ul>
    </div>

  

</section>



<div class="container my-5">
    <div class="row">
        <!-- Chart Section -->
        <div class="col-md-6 mb-4">
            <div class="chart-container">
                <h2 class="text-center text-success mb-4">Top Oders Products</h2>
                <section>
                    <canvas id="chart_bar"></canvas>
                    <div class="alert alert-danger text-center mt-3" id="no-data-message-chart" style="display: <?php echo $hasData ? 'none' : 'block'; ?>;">
                        No orders or products available.
                    </div>
                </section>
            </div>
        </div>

        <!-- Table Section in a Card -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-dark">
                    <h5 class="mb-0 text-center">Top Order Product Details</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Order Id</th>
                                <th>Top Sales Products</th>
                                <th>Percentage of That Products</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE admin_id='$admin_id' ORDER BY `top_sales_percentage` DESC") or die('Query failed: ' . mysqli_error($conn));
                            if (mysqli_num_rows($select_orders) > 0) {
                                while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($fetch_orders['id']); ?></td>
                                    <td><?php echo htmlspecialchars($fetch_orders['top_sales']); ?></td>
                                    <td><?php echo htmlspecialchars($fetch_orders['top_sales_percentage']); ?> /off</td>
                                    <td><?php echo htmlspecialchars($fetch_orders['placed_on']); ?></td>
                                </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="3" class="text-center">No orders placed yet!</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container my-5">
    <div class="row">
        

    <div class="col-md-6">
    <div class="card">
        <div class="card-header bg-primary text-dark">
            <h5 class="mb-0 text-center">Users Who Ordered</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>User Name</th>
                        <th>Order Product</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE admin_id='$admin_id' ") or die('query failed');
                if(mysqli_num_rows($select_orders) > 0){
                    while($fetch_orders = mysqli_fetch_assoc($select_orders)){
           
                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($date); ?></td>
                                    <td><?php echo $fetch_orders['name']; ?></td>
                                    <td><?php echo $fetch_orders['total_products']; ?></td>
                                </tr>
                                <?php
                    }
                } else {
                    echo '<tr><td colspan="11" class="text-center">No orders placed yet!</td></tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


        <div class="col-md-6 mb-4">
            <div class="chart-container">
                <h2 class="text-center text-success mb-4">Orders by Date</h2>
                    <section>
                        <canvas id="orders_by_date_chart"></canvas>
                        <div class="alert alert-danger text-center mt-3" id="no-data-message-date" style="display: <?php echo $hasData ? 'none' : 'block'; ?>;">
                            No orders available.
                        </div>
                    </section>
            </div>
        </div>
    </div>
</div>



<div class="container my-5">
    <div class="row">

    <div class="col-md-6 mb-4">
            <div class="chart-container">
                <h2 class="text-center text-success mb-4">ratings</h2>
                    <section>
                         <!-- Line Chart -->
              <canvas id="lineChart"></canvas>
              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new Chart(document.querySelector('#lineChart'), {
                    type: 'line',
                    data: {
                      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                      datasets: [{
                        label: 'Ratings',
                        data: [65, 59, 80, 81, 56, 55, 40],
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                      }]
                    },
                    options: {
                      scales: {
                        y: {
                          beginAtZero: true
                        }
                      }
                    }
                  });
                });
              </script>
                    </section>
            </div>
        </div>

<div class="col-md-6">
    <div class="card">
        <div class="card-header bg-primary text-dark">
            <h5 class="mb-0 text-center">Ratings</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0"></table>


<!-- Table to display filtered ratings -->
            <table id="ratings_table" class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Ratings will be displayed here -->
                </tbody>
            </table>
            </div>
    </div>
</div>


</div></div>

<?php @include("admin_footer.php"); ?>

<!-- Add FontAwesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- Add AOS for animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script src="../js/jquery-1.9.1.js"></script>
<script src="../js/Chart.min.js"></script>
<script type="text/javascript">
    var ctx1 = document.getElementById("chart_bar").getContext('2d');

    var totalPrices = <?php echo json_encode($total_price); ?>;
    var placedOn = <?php echo json_encode($placed_on); ?>;
    var topSales = <?php echo json_encode($top_sales); ?>;
    var topSalesPercentage = <?php echo json_encode($top_sales_percentage); ?>;

    // Show message if there is no data
    var hasData = totalPrices.length > 0;

    if (hasData) {
        var myChart1 = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: placedOn,
                datasets: [
                    {
                        label: 'Top Sales Percentage',
                        backgroundColor: "#5969ff",
                        data: topSalesPercentage,
                        borderColor: "#4e5d6c",
                        borderWidth: 1,
                    },
                    {
                        label: 'Top Sales Products',
                        backgroundColor: "#ff407b",
                        data: topSales,
                        borderColor: "#d83b6c",
                        borderWidth: 1,
                    }
                ]
            },
            options: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        fontColor: '#71748d',
                        fontFamily: 'Circular Std Book',
                        fontSize: 14,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const orderDates = <?php echo $orderDatesJson; ?>;
    const orderNames = <?php echo $orderNamesJson; ?>;

    const ctx = document.getElementById('orders_by_date_chart').getContext('2d');
    const ordersChart = new Chart(ctx, {
        type: 'bar', // or any other chart type you prefer
        data: {
            labels: orderDates,
            datasets: [{
                label: 'Orders Count',
                data: orderNames.map(names => names.split(',').length), // Count of names per date
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const index = context.dataIndex;
                            return ` ${orderNames[index]}`; // Show names in the tooltip
                        }
                    }
                }
            }
        }
    });

    // Add click event listener to the chart
    ctx.canvas.addEventListener('click', function(evt) {
        const activePoints = ordersChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
        if (activePoints.length > 0) {
            const index = activePoints[0].index; // Get the index of the clicked bar
            const selectedDate = orderDates[index];
            const selectedNames = orderNames[index];

            // Display the date and names
            alert(`Order Date: ${selectedDate}\nNames: ${selectedNames}`);
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ratingDates = <?php echo $ratingDatesJson; ?>;
    const averageRatings = <?php echo $averageRatingsJson; ?>;

    const ctx2 = document.getElementById('ratings_chart').getContext('2d');
    const ratingsChart = new Chart(ctx2, {
        type: 'line', // Line chart
        data: {
            labels: ratingDates, // Dates of ratings
            datasets: [{
                label: 'Average Rating',
                data: averageRatings, // Average ratings for each date
                borderColor: '#4caf50', // Line color
                fill: false, // No fill
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Average Rating'
                    },
                    min: 0,
                    max: 5,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>

<script>
    // When a date is clicked, filter ratings for that date
    document.querySelectorAll('.rating-filter').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const selectedDate = this.getAttribute('data-date');

            // Fetch and display ratings for the selected date
            fetchRatingsForDate(selectedDate);
        });
    });

    // Function to fetch and display ratings for the selected date
    function fetchRatingsForDate(date) {
        fetch('fetch_ratings_by_date.php?date=' + date)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#ratings_table tbody');
                tableBody.innerHTML = ''; // Clear previous data

                if (data.length > 0) {
                    data.forEach(rating => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${rating.user_name}</td>
                            <td>${rating.rating}</td>
                            <td>${rating.comment}</td>
                            <td>${rating.created_at}</td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    tableBody.innerHTML = '<tr><td colspan="4" class="text-center">No ratings for this date</td></tr>';
                }
            });
    }
</script>

<script>
    AOS.init();
</script>

<!-- Optional custom styles -->
<style>
    .card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .animated-icon {
        font-size: 3rem;
        transition: transform 0.3s;
    }
    .animated-icon:hover {
        transform: scale(1.1);
    }
    @media (max-width: 768px) {
        .card {
            width: 90vw; /* Adjust width for smaller screens */
        }
    }
</style>
