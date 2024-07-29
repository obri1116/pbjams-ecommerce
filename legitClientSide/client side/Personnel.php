<!DOCTYPE html>
<?php
session_start();
?>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Personnel</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./css/styles.css" />
    <link rel="stylesheet" href="./css/nav.css" />
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
    margin: 0 0 30px 1005px;
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
.navbar {
    padding: 1rem;
}
.navbar-brand {
    color: #d63384;
    font-weight: bold;
    height: auto;
    max-height: 75px;
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

/* .about-img {
    border-radius: 0px;
} */

.card {
  padding: 23px;
  border-color: transparent;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  position: relative;
  float: center;
  background-color: rgba(255, 255, 254, 0.2);
  border-radius: 20px;
  width: 340px;
  height: 400px;
  transition: transform 0.3s, box-shadow 0.3s;

}


.card-custom {
    border: none;
    margin-bottom: 30px;
}

.card-body-custom {
    padding: 0px;
}


.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

figure {
    display: grid;
    border-radius: 1rem;
    overflow: hidden;
    cursor: pointer;
    width: 97%;
    height: 175px; /* Fixed height for the figure */
    position: relative;
}

figure img {
    width: 100%; /* Fill the width */
    height: 100%; /* Fill the height */
    transition: transform 0.4s; /* Smooth scaling effect */
}

figure figcaption {
    display: grid;
    align-items: end;
    font-family: century gothic;
    font-size: 2rem;
    font-weight: bold;
    color: transparent; /* Use transparent for masking effect */
    padding: 0.75rem;
    background: var(--c, #0009);
    margin: 0; /* Reset margin */
    transition: color 0.4s; /* Smooth color transition */
}

figure:hover figcaption {
    color: #fff; /* Change color on hover */
}

figure:hover img {
    transform: scale(1.1); /* Slightly reduced scale */
}
.centered-container {
        display: flex;
        justify-content: center;
    }
    h1 {
    font-family: 'Century Gothic', sans-serif;
    text-align: center; 
    font-weight: bold; 
    color: #d63384; 
    margin-top: 30px;
    margin-bottom: 30px; 
    font-size: 2.5rem; 
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1); 
}
.fade-in {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.5s ease, transform 0.5s ease;
}

.fade-in.visible {
    opacity: 1;
    transform: translateY(0);
}

@media (max-width: 768px) {
    .navbar-nav {
        flex-direction: column;
        align-items: center;
    }

    .header-design h1 {
        font-size: 2rem;
    }

    .quantity-control {
        flex-direction: column;
    }
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
                            <a href="./index.php" class="nav-item nav-link ">Home</a>
                            <a href="./pradak.php" class="nav-item nav-link ">Products</a>
                            <a href="./review.php" class="nav-item nav-link">Reviews</a>
                            <a href="./contact.php" class="nav-item nav-link">Contact us</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link active ">About <i class="bi bi-caret-down-fill"></i></a>
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
    <!-------------------MAIN BODY--------------->
    <h1>Behind of every crafts made by Personalized by Jams</h1>
    <div class="container mt-5 centered-container ">
        <div class="row row-cols-1 row-cols-md-3 d-flex justify-content-center " >
            <div class="col" >
                <div class="card ">
                    <figure>
                        <img src="./assets/graphic designer.jpg" alt="pic"  >
                    </figure>
                    <h5>Margarette Martin</h5>
                    <h6 style="color:#d63384; font-weight:bolder;"><i class="bi bi-brush-fill"></i> PBJ Graphic Artist</h6>
                    <p>Marga is the graphic artist who has been working with us for a decade years for PBJams.</p>
                    

                </div>
            </div>
            <!-- Card for CEO -->
            <div class="col">
                <div class="card">
                  <figure>
                    <img src="./assets/ceo.jpg">
                  </figure>
                  <h5>Jamile Bobadillas</h5>
                    <h6 style="color:#d63384; font-weight:bolder;"><i class="bi bi-person-heart"></i> PBJ C.E.O</h6>
                    <p>Mrs. Bobadilla is the one who build, manage, study everything about business to make customers satisfied.</p>
                </div>
            </div>
            <!-- Card for COO -->
            <div class="col">
                <div class="card ">
                  <figure>
                    <img src="./assets/husband.jpg">
                  </figure> 
                  <h5>Edwin Bobadillas</h5>
                    <h6 style="color:#d63384; font-weight:bolder;"><i class="bi bi-person-hearts"></i> PBJ C.O.O</h6>
                    <p>Mr. Edwin brings PBJams to crafting unique designs that resonate with our customers.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light text-center text-lg-start mt-5">
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
  </body>
</html>
