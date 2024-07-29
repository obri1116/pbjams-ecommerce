<!DOCTYPE html>
<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "client_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch gallery details
$stmt = $conn->prepare("SELECT 
    e.e_id, 
    e.e_title, 
    e.e_caption, 
    e.type 
FROM 
    gallery e");
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[$row['e_id']]['details'] = $row;
}

// Fetch gallery images
$images_stmt = $conn->prepare("SELECT 
    img_id, 
    e_id, 
    img_event 
FROM 
    gallery_images");
$images_stmt->execute();
$images_result = $images_stmt->get_result();

while ($row = $images_result->fetch_assoc()) {
    $products[$row['e_id']]['images'][] = base64_encode($row['img_event']);
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="./css/gallery.css">
    <title>Gallery</title>
</head>
<body style="background-image:url('./assets/bg.png');" > 
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
                    <div class="collapse navbar-collapse py-3" id="navbarCollapse">
                        <div class="navbar-nav mx-auto border-top fw-bold">
                            <a href="./index.php" class="nav-item nav-link ">Home</a>
                            <a href="./pradak.php" class="nav-item nav-link  ">Products</a>
                            <a href="./review.php" class="nav-item nav-link ">Reviews</a>
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

    
  <div class="border-bottom py-3 " style=" text-align: center;">
    <p class="display-5 text-dark mb-0 mt-3">RECENT EVENTS OF</p>
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

<!-- First Container: Images and text -->

<div class="container mt-5 custom-container ">
    <div class="row">
        <div class="col-md-12">
            <?php foreach ($products as $product_id => $product): ?>
                <div class="col-md-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="carousel<?= $product_id ?>" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <?php foreach ($product['images'] as $index => $image): ?>
                                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                                    <img src="data:image/jpeg;base64,<?= $image ?>" class="d-block w-100" alt="<?= htmlspecialchars($product['details']['e_title']) ?>">
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?= $product_id ?>" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carousel<?= $product_id ?>" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <h5 class="card-title" style="margin-left:50px"><?= htmlspecialchars($product['details']['e_title']) ?></h5>
                                    <p class="card-text"><span style="color: #f795bd; font-weight:bold; margin-left:50px;font-size:large"><?= htmlspecialchars($product['details']['type']) ?></span></p>
                                    <p class="card-text" style="margin-left:50px"><?= htmlspecialchars($product['details']['e_caption']) ?></p>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<footer class="bg-light text-center text-lg-start">
    <!-- Grid container -->
    <div class="container p-4 mt-5">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>
$(document).ready(function() {
    $('#galleryModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var eventId = button.data('id');

        $.ajax({
            url: 'get_gallery_details.php',
            method: 'GET',
            data: { e_id: eventId },
            success: function(data) {
                var details = JSON.parse(data);
                $('#modalTitle').text(details.e_title);
                $('#modalCaption').text(details.e_caption);
                var imagesHtml = '';
                details.images.forEach(function(image) {
                    imagesHtml += '<img src="data:image/jpeg;base64,' + image + '" class="img-fluid mr-2 mb-2" style="max-width: 100px; max-height: 100px;">';
                });
                $('#modalImages').html(imagesHtml);
            }
        });
    });
});
</script>
</body>
</html>