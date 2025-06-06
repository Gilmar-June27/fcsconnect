
<footer id="footer" class="footer dark-background">

<div class="container">
  <div class="row gy-3">
    <div class="col-lg-3 col-md-6 d-flex">
      <i class="bi bi-geo-alt icon"></i>
      <div class="address">
        <h4>Address</h4>
        <p>Buenavista Bohol</p>
        <p>Buenavista Community College</p>
        <p></p>
      </div>

    </div>

    <div class="col-lg-3 col-md-6 d-flex">
      <i class="bi bi-telephone icon"></i>
      <div>
        <h4>Contact</h4>
        <p>
          <strong>Phone:</strong> <span>+1 5589 55488 55</span><br>
          <strong>Email:</strong> <span>fsc-onnect@gmail.com</span><br>
        </p>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 d-flex">
      <i class="bi bi-clock icon"></i>
      <div>
        <h4>Opening Hours</h4>
        <p>
          <strong>Mon-Sat:</strong> <span>11AM - 23PM</span><br>
          <strong>Sunday</strong>: <span>Closed</span>
        </p>
      </div>
    </div>

    <div class="col-lg-3 col-md-6">
      <h4>Follow Us</h4>
      <div class="social-links d-flex">
  
        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
        <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
        <a href="#" class="linkedin"><i class="bi bi-youtube"></i></a>
      </div>
    </div>

  </div>
</div>

<div class="container copyright text-center mt-4">
  <p>© <span>Copyright</span> <strong class="px-1 sitename">FCs-Connect</strong> <span>All Rights Reserved</span></p>
  <div class="credits">
    <!-- All the links in the footer should remain intact. -->
    <!-- You can delete the links only if you've purchased the pro version. -->
    <!-- Licensing information: https://bootstrapmade.com/license/ -->
    <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
    Designed by <a href="#">Gilmar Aparece</a>
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
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

<!-- Main JS File -->
<script src="assets/js/main.js"></script>
<style>
    .message-box {
    padding: 10px;
    margin: 10px 0;
    text-align: end;
    border-radius: 5px;
    position: fixed;
    top: 20px;
    right: 0;
    transform: translateX(-50%);
    z-index: 1000;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

.close-btn {
    margin-left: 10px;
    cursor: pointer;
}

</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messages = document.querySelectorAll('.message-box');

        messages.forEach(function(message) {
            // Fade in effect
            message.style.opacity = '0';
            message.style.transition = 'opacity 1s ease';
            setTimeout(function() {
                message.style.opacity = '1';
            }, 100); // Delay to start the fade-in effect

            // Fade out after 3 seconds and then remove the message
            setTimeout(function() {
                message.style.opacity = '0';
                setTimeout(function() {
                    message.remove();
                }, 1000); // Delay before removing the message element
            }, 3000); // Delay before starting the fade-out
        });

        // Close button functionality
        const closeButtons = document.querySelectorAll('.close-btn');
        closeButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const parent = btn.parentElement;
                parent.style.opacity = '0';
                setTimeout(function() {
                    parent.remove();
                }, 1000); // Same fade-out effect
            });
        });
    });
</script>
</body>

</html>