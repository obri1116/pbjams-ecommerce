<!DOCTYPE html>
<?php
session_start();

?>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Events</title>
    <!-- CSS BS START -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="./css/nav.css" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <!-- CSS BS END -->
    <style>
       .header-design {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .header-design h1 {
            font-family: 'Century Gothic', sans-serif;
            font-weight: bold;
            color: black;
        }
        .header-design p {
            font-family: 'Century Gothic', sans-serif;
            font-size: 1.1rem;
            color: #d63384;
            font-weight: bold;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 120px;
            margin: 10px auto;
        }
        .quantity-control button {
            background-color: #d63384;
            border: none;
            color: white;
            padding: 5px 10px;
            font-size: 16px;
            cursor: pointer;
        }
        .quantity-control button:disabled {
            background-color: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
        }
        .quantity-display {
            margin: 0 10px;
            font-size: 16px;
            font-weight: bold;
        }
        .product-price {
            display: flex;
            justify-content: space-between;
        }
        .stock-display {
            margin-left: 10px;
            margin-top: 5px;
            font-size: 0.9rem;
            color: red;

        }
        .search-bar {
            display: flex;
            align-items: center;
            margin: 0px 0px 30px 1005px;
        }
        .search-input {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px 30px;
            margin-right: 10px;
        }
        .search-button {
            background-color: #d63384;
            border: none;
            color: white;
            padding: 5px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .dropdown-menu-end {
            max-width: 300px;
            overflow-y: auto;
            border: none;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .dropdown-item {
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .item-details {
            flex: 1;
            margin-right: 10px;
        }
        .item-quantity {
            width: 50px;
            text-align: center;
        }
        .item-price {
            margin-left: 10px;
        }
        .total {
            font-weight: bold;
            padding: 10px;
            background-color: #d63384;
            color: #fff;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        .dropdown-header {
            background-color: #f8f9fa;
            padding: 10px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .dropdown-divider {
            margin: 0;
        }
        .navbar-brand {
            color: #d63384;
            font-weight: bold;
        }
        .navbar-light .navbar-nav .nav-link {
            color: #333;
        }
        .bi-cart4 {
            color: #d63384;
        }
        .bi-cart4:hover {
            color: #8c1b4f;
        }
        .quantity-controls {
            display: flex;
            align-items: center;
            margin: 20px;
        }
        .quantity-btn {
            padding: 0 8px;
            border: none;
            background: transparent;
            cursor: pointer;
        }
        .product_card .product-image {
            height: 200px; /* Adjust this value to your desired image height */
            object-fit: cover; /* Ensures the image covers the entire area */
        }
    </style>
  </head>
  <body  style="background-image:url('./assets/bg.png');">

    
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
                            <a href="./index.php" class="nav-item nav-link">Home</a>
                            <a href="./pradak.php" class="nav-item nav-link ">Products</a>
                            <a href="./review.php" class="nav-item nav-link">Reviews</a>
                            <a href="./contact.php" class="nav-item nav-link">Contact us</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link active ">About <i class="bi bi-caret-down-fill"></i></a>
                                <div class="dropdown-menu m-0 bg-secondary rounded-0" style="height: 130px; width: 60px; font-size: 15px">
                                    <a href="./about.php" class="dropdown-item">The Company</a>
                                    <a href="./Personnel.php" class="dropdown-item">Personnel</a>
                                    <a href="./event.php" class="dropdown-item">Events</a>
                                </div>
                            </div>
                            <?php if(isset($_SESSION['username'])): ?>
                            <a href="./view_cart.php" class="nav-item nav-link active"><i class="bi bi-cart4 cart-hover"></i></a>
                            <?php endif; ?>
                        </div>
                        <?php if(isset($_SESSION['username'])): ?>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link"><i class="bi bi-person-circle"> Hello, <?php echo $_SESSION['first_name']; ?></i> </a>
                            <ul class="dropdown-menu" style="height:335px">
                                <img src="./assets/logo.jpg"><br>
                                <h6><?php echo $_SESSION['first_name']; ?></h6>
                                <p>@<?php echo $_SESSION['username']; ?></p>
                                <hr>
                                <li><a class="dropdown-item" href="view_orders.php"><i class="bi bi-list-ul"></i> Your Order</a></li>
                                <li><a class="dropdown-item" href="edit_profile.php"><i class="bi bi-pencil-square"></i> Edit profile</a></li>
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


  <div class="border-bottom py-3 " style=" text-align: center;">
    <p class="display-5 text-dark mb-0 mt-3">EVENTS BY</p>
    <p class="display-2 fw-bold mb-0" style="color: #f795bd;">PERSONALISED BY JAMS</a>
    <p class ="h5 mt-3 fw-bold text-danger">
        <a href="https://www.facebook.com/personalisedbyjams">
          <span class ="btn text-dark"><i class="bi bi-facebook icon-color" ></i> Personalised by Jams</span>
        </a>
        <a href="https://www.instagram.com/personalisedbyjams?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==">
        <span class ="btn text-dark"><i class="bi bi-instagram icon-color" ></i> @personalisedbyjams</span>
        </a>
        <a href="https://l.facebook.com/l.php?u=https%3A%2F%2Fwww.etsy.com%2Fau%2Fshop%2FPersonalisebyJams%3Fref%3Dprofile_header%26fbclid%3DIwZXh0bgNhZW0CMTAAAR1xQ2WnF237qJwHC3554EQpZHlC8_AGJ8S5FqkMZl_V3YiKp7WDCzFshzI_aem_AbJbMM6ZKIJ0WN6DQehRIa-ySJqQDF70hVmIkngriPWPbWo-_XKYyXiMC-UIK_QAJpyL0xsJlYgoxpT2lsV2tjRp&h=AT2KPAa1jhkgIgZU4bLUmS6JjC83DLGsXSUzG9fBVPplnsS915ZbjLkpzPok0Ov13Oeprlr4YZegVEbYeKc9N2hCg--7arT1ENBplD_D1Qw2CDPAWFOYmg7QzaytVL2Efe-fWg">
        <span class ="btn btn-color"><i class="bi bi-shop-window icon-color"></i> Personalised by Jams</span>
        </a>
      </p>
    </p>
</div>

    <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
      <h2
        class="text-center p-3 text-dark fw-bold"
        style="font-family: century gothic; font-size: 4rem"
      >
        
      </h2>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <div class="container-lg my-5">
            <div class="row">
              <div class="col-md-4">
                <img
                  class="d-block w-100 rounded"
                  src="./assets/hbd.jpg"
                  alt="First slide"
                  style="height: 350px;"
                />
              </div>
              <div class="col-md-8">
                <div class="text-container d-none d-md-block">
                  <h1 class="fw-bold">
                    Happy 1st Birthday Nathaniel ! 🐮🐷🚜🌾
                  </h1>
                  <p class="text-gray">
                    Check out our personalised souvenirs that will make your celebration even more memorable! 🎉✨💙 <br> <br>
              •Personalised Chocolate bar✔️ <br>  
              •Personalised gable box ✔️ <br><br>

                If you any have upcoming events or thinking to give a gift to your loved ones, just check our page or message me. More than happy to brainstorm ideas with you 😊. <br><br>
                #personalisedbyjams #personalisedgifts #createdwithlove #pbjams #SupportSmallBusiness #keepsake #baptism #birthday #thankyouforyourorder
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Second Slide -->
        <div class="carousel-item">
          <div class="container-lg my-5">
            <div class="row">
              <div class="col-md-4">
                <img
                  class="d-block w-100 rounded"
                  src="./assets/pokemon.jpg"
                  alt="Second slide"
                  style="height: 350px;"
                />
              </div>
              <div class="col-md-8">
                <div class="text-container d-none d-md-block">
                  <h1 class="fw-bold">
                    ✨Pokemon theme party ! <br>
                  </h1>
                  <p class="text-gray">
                    Get ready to wow your guests with these customised loot bags! Personal touches make all the difference, making every event unforgettable. <br><br>
                    Planning an event? We've got your back! Let us know how we can make it extra special for you. <br> <br>
                    #personalisedbyjams #birthday #souvenirs #party #SupportSmallBusiness
                  </p>
               
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Third Slide -->
        <div class="carousel-item">
          <div class="container-lg my-5">
            <div class="row">
              <div class="col-md-4">
                <img
                  class="d-block w-100 rounded"
                  src="./assets/harry-apple.jpg"
                  alt="Third slide"
                  style="height: 350px;"
                />
              </div>
              <div class="col-md-8">
                <div class="text-container d-none d-md-block ">
                  <h1 class="fw-bold">
                    HARRY & APPLE WEDDING DAY 💐💖🌟
                  </h1>
                  <p class="text-gray">
                    Delight your guests with personalised mugs as unforgettable wedding souvenirs. Cherish the special day with a practical and heartfelt keepsake. <br><br>
If you any have upcoming events or thinking to give a gift to your loved ones, just check our page or message me. More than happy to brainstorm ideas with you 😊💜.  <br><br>
#Personalisedbyjams  #birthdayparty #birthdaycake #personalisedgifts #giveaway #SupportSmallBusiness #baptism #wedding
                  </p>

                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Fourth Slide -->
        <div class="carousel-item">
          <div class="container-lg my-5">
            <div class="row">
              <div class="col-md-4">
                <img
                  class="d-block w-100 rounded"
                  src="./assets/wp.jpg"
                  alt="Fourth slide"
                  style="height: 350px;"
                />
              </div>
              <div class="col-md-8">
                <div class="text-container d-none d-md-block">
                  <h1 class="fw-bold">
                    3 IN 1 WORKPAD FOR KIDS
                  </h1>
                  <p class="text-gray">
                    Spark your child's creativity with our customisable 3-1 workpad ! Watch their skills grow as they explore and learn in a fun and engaging way. Ideal for little ones eager to develop essential motor skills while having endless fun! ✏️🌟👶 <br><br>

                    •80 Pages of Activities <br>
                    •Tracing  <br>
                    • Colouring  <br>
                    • Counting <br>
                    •30 Theme Cover Design Available ( Can personalised) <br>
                    A5 size <br><br>
                    #personalisedbyjams #SupportSmallBusiness #birthday #souvenirs #party
                  </p>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <button
        class="carousel-control-prev"
        type="button"
        data-bs-target="#myCarousel"
        data-bs-slide="prev"
      >
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button
        class="carousel-control-next"
        type="button"
        data-bs-target="#myCarousel"
        data-bs-slide="next"
      >
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>

    <!-- CAROUSEL END -->

<!-- footer start -->
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
    <!-- JS BS START-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <!-- JS BS END-->
  </body>
</html>
