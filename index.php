<?php  @include('header.php'); ?>
<?php  @include('navbar.php'); ?>






  <main class="main">

 


<!-- Hero Section -->
<section id="hero" class="hero section light-background">

  <div class="container">
    <div class="row gy-4 justify-content-center justify-content-lg-between align-items-center">
      <div class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center text-center text-lg-left">
        <h1 data-aos="fade-up">From Farm Fresh Veggies<br>To the Finest Seafood</h1>
        <p data-aos="fade-up" data-aos-delay="100">Experience the true taste of nature with our locally sourced vegetables and premium seafood. Brought to you by dedicated farmers and fishermen who care about quality.</p>
        <div class="d-flex flex-column flex-md-row justify-content-center justify-content-lg-start" data-aos="fade-up" data-aos-delay="200">
          <a href="browse_products.php" class="btn-get-started">Order Fresh Today</a>
          <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox btn-watch-video d-flex align-items-center mt-3 mt-md-0 ml-md-3"><i class="bi bi-play-circle"></i><span>Watch Our Journey</span></a>
        </div>
      </div>
      <div class="col-lg-5 order-1 order-lg-2 hero-img" data-aos="zoom-out">
        <img src="images/1.jpg" class="img-fluid animated" alt="Farm fresh vegetables and seafood">
      </div>
    </div>
  </div>

</section><!-- /Hero Section -->


    
 <?php @include('product.php') ?>
 <?php @include('farmers.php') ?>

  </main>
  <?php  include('footer.php') ?>