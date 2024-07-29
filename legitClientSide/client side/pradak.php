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

// Handle adding products to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $personalization = $_POST['personalization'];
    $user_id = $_SESSION['user_id']; // Assuming you have the user ID stored in the session

    // Fetch the available stock for the product
    $stmt = $conn->prepare("SELECT quantity FROM products WHERE p_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($availableStock);
    $stmt->fetch();
    $stmt->close();

    // Insert or update cart in the database with stock
    if ($availableStock >= $quantity) {
        $stmt = $conn->prepare("INSERT INTO cart (u_id, p_id, quantity, personalization) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity), personalization = VALUES(personalization)");
        $stmt->bind_param("iiis", $user_id, $product_id, $quantity, $personalization);

        if (!$stmt->execute()) {
            echo "<script>alert('Error: " . addslashes($stmt->error) . "');</script>";
        } else {
            echo "<script>alert('Product added to cart successfully.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Insufficient stock for this product. Available: $availableStock'); window.history.back();</script>";
    }
}

// Fetch products and images
$sql = "SELECT products.p_id, products.name, products.quantity, products.price, products.details, images.image, products.persona 
        FROM products 
        JOIN images ON products.p_id = images.p_id 
        WHERE products.quantity > 0
        ORDER BY products.p_id";
$result = $conn->query($sql);

$products = [];
while ($row = $result->fetch_assoc()) {
    $product_id = $row['p_id'];
    $products[$product_id]['details'] = [
        'name' => $row['name'],
        'quantity' => $row['quantity'],
        'price' => $row['price'],
        'details' => $row['details'],
        'persona' => $row['persona'],
    ];
    // Store all images for the product
    $products[$product_id]['images'][] = base64_encode($row['image']);
}

$conn->close();
?>



<html lang="en">

<div id="myModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <p style="font-weight: bold;">NOTICE</p>
      <span class="close">&times;</span>
    </div>
    <div class="modal-body">
      <span id="modalMessage"></span>
      
    </div>
  </div>
</div>
    

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/CIP-1101 FINAL PROJECT-20240522T032656Z-001/CIP-1101 FINAL PROJECT/nav.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <script type="text/javascript"
    src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js">
</script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/pradak.css">
    <link rel="stylesheet" href="./css/nav.css">
    <title>Products</title>

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
                            <a href="./pradak.php" class="nav-item nav-link active ">Products</a>
                            <a href="./review.php" class="nav-item nav-link ">Reviews</a>
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

<!-- CARD -->
<div class="container mt-3">
    <h2 class="display-4">Our Products</h2><br>
    <div class="row row-cols-1 row-cols-md-4">
        <?php foreach ($products as $product_id => $product): ?>
            <div class="col">
                <div class="card-size h-100">
                    <div id="carousel<?= $product_id ?>" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner h-100">
                            <?php foreach ($product['images'] as $index => $image): ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <img src="data:image/jpeg;base64,<?= $image ?>" class="d-block w-100 fixed-size" alt="<?= $product['details']['name'] ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $product['details']['name'] ?></h5><br> 
                        <p class="card-text">$ <?= number_format($product['details']['price'], 2) ?></p>
                        <small class="small">Stock: <?= $product['details']['quantity'] ?></small><br><br>
                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#productModal<?= $product_id ?>">
                            View Details
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Modal -->
            <div class="modal fade" id="productModal<?= $product_id ?>" tabindex="-1" aria-labelledby="productModalLabel<?= $product_id ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable custom-modal-dialog">
        <div class="modal-content custom-modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div id="carouselModal<?= $product_id ?>" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php foreach ($product['images'] as $index => $image): ?>
                                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                        <img src="data:image/jpeg;base64,<?= $image ?>" class="d-block w-100" alt="<?= $product['details']['name'] ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselModal<?= $product_id ?>" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselModal<?= $product_id ?>" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                    <div class="modal-text col-md-6">
                        <h5 class="modal-title" id="productModalLabel<?= $product_id ?>"><?= $product['details']['name'] ?></h5>
                        <p class="mt-3"><?= $product['details']['details'] ?></p>
                        <p class="card-text" style="display: flex; justify-content: space-between; align-items: center;">
                            $ <?= number_format($product['details']['price'], 2) ?>
                            <span><small class="small">Stock: <?= $product['details']['quantity'] ?></small></span>
                        </p>
                        <div class="mb-3">
                            <div class="quantity-controller mt-3">
                                <label for="quantity<?= $product_id ?>" class="form-label" style="font-weight: bold;">Quantity:</label>
                                <div class="input-group">
                                    <button class="minus btn btn-outline-secondary" type="button" onclick="decrementQuantity(<?= $product_id ?>)">-</button>
                                    <input type="number" id="quantity<?= $product_id ?>" name="quantity" class="form-control text-center" min="1" max="<?= $product['details']['quantity'] ?>" value="1" required>
                                    <button class="plus btn btn-outline-secondary" type="button" onclick="incrementQuantity(<?= $product_id ?>)">+</button>
                                </div>
                            </div><br>
                            <form method="post" action="" onsubmit="return checkLogin(<?= $product_id ?>)">
                                <p><strong>Note:</strong> <?= $product['details']['persona'] ?></p>
                                <textarea class="form-control personalization-textarea" id="personalizationTextarea<?= $product_id ?>" name="personalization"></textarea>
                                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                <input type="hidden" name="product_name" value="<?= $product['details']['name'] ?>">
                                <input type="hidden" name="product_price" value="<?= $product['details']['price'] ?>">
                                <input type="hidden" name="product_persona" value="<?= $product['details']['persona'] ?>">
                                <input type="hidden" name="quantity" id="hiddenQuantity<?= $product_id ?>" value="1">
                                <button type="submit" class="btn btn-success"><i class="bi bi-cart-check"></i> Add to Cart</button>
                                <div id="loginWarning<?= $product_id ?>" class="text-danger" style="display: none;">Please log in first in order to add products to cart.</div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
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
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

<script>
function checkLogin(productId) {
    <?php if (!isset($_SESSION['user_id'])): ?>
        document.getElementById('loginWarning' + productId).style.display = 'block';
        return false; // Prevent form submission
    <?php endif; ?>
    return true; // Allow form submission
}

function incrementQuantity(productId) {
    const quantityInput = document.getElementById('quantity' + productId);
    const currentQuantity = parseInt(quantityInput.value);
    const maxQuantity = parseInt(quantityInput.max);

    if (currentQuantity < maxQuantity) {
        quantityInput.value = currentQuantity + 1;
        document.getElementById('hiddenQuantity' + productId).value = quantityInput.value;
    } else {
        alert("Cannot exceed available stock of " + maxQuantity + "!");
    }
}

function decrementQuantity(productId) {
    const quantityInput = document.getElementById('quantity' + productId);
    const currentQuantity = parseInt(quantityInput.value);

    if (currentQuantity > 1) {
        quantityInput.value = currentQuantity - 1;
        document.getElementById('hiddenQuantity' + productId).value = quantityInput.value;
    }
}

// Add event listener for manual input
function validateQuantityInput(productId) {
    const quantityInput = document.getElementById('quantity' + productId);
    const maxQuantity = parseInt(quantityInput.max);

    quantityInput.addEventListener('input', function() {
        const inputQuantity = parseInt(quantityInput.value);
        if (inputQuantity > maxQuantity) {
            alert("Cannot exceed available stock of " + maxQuantity + "!");
            quantityInput.value = maxQuantity; // Reset to max if over
        } else if (inputQuantity < 1) {
            quantityInput.value = 1; // Reset to 1 if under
        }
        document.getElementById('hiddenQuantity' + productId).value = quantityInput.value; // Update hidden input
    });
}

// Call validateQuantityInput for each product when the modal is shown
document.addEventListener('DOMContentLoaded', function() {
    <?php foreach ($products as $product_id => $product): ?>
        validateQuantityInput(<?= $product_id ?>);
    <?php endforeach; ?>
});


</script>
</body>
</html>
