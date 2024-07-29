
<!DOCTYPE html>
<?php
session_start();
        
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/CIP-1101 FINAL PROJECT-20240522T032656Z-001/CIP-1101 FINAL PROJECT/nav.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <script type="text/javascript"
    src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js">
</script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="./css/nav.css">
    <title>CLIENT HOME</title>

</head>
<body style="background-image:url('./assets/bg.png');">


    <!-- Navbar start -->
    <section class="m-0 sticky-top px-0">
        <div class="container-fluid px-0">
            <div class="container-fluid topbar d-none d-lg-block" style="background-color: #d2a9e3;">
                <div class="container">
                    <div class="topbar-top d-flex justify-content-between flex-lg-wrap">
                    
                    </div>
                    <div class="top-link d-flex justify-content-between align-items-center">
                        <div class="d-flex icon align-items-center">
                            <p class="mb-0 text-white me-4 fw-bold" style="font-family: century gothic;">FOLLOW US:</p>
                            <a href="https://www.facebook.com/personalisedbyjams" class="me-2 text-white"><i class="fab fa-facebook-f text-body link-hover"></i></a>
                            <a href="https://www.instagram.com/personalisedbyjams?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="me-2"><i class="fab fa-instagram text-body link-hover"></i></a>
                            <a href="https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.etsy.com%2Fau%2Fshop%2FPersonalisebyJams%3Fref%3Dprofile_header%26fbclid%3DIwZXh0bgNhZW0CMTAAAR1xQ2WnF237qJwHC3554EQpZHlC8_AGJ8S5FqkMZl_V3YiKp7WDCzFshzI_aem_AbJbMM6ZKIJ0WN6DQehRIa-ySJqQDF70hVmIkngriPWPbWo-_XKYyXiMC-UIK_QAJpyL0xsJlYgoxpT2lsV2tjRp&h=AT2KPAa1jhkgIgZU4bLUmS6JjC83DLGsXSUzG9fBVPplnsS915ZbjLkpzPok0Ov13Oeprlr4YZegVEbYeKc9N2hCg--7arT1ENBplD_D1Qw2CDPAWFOYmg7QzaytVL2Efe-fWg" class="me-2"><i class="bi bi-shop-window text-body link-hover"></i></a>
                        </div>
                        <?php if(!isset($_SESSION['username'])): ?>
                        <div class="login_signup">
                            <a href="./login.php" class="login">Login</a> |
                            <a href="./register.php" class="signup"> Sign Up</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="sticky-top px-0">
        <div class="container-fluid" style="background-color: #faecf5;">
            <div class="container">
                <nav class="navbar navbar-expand-xl">
                    <a href="index.php" class="navbar-brand mt-3">
                        <div class="container-fluid">
                            <span><img src="./assets/banner-new.jpg" class="container-fluid" style="height: 75px"></span>
                        </div>
                    </a>
                    <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    </button>
                    <div class="collapse navbar-collapse py-3" id="navbarCollapse">
                        <div class="navbar-nav mx-auto border-top fw-bold">
                            <a href="./index.php" class="nav-item nav-link active">Home</a>
                            <a href="./pradak.php" class="nav-item nav-link ">Products</a>
                            <a href="./review.php" class="nav-item nav-link">Reviews</a>
                            <a href="./contact.php" class="nav-item nav-link">Contact us</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link ">About <i class="bi bi-caret-down-fill"></i></a>
                                <div class="dropdown-menu m-0 bg-secondary rounded-0" style="height: 130px; width: 60px; font-size: 15px">
                                    <a href="./about.php" class="dropdown-item">The Company</a>
                                    <a href="./Personnel.php" class="dropdown-item">Personnel</a>
                                    <a href="./gallery.php" class="dropdown-item">Gallery</a>

                                </div>
                            </div>
                            <?php if(isset($_SESSION['username'])): ?>
                            <a href="./view_cart.php" class="nav-item nav-link active"><i class="bi bi-cart4 cart-hover"></i></a>
                            <?php endif; ?>
                        </div>
                        <?php if(isset($_SESSION['username'])): ?>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link"><i class="bi bi-person-circle"> Hello, <?php echo $_SESSION['first_name']; ?></i> </a>
                            <ul class="dropdown-menu">
                                <img src="./assets/logo.jpg"><br>
                                <h6><?php echo $_SESSION['full_name']; ?></h6>
                                <p>@<?php echo $_SESSION['username']; ?></p>
                                <hr>
				                <li><a class="dropdown-item" href="view_orders.php"><i class="bi bi-list-ul"></i> Your Order</a></li>
                                <li><a class="dropdown-item" style="text-align: left" href="edit_profile.php"><i class="bi bi-pencil-square"></i> Edit profile</a></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
                            </ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </nav>
            </div>
        </div>
    </section>
    <!-- Navbar end -->



<!-- Main Post Section Start -->

<div class="container-lg my-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
            <div class="container-fluid">
            <div class="row">
              <div class="col-md-7">
                <!-- Column 1 content here -->
               
                <h5>About</h5>
                <h4>Personalised by Jams</h4>

                <p>Personalised by Jams is dedicated to creating unique and memorable gifts tailored to your special moments. Our passion lies in crafting custom items that resonate with personal stories and cherished memories.
                For over a decade, we have worked tirelessly to provide high-quality products that reflect the individuality of our customers. Whether it’s a birthday, anniversary, or any occasion worth celebrating, our team is committed to delivering exceptional service and craftsmanship.

                At Personalised by Jams, we believe that every gift should tell a story. That’s why we pay meticulous attention to detail, ensuring each piece is not only beautiful but also meaningful. We take pride in our ability to bring your ideas to life, transforming them into treasured keepsakes.

                Join us in celebrating life’s special moments with personalized gifts that truly stand out.</p>
              </div>
              <div class="col-md-5">
                <!-- Column 2 content here -->
                <img src ="./assets/pbjlogo.png">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    
  </div>
</div>
 <!-- PRODUCT OVERVIEW-->

<!-- Main Post Section End -->

<!-- START CONTACT US -->
<div class="container-lg my-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card" style="background-color: #f795bd";>
        <div class="card-body" >
            <div class="container-fluid">
            <div class="row">
            <div class="col-md-5" >
                <!-- Column 2 content here -->
                 <img src="./assets/prev1.jpg" style="border-radius: 20px;">
              
            </div>
            <div class="col-md-7">
                <!-- Column 1 content here -->
                <h4 style="font-size:70px; color:#faecf5; padding: 20px";>Connect with PBJ</h4>
                <p style="font-size: 18px; padding: 20px";>
                        At Personalised by Jams, we cherish the connections we build with our customers. Our team is dedicated to understanding your needs and bringing your unique ideas to life. Whether you have questions about our products or need assistance with your order, we’re here to help every step of the way.
                        We invite you to reach out and discover how we can create something special together. Your satisfaction is our priority, and we look forward to making your gift-giving experience truly memorable.
                 </p>
                 <div class="text-center" style="padding: 20px;">
                    <a href="contact.php" style="background-color: #faecf5; color:#f795bd; padding:10px; border-radius: 10px; text-decoration:none; font-weight:bolder;">Contact us</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    
  </div>
</div>
  <!-- END CONTACT US -->



<footer class="bg-light text-center text-lg-start">
    <!-- Grid container -->
    <div class="container p-4">
      <!--Grid row-->
      <div class="row">
        <!--Grid column-->
        <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
          <h5 class="text-uppercase  fw-bold"><span class=" fw-bold" style="color: #f795bd;"> PBJams </span>Personalised by Jams</h5>
          <p>
            Looking for unique and personalized gifts that will make a lasting impression? Look no further than Personalised by Jams! Personalised by Jams is a small business that specializes in creating custom gifts that are perfect for any occasion
          </p>
        </div>
        <!--Grid column-->
  
        <!--Grid column-->
        <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
          <h5 class="text-uppercase fw-bold" style="color: #f795bd;">LINKS</h5>
          <ul class="list-unstyled mb-0">
            <li>
              <a href="./index.php" class="text-dark">Home</a>
            </li>
            <li>
              <a href="./about.php" class="text-dark">About</a>
            </li>
            <li>
              <a href="./pradak.php" class="text-dark">Products</a>
            </li>
            <li>
              <a href="./contact.php" class="text-dark">Contact us</a>
            </li>
          
          </ul>
        </div>
        <!--Grid column-->
  
        <!--Grid column-->
        <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
          <h5 class="text-uppercase fw-bold" style="color: #f795bd;">STAY CONNECTED</h5>
          <ul class="list-unstyled mb-0">
            <li><i class="bi bi-facebook icon-color" ></i>
              <a href="https://www.facebook.com/personalisedbyjams" class="text-dark">Facebook</a>
            </li>
            <li><i class="bi bi-instagram icon-color"></i>
              <a href="https://www.instagram.com/personalisedbyjams?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="text-dark">Instagram</a>
            </li>
            <li><i class="bi bi-shop-window icon-color"></i>
              <a href="https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.etsy.com%2Fau%2Fshop%2FPersonalisebyJams%3Fref%3Dprofile_header%26fbclid%3DIwZXh0bgNhZW0CMTAAAR1xQ2WnF237qJwHC3554EQpZHlC8_AGJ8S5FqkMZl_V3YiKp7WDCzFshzI_aem_AbJbMM6ZKIJ0WN6DQehRIa-ySJqQDF70hVmIkngriPWPbWo-_XKYyXiMC-UIK_QAJpyL0xsJlYgoxpT2lsV2tjRp&h=AT2KPAa1jhkgIgZU4bLUmS6JjC83DLGsXSUzG9fBVPplnsS915ZbjLkpzPok0Ov13Oeprlr4YZegVEbYeKc9N2hCg--7arT1ENBplD_D1Qw2CDPAWFOYmg7QzaytVL2Efe-fWg" class="text-dark">Etsy</a>
            </li>
            <!-- <li><i class="bi bi-twitter-x icon-color"></i>
              <a href="#!" class="text-dark">Twitter</a>
            </li> -->
          </ul>
        </div>
      </div>
    </div>
  </footer>

  
    <!-- script src start-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <!-- Bootstrap and EmailJS scripts -->
<!-- Include Bootstrap JS and EmailJS -->
<script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

<script src="../script.js"></script>
    <!-- script src end -->

   

</body>
</html>