<!DOCTYPE html>
<?php
session_start();

?>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About Company</title>

    
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./css/nav.css" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
    />
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

      .product_card .product-image {
        height: 200px; /* Adjust this value to your desired image height */
        object-fit: cover; /* Ensures the image covers the entire area */
      }

      .about-img {
        transition: transform 0.3s ease;
        border-radius: 30px;
      }

      .about-img:hover {
        transform: scale(1.05);
      }

      .caro-header {
        animation: fadeInUp 1s ease;
        font-size: 50px;
      }

      .caro-p {
        animation: fadeIn 1.5s ease;
      }

      @keyframes fadeIn {
        from {
          opacity: 0;
        }
        to {
          opacity: 1;
        }
      }

      @keyframes fadeInUp {
        from {
          transform: translateY(20px);
          opacity: 0;
        }
        to {
          transform: translateY(0);
          opacity: 1;
        }
      }

      @media (max-width: 768px) {
        .search-bar {
          margin: 0 auto;
        }
        .header-design p {
          font-size: 1rem;
        }
      }

      @media (max-width: 576px) {
        .header-design h1 {
          font-size: 1.5rem;
        }
      }
      .carousel {
          width: 70%; /* Adjust carousel width */
          align-items: center;
          margin:auto;
        }

        .carousel-inner .carousel-item {
          transition: transform 1s ease-in-out; /* Smooth transition */
        }

        @media (max-width: 768px) {
          .carousel {
            width: 100%; /* Full width on smaller screens */
          }
        }
        .centered-container {
        display: flex;
        justify-content: center;
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
                            <a href="./main.php" class="nav-item nav-link ">Home</a>
                            <a href="./pradak.php" class="nav-item nav-link ">Products</a>
                            <a href="./review.php" class="nav-item nav-link">Reviews</a>
                            <a href="./contact.php" class="nav-item nav-link">Contact us</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link active ">About <i class="bi bi-caret-down-fill"></i></a>
                                <div class="dropdown-menu m-0 bg-secondary rounded-0" style="height: 130px; width: 60px; font-size: 15px">
                                    <a href="./about.php" class="dropdown-item ">The Company</a>
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
                                <h6 style="margin-top: -30px;"><?php echo $_SESSION['full_name']; ?></h6>
                                <p>@<?php echo $_SESSION['username']; ?></p>
                                <hr>
				                        <li><a class="dropdown-item" href="./view_orders.php"><i class="bi bi-list-ul"></i> Your Order</a></li>
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
    <!-------------------MAIN BODY--------------->
    <div class="row container-fluid" style="padding: 100px; padding-top:0px; text-align:justify">
      <!--------------POSSIBLE CAROUSEL------------->
      <!-----3 COLUMNS lg4 md4 sm2----->
      <div class="row container-fluid">
      <div class="row">
        <div class="col-lg-12 ms-4 mb-5 mt-3">
          <div
            id="myCarousel"
            class="carousel slide mb-6"
            data-bs-ride="carousel"
          >
            <div class="carousel-indicators">
              <button
                type="button"
                data-bs-target="#myCarousel"
                data-bs-slide-to="0"
                class="active"
                aria-current="true"
                aria-label="Slide 1"
              ></button>
              <button
                type="button"
                data-bs-target="#myCarousel"
                data-bs-slide-to="1"
                aria-label="Slide 2"
              ></button>
              <button
                type="button"
                data-bs-target="#myCarousel"
                data-bs-slide-to="2"
                aria-label="Slide 3"
              ></button>
            </div>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="./assets/caro1.1.jpg" class="caro-pic1 w-100" />
                <div class="container">
                  <div class="carousel-caption">
                    <h1 class="caro-header">Want to see more?</h1>
                    <p class="caro-p1 text-center">
                      click to the button to see our products
                    </p>
                    <p>
                      <a
                        class="btn btn-lg"
                        href="https://www.instagram.com/personalisedbyjams/"
                        target="_blank"
                        >Learn more</a
                      >
                    </p>
                  </div>
                </div>
              </div>
              <div class="carousel-item">
                <img src="./assets/caro1.2.jpg" class="caro-pic1 w-100" />
                <div class="container">
                  <div class="carousel-caption">
                    <h1 class="caro-header">Want to see more?</h1>
                    <p class="caro-p1 text-center">
                      click to the button to see our products
                    </p>
                    <p>
                      <a
                        class="btn btn-lg"
                        href="https://www.instagram.com/personalisedbyjams/"
                        target="_blank"
                        >Learn more</a
                      >
                    </p>
                  </div>
                </div>
              </div>
              <div class="carousel-item">
                <img src="./assets/caro1.jpg" class="caro-pic1 w-100" />
                <div class="container">
                  <div class="carousel-caption">
                    <h1 class="caro-header">Want to see more?</h1>
                    <p class="caro-p1">
                      click to the button to see our products
                    </p>
                    <p>
                      <a
                        class="btn btn-lg"
                        href="https://www.instagram.com/personalisedbyjams/"
                        target="_blank"
                        >Learn more</a
                      >
                    </p>
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
              <span
                class="carousel-control-prev-icon"
                aria-hidden="true"
              ></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button
              class="carousel-control-next"
              type="button"
              data-bs-target="#myCarousel"
              data-bs-slide="next"
            >
              <span
                class="carousel-control-next-icon"
                aria-hidden="true"
              ></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>
      </div>
      <!--------------END OF CAROUSEL------------->
      
    </div>
<div class="container-lg my-5 centered-container">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
            <div class="container-fluid">
            <div class="row">
              <div class="col-md-7">
              <h2 class="caro-header ms-5 mt5 fw-bold" >Our History</h2>
              
          <p class="caro-p ms-5 mt-5 text-justify">
            Personalised by Jams isn't just a store, it's a story. It all
            started with the desire to create gifts that were more than just
            presents - they were meant to be cherished memories. The founders,
            fueled by a passion for personalization and a love for making people
            smile, poured their creativity into crafting unique items that could
            be tailored to every occasion and every loved one. <br /><br />Their
            journey began with a focus on heartfelt keepsakes like keyrings and
            cake toppers. As word spread about their dedication to quality and
            customization, their product line blossomed to encompass a
            delightful array of giftables - from mugs that warm the heart to
            chocolate bars that satisfy both taste buds and sentimental
            cravings. <br /><br />Today, Personalised by Jams remains a
            family-run business, where each item is crafted with the same care
            and attention to detail as those first special pieces. Their
            commitment to innovation ensures they're constantly expanding their
            offerings, from custom pajamas for cozy nights in to elegant cheese
            boards for unforgettable gatherings. But at the heart of it all,
            Personalised by Jams remains true to its roots. <br /><br />
          </p>
          
              </div>
              <div class="col-md-5">
                <img src="./assets/history.jpg" class="about-img img-fluid ms-3 d-flex justify-content-center" style="width: 90%; max-width: 600px; height:80%; margin-top:90px" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


      
      <div class="row pb-5">
        <div class="col-md-7 order-md-2">
          <!---ORDER MD2 PARA MAG DULO--->
          
        </div>
        <div class="col-md-5">
          
        </div>
      </div>
      <hr>
      <div class="row">
      <div class="col-md-5">
          <img
            src="./assets/personalized invitation.jpg"
            class="about-img img-fluid w-40 ms-3"
            style="width: 90%; max-width: 600px; height:80%; margin-top:90px"
          />
        </div>
        <div class="col-md-7">
          <h2 class="caro-header ms-5 mt-5 fw-bold" style="text-align:left">
            Personalised by Jams: Highlights of a Heartfelt Brand
          </h2>
          <p class="caro-p text-justify ms-5 mt-5">
            While specific details about Personalised by Jams' history are
            unavailable on their Instagram account, here are some key highlights
            we can glean from their offerings:<br />
            <li class="ms-5">
              <b>Customization is King:</b>Personalization is at the core of
              their business. From keyrings engraved with a special message to
              mugs featuring a loved one's photo, they focus on creating unique
              and meaningful gifts.
            </li>
            <li class="ms-5">
              <b>Gifts for Every Occasion:</b>Birthdays, holidays, weddings, or
              "just because" moments - Personalised by Jams caters to all. Their
              diverse product range ensures you can find the perfect gift for
              any recipient and any event.
            </li>
            <li class="ms-5">
              <b>Expanding Horizons</b>They're not afraid to evolve! Starting
              with keepsakes, they've expanded to include practical items like
              mugs and chocolate, and even comfy pajamas and elegant
              cheeseboards. This demonstrates their commitment to offering a
              well-rounded selection.
            </li>
            <li class="ms-5">
              <b>Family-Run Heart:</b>The warm and personal touch in their
              products suggests a family-run business. This adds a layer of
              charm and care to their customer experience.
            </li>
          </p>
        </div>
        
      </div>
    </div>
   
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
              <a href="./main.php" class="text-dark">Home</a>
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
  </body>
</html>
