<?php @include("function.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - admin</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: iPortfolio
  * Template URL: https://bootstrapmade.com/iportfolio-bootstrap-portfolio-websites-template/
  * Updated: Jun 29 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header dark-background d-flex flex-column">
    <i class="header-toggle d-xl-none bi bi-list"></i>

    <div class="profile-img">
      <img src="assets/img/my-profile-img.jpg" alt="" class="img-fluid rounded-circle">
    </div>

    <a href="index.html" class="logo d-flex align-items-center justify-content-center">
      <!-- Uncomment the line below if you also wish to use an image logo -->
      <!-- <img src="assets/img/logo.png" alt=""> -->
      <h1 class="sitename">Admin</h1>
    </a>


    <nav id="navmenu" class="navmenu">
      <ul>
        <li><a href="#hero" class="active"><i class="bi bi-speedometer2 navicon"></i> Dashboard</a></li>
        <li><a href="#contact"><i class="bi bi-graph-up-arrow navicon"></i> Analytics</a></li>
        <li class="dropdown"><a href="#" class="active"><i class="bi bi-menu-button navicon"></i> <span contenteditable="true" spellcheck="false">Users</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul class="dropdown-active">
            <li><a href="#about"><i class="bi bi-people navicon"></i> Buyers</a></li>
            <li><a href="#skills"><i class="bi bi-people navicon"></i> Farmers</a></li>
            </ul>
         </li>
<!--         
        <li><a href="#resume"><i class="bi bi-egg navicon"></i> Pending Product</a></li>
        <li><a href="#portfolio"><i class="bi bi-egg-fried navicon"></i> Approved Product</a></li> -->
        <li><a href="#services"><i class="bi bi-newspaper navicon"></i> Reports</a></li>
        <li><a href="#testimonials"><i class="bi bi-slash-circle navicon"></i> Blocked</a></li>
       
        <li><a href="../logout.php"><i class="bi bi-door-open navicon"></i> Logout</a></li>
      </ul>
    </nav>

  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero dark-background">
     <!-- Section Title -->
     <div class="container section-title" data-aos="fade-up">
        <h2 class="text-white">Dashboard</h2>
      </div><!-- End Section Title -->
    <div class="container">
        <ul class="nav  d-flex justify-content-center aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
            <div class="row ">
                <div class="container">
                    <div class="row  row-cols-md-2">
                        <div class="col mb-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
                            <div class="card text-center" >
                                <div class="card-body">
                                    <i class="fas fa-dollar-sign fa-3x mb-3 animated-icon" style="color: #28a745;"></i>
                                    <?php 
                                        $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user' || user_type = 'admin'") or die('query failed');
                                        $number_of_users = mysqli_num_rows($select_users);
                                    ?>
                                   
                                    <p class="card-text"><?php echo $number_of_users; ?></p>
                                    <p class="card-title" style="    padding: 10px 66px;">total users</p>
                                    <button class="btn btn-primary" width="200px">See Orders</button>
                                </div>
                            </div>
                        </div>

                        <div class="col mb-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="200">
                            <div class="card text-center" >
                                <div class="card-body">
                                    <i class="fas fa-check-circle fa-3x mb-3 animated-icon" style="color: #17a2b8;"></i>
                                    <?php 
                                        $select_messages = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
                                        $number_of_messages = mysqli_num_rows($select_messages);
                                    ?>
                                    <p class="card-text"><?php echo $number_of_messages; ?></p>
                                    <p class="card-title" style="    padding: 10px 66px;">new message</p>
                                    <button class="btn btn-primary">See Orders</button>
                                </div>
                            </div>
                        </div>

                        

                    </div>
                </div>
            </div>
        </ul>
    </div>
    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Buyers</h2>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="container">
    <div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Image</th>
                <th scope="col">User ID</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">User Type</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
            while ($fetch_users = mysqli_fetch_assoc($select_users)) {
                $status_class = $fetch_users['status'] == 'activate' ? 'btn-success' : 'btn-danger';
                $image_path = $fetch_users['image'] ? "../images/" . $fetch_users['image'] : "../images/default-avatar.png";
            ?>
                <tr>
                    <td><img src="<?php echo $image_path; ?>" alt="User Image" style="width: 60px; height: 60px; object-fit: cover;"></td>
                    <td><?php echo $fetch_users['id']; ?></td>
                    <td><?php echo $fetch_users['name']; ?></td>
                    <td><?php echo $fetch_users['email']; ?></td>
                    <td>
                        <span style="color:<?php if ($fetch_users['user_type'] == 'admin') { echo 'var(--orange)'; } ?>">
                            <?php echo $fetch_users['user_type']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($fetch_users['block'] == 'no') { ?>
                            <a href="super-admin-dashboard.php?block=<?php echo $fetch_users['id']; ?>" onclick="return confirm('Are you sure you want to block this user?');" class="btn btn-success btn-sm">Activate</a>
                        <?php } else { ?>
                            <a href="super-admin-dashboard.php?unblock=<?php echo $fetch_users['id']; ?>" onclick="return confirm('Are you sure you want to unblock this user?');" class="btn btn-danger btn-sm">Deactivate</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    </div>
</div>


      </div>

    </section><!-- /About Section -->



    <!-- Skills Section -->
    <section id="skills" class="skills section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Farmers</h2>
       
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="container">
    <div class="table-responsive">
    <table class="table table-striped" table-bordered">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Image</th>
                <th scope="col">User ID</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">User Type</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed');
            while ($fetch_users = mysqli_fetch_assoc($select_users)) {
                $status_class = $fetch_users['status'] == 'activate' ? 'btn-success' : 'btn-danger';
                $image_path = $fetch_users['image'] ? "../images/" . $fetch_users['image'] : "../images/default-avatar.png";
            ?>
                <tr>
                    <td><img src="<?php echo $image_path; ?>" alt="User Image" class="img-thumbnail" style="width: 60px; height: 60px;"></td>
                    <td><?php echo $fetch_users['id']; ?></td>
                    <td><?php echo $fetch_users['name']; ?></td>
                    <td><?php echo $fetch_users['email']; ?></td>
                    <td>
                        <span style="color:<?php if ($fetch_users['user_type'] == 'admin') { echo 'var(--orange)'; } ?>">
                            <?php echo $fetch_users['user_type']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($fetch_users['block'] == 'no') { ?>
                            <a href="super-admin-dashboard.php?block=<?php echo $fetch_users['id']; ?>" onclick="return confirm('Are you sure you want to block this user?');" class="btn btn-success btn-sm">Activate</a>
                        <?php } else { ?>
                            <a href="super-admin-dashboard.php?unblock=<?php echo $fetch_users['id']; ?>" onclick="return confirm('Are you sure you want to unblock this user?');" class="btn btn-danger btn-sm">Deactivate</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    </div>
</div>


      </div>

    </section><!-- /Skills Section -->

    <!-- Resume Section -->
    <section id="resume" class="resume section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Pending Product</h2>
       </div><!-- End Section Title -->

      <div class="container">
      <button class="btn btn-primary" name="approve_all">Approved All</button>
      <div class="container mt-4">
    <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Image</th>
                <th scope="col">Product Name</th>
                <th scope="col">Price</th>
                <th scope="col">Description</th>
                <th scope="col">Category</th>
                <th scope="col">Harvest Status</th>
                <th scope="col">Farmer's Name</th> <!-- Column for Farmer's Name -->
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $select_products = mysqli_query($conn, "SELECT products.*, users.name AS admin_name 
                                                    FROM products 
                                                    JOIN users ON products.admin_id = users.id 
                                                    WHERE products.status = 'processing' 
                                                   ") or die('query failed');
            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
        ?>
            <tr>
                <td>
                  <?php 
                // Explode the images to handle multiple images
                $images = explode(',', $fetch_products['images']);
                $main_image = isset($images[0]) ? $images[0] : 'default-image.jpg'; // Fallback to default image if no image found
            ?>
            <img src="../images/<?php echo $main_image; ?>" class="card-img-top" alt="<?php echo $fetch_products['name']; ?>" style="width: 100px; height: 100px;">
                </td>
                <td><?php echo htmlspecialchars($fetch_products['name']); ?></td>
                <td>₱<?php echo htmlspecialchars($fetch_products['price']); ?></td>
                <td><?php echo htmlspecialchars($fetch_products['descriptions']); ?></td>
                <td><?php echo htmlspecialchars($fetch_products['category']); ?></td>
                <td><?php echo htmlspecialchars($fetch_products['havest_status']); ?></td>
                <td><?php echo htmlspecialchars($fetch_products['admin_name']); ?></td> <!-- Display Farmer's Name -->
                <td>
                    <a href="super-admin-dashboard.php?approve=<?php echo $fetch_products['id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this product?');">Approve</a>
                    <a href="super-admin-dashboard.php?reject=<?php echo $fetch_products['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject this product?');">Reject</a>
                </td>
            </tr>
        <?php
                }
            } else {
                echo '<tr><td colspan="8" class="text-center">No pending vegetable products!</td></tr>';
            }
        ?>
        </tbody>
    </table>
    </div>
</div>


      </div>

    </section><!-- /Resume Section -->

    <!-- Portfolio Section -->
    <section id="portfolio" class="portfolio section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Approved Product</h2>
         </div><!-- End Section Title -->

      <div class="container">

      <div class="container mt-4">
   <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Image</th>
                <th scope="col">Product Name</th>
                <th scope="col">Price</th>
                <th scope="col">Description</th>
                <th scope="col">Category</th>
                <th scope="col">Harvest Status</th>
                <th scope="col">Farmer's Name</th> <!-- New column for Farmer's Name -->
            </tr>
        </thead>
        <tbody>
        <?php
            // Fetch products with status 'approved' and join with users to get admin's name
            $select_products = mysqli_query($conn, "SELECT products.*, users.name AS admin_name 
                                                    FROM products 
                                                    JOIN users ON products.admin_id = users.id 
                                                    WHERE products.status = 'approved'") 
                                                    or die('query failed');

            if (mysqli_num_rows($select_products) > 0) {
                while ($fetch_products = mysqli_fetch_assoc($select_products)) {
        ?>
            <tr>
                <td>
                  <?php 
                // Explode the images to handle multiple images
                $images = explode(',', $fetch_products['images']);
                $main_image = isset($images[0]) ? $images[0] : 'default-image.jpg'; // Fallback to default image if no image found
            ?>
            <img src="../images/<?php echo $main_image; ?>" class="card-img-top" alt="<?php echo $fetch_products['name']; ?>" style="width: 100px; height: 100px;">
                </td>
                <td><?php echo htmlspecialchars($fetch_products['name']); ?></td>
                <td>₱<?php echo htmlspecialchars($fetch_products['price']); ?></td>
                <td><?php echo htmlspecialchars($fetch_products['descriptions']); ?></td>
                <td><?php echo htmlspecialchars($fetch_products['category']); ?></td>
                <td><?php echo htmlspecialchars($fetch_products['havest_status']); ?></td>
                <td><?php echo htmlspecialchars($fetch_products['admin_name']); ?></td> <!-- Display Farmer's Name -->
            </tr>
        <?php
                }
            } else {
                echo '<tr><td colspan="7" class="text-center">No approved vegetable products!</td></tr>';
            }
        ?>
        </tbody>
    </table>
    </div>
</div>


      </div>

    </section><!-- /Portfolio Section -->

    <!-- Services Section -->
    <section id="services" class="services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Reports</h2>
       </div><!-- End Section Title -->

      <div class="container">

      <div class="container mt-4">
    <h1 class="mb-4">Reports</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Reporter Name</th>
                    <th>Reported User Name</th>
                    <th>Report Type</th>
                    <th>Description</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query to join reports with users to get names
                $query = "
                    SELECT 
                        r.id,
                        r.reporter_id,
                        r.reported_user_id,
                        r.report_type,
                        r.description,
                        r.status,
                        r.blocked,
                        r.created_at,
                        u1.name AS reporter_name,
                        u2.name AS reported_user_name
                    FROM reports r
                    JOIN users u1 ON r.reporter_id = u1.id
                    JOIN users u2 ON r.reported_user_id = u2.id WHERE r.blocked = 0
                ";

                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $date = date('F d, Y h:i A', strtotime($row['created_at']));
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['reporter_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['reported_user_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['report_type']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td>" . $date . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No reports found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>



      </div>

    </section><!-- /Services Section -->

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Blocked Reports</h2>
           </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

      <div class="container mt-4">

    <div class="table-responsive">
    <div class="table-responsive">
    <table class="table table-striped table-bordered table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Reporter Name</th>
                    <th>Reported User Name</th>
                    <th>Description</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query to join reports with users to get names
                $query = "
                    SELECT 
                        r.id,
                        r.reporter_id,
                        r.reported_user_id,
                        r.report_type,
                        r.description,
                        r.status,
                        r.blocked,
                        r.created_at,
                        u1.name AS reporter_name,
                        u2.name AS reported_user_name
                    FROM reports r
                    JOIN users u1 ON r.reporter_id = u1.id
                    JOIN users u2 ON r.reported_user_id = u2.id 
                    WHERE r.blocked = 1
                ";

                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $date = date('F d, Y h:i A', strtotime($row['created_at']));
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['reporter_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['reported_user_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['reported_user_name']) . " " . htmlspecialchars($row['description']) . " " . htmlspecialchars($row['reporter_name']) . "</td>";
                        echo "<td>" . $date . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No reports found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        </div>
    </div>
</div>


      </div>

    </section><!-- /Testimonials Section -->

    <!-- Contact Section -->
   
   


<!-- Contact Section with Analytics -->
<section id="contact" class="contact section">
    <div class="container section-title" data-aos="fade-up">
        <h2>Analytics</h2>
    </div>
    <div class="container mt-5 d-flex justify-content-center align-items-center m-auto">
    <form id="filterForm" method="post" class="d-flex gap-3">
        <div>
            <label for="month" class="form-label">Select Month:</label>
            <select name="month" id="month" class="form-select">
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?php echo $m; ?>" <?php echo ($m == $month) ? 'selected' : ''; ?>>
                        <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        
        <div>
            <label for="year" class="form-label">Select Year:</label>
            <select name="year" id="year" class="form-select">
                <?php for ($y = date('Y'); $y >= 2000; $y--): ?>
                    <option value="<?php echo $y; ?>" <?php echo ($y == $year) ? 'selected' : ''; ?>>
                        <?php echo $y; ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Filter</button>
    </form>
</div>
    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row mb-4">
            <div class="col-lg-6 col-md-12">
                <h2>Buyers Registrations</h2>
                <div class="chart-container">
                    <canvas id="user_chart"></canvas>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <h2>Farmers Registrations</h2>
                <div class="chart-container">
                    <canvas id="admin_chart"></canvas>
                </div>
            </div>
        </div>
    </div>
</section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer position-relative light-background">

    <div class="container">
      <div class="copyright text-center ">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">FCs-Connect</strong> <span>All Rights Reserved</span></p>
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="#">FCs-Connect</a>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/typed.js/typed.umd.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="../js/jquery-1.9.1.js"></script>
<script src="../js/Chart.min.js"></script>
<!-- Chart.js -->

<script type="text/javascript">
    var userCtx = document.getElementById("user_chart").getContext('2d');
    var adminCtx = document.getElementById("admin_chart").getContext('2d');

    var dates = <?php echo $dates; ?>;
    var userCounts = <?php echo $user_data; ?>;
    var adminCounts = <?php echo $admin_data; ?>;
    
    var userChart = new Chart(userCtx, {
        type: 'line',  // Change type to 'line'
        data: {
            labels: dates,
            datasets: [{
                label: 'Buyers Registrations',
                borderColor: "#5969ff",
                backgroundColor: "rgba(89, 105, 255, 0.1)",  // Light color for the line fill
                data: userCounts,
                fill: true,  // Enable fill below the line
                tension: 0.4  // Makes the line smoother
            }]
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

    var adminChart = new Chart(adminCtx, {
        type: 'line',  // Change type to 'line'
        data: {
            labels: dates,
            datasets: [{
                label: 'Farmers Registrations',
                borderColor: "#ff407b",
                backgroundColor: "rgba(255, 64, 123, 0.1)",  // Light color for the line fill
                data: adminCounts,
                fill: true,  // Enable fill below the line
                tension: 0.4  // Makes the line smoother
            }]
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
</script>


</body>

</html>