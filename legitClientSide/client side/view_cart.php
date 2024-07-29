<!DOCTYPE html>
<?php
session_start();

if (isset($_COOKIE['username']) && isset($_SESSION['access_count'])) {
    echo "<span class='welcome-message'>Welcome, " . $_COOKIE['first_name'] . " " . $_COOKIE['username'] . "!<br></span>";
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "client_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id']; // Assuming you have the user's ID stored in the session
// Fetch cart items
// Fetch cart items with one image per product
$sql = "
    SELECT 
        cart.cart_id,
        cart.p_id, 
        products.name, 
        cart.quantity, 
        products.price, 
        cart.personalization,
        products.quantity AS available_stock,
        MIN(images.image) AS image 
    FROM cart
    JOIN products ON cart.p_id = products.p_id
    JOIN images ON products.p_id = images.p_id
    WHERE cart.u_id = ?
    GROUP BY cart.cart_id, cart.p_id, products.name, cart.quantity, products.price, cart.personalization, products.quantity
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// After fetching the cart items
if ($result->num_rows == 0) {
    echo "<script>$(document).ready(function() { $('#emptyCartModal').modal('show'); });</script>";
}

$grandTotal = 0;
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">

    <title>VIEW CART</title>
    <style>
      <?php include "./css/viewcart.css" ?>
      <?php include "./css/nav.css" ?>
    </style>
</head>
<body>


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
                            <a href="" class="me-2"><i class="fab fa-twitter text-body link-hover"></i></a>
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
                                <a href="#" class="nav-link">About <i class="bi bi-caret-down-fill"></i></a>
                                <div class="dropdown-menu m-0 bg-secondary rounded-0" style="height: 130px; width: 60px; font-size: 15px">
                                    <a href="./about.php" class="dropdown-item">The Company</a>
                                    <a href="./Personnel.php" class="dropdown-item">Personnel</a>
                                    <a href="./gallery.php" class="dropdown-item">Gallery</a>

                                </div>
                            </div>
                            <a href="./view_cart.php" class="nav-item nav-link active"><i class="bi bi-cart4 cart-hover"></i></a>

                        </div>
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
                    </div>
                </nav>
            </div>
        </div>
    </section>
    <!-- Navbar end -->



<!-- Main Post Section Start -->

<div class="container-lg my-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <h5 class="card-title" style="margin-bottom: 50px;">Shopping Cart</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 bg-white">
                                <thead class="thead-mod">
                                    <tr>
                                        <th>Actions</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Personalization</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $grandTotal = 0; // Initialize grand total
                                    while ($row = $result->fetch_assoc()) {
                                        $total = $row['price'] * $row['quantity'];
                                        $grandTotal += $total;
                                        echo "<tr data-p_id='" . $row['p_id'] . "' data-available-stock='" . $row['available_stock'] . "' data-price='" . $row['price'] . "' data-cart_id='" . $row['cart_id'] . "'>";
                                        echo "<td>";
                                        echo "<button type='button' class='btn btn-danger btn-sm btn-delete' data-p_id='" . $row['p_id'] . "' data-cart_id='" . $row['cart_id'] . "'><i class='fas fa-trash'></i></button>";
                                        echo "</td>";
                                        echo "<td><div class='d-flex align-items-center'><img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' alt='' style='width: 50px; height: 50px; margin-right: 10px;'><div class='ms-3'><p class='fw-bold mb-1'>" . htmlspecialchars($row['name']) . "</p></div></div></td>";
                                        echo "<td>" . number_format($row['price'], 2) . "</td>";
                                        echo "<td><input type='number' class='form-control form-control-sm quantity-input' value='" . $row['quantity'] . "'></td>";
                                        echo "<td><input type='text' class='form-control form-control-sm personalization-input' value='" . htmlspecialchars($row['personalization']) . "'></td>";
                                        echo "<td class='total-cell'>&#8369; " . number_format($total, 2) . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="align-left"></td>
                                        <td class="align-right"><strong class="GT">Grand Total:</strong> <strong class='grand-total'>&#8369; <?php echo number_format($grandTotal, 2); ?></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="pradak.php" class="continue-btn"> <i class="bi bi-bag-plus-fill"></i> Continue Shopping</a>
                        <div class="d-flex justify-content-center">
                            <a href="checkout.php" class="checkout-btn"><i class="bi bi-cart-check-fill"></i> Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
 
<!-- Footer start -->

<footer class="bg-light text-center text-lg-start">
    <!-- Grid container -->
    <div class="container p-4">
      <!--Grid row-->
      <div class="row">
        <!--Grid column-->
        <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
          <p class="text-uppercase  fw-bold"><span class=" fw-bold" style="color: #f795bd;"> PBJams </span>Personalised by Jams</p>
          <p>
            Looking for unique and personalized gifts that will make a lasting impression? Look no further than Personalised by Jams! Personalised by Jams is a small business that specializes in creating custom gifts that are perfect for any occasion
          </p>
        </div>
        <!--Grid column-->
  
        <!--Grid column-->
        <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
          <p class="text-uppercase fw-bold" style="color: #f795bd;">LINKS</p>
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
          <p class="text-uppercase fw-bold" style="color: #f795bd;">STAY CONNECTED</p>
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

          </ul>
        </div>
        <!--Grid column-->
      </div>
      <!--Grid row-->
    </div>
    <!-- Grid container -->
  </footer>

   
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    function updateCart(cartId, productId, quantity, personalization) {
        $.ajax({
            type: 'POST',
            url: 'update_cart.php',
            data: {
                action: 'update',
                cart_id: cartId,
                p_id: productId,
                quantity: quantity,
                personalization: personalization
            },
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                alert('An error occurred while updating the cart.');
            }
        });
    }

    function calculateRowTotal(row) {
        var price = parseFloat(row.data('price'));
        var quantity = parseInt(row.find('.quantity-input').val());
        return price * quantity;
    }

    function updateGrandTotal() {
        var grandTotal = 0;
        $('.total-cell').each(function() {
            grandTotal += parseFloat($(this).data('total'));
        });
        $('.grand-total').text('₱ ' + grandTotal.toFixed(2));
    }

    $('.quantity-input').on('input', function() {
        var row = $(this).closest('tr');
        var productId = row.data('p_id');
        var cartId = row.data('cart_id');
        var newQuantity = parseInt($(this).val());
        var availableStock = parseInt(row.data('available-stock'));

        if (newQuantity > availableStock) {
            alert('Quantity exceeds available stock!');
            $(this).val(availableStock);
            newQuantity = availableStock;
        }

        var newTotal = calculateRowTotal(row);
        row.find('.total-cell').text('₱ ' + newTotal.toFixed(2)).data('total', newTotal);

        updateCart(cartId, productId, newQuantity, row.find('.personalization-input').val());
        updateGrandTotal();
    });

    $('.personalization-input').on('input', function() {
        var row = $(this).closest('tr');
        var productId = row.data('p_id');
        var cartId = row.data('cart_id');
        var newPersonalization = $(this).val();
        var newQuantity = parseInt(row.find('.quantity-input').val());

        updateCart(cartId, productId, newQuantity, newPersonalization);
    });

    $('.btn-delete').click(function() {
        var productId = $(this).data('p_id');
        var cartId = $(this).data('cart_id');
        var row = $(this).closest('tr');

        $.ajax({
            type: 'POST',
            url: 'update_cart.php',
            data: {
                action: 'remove',
                p_id: productId,
                cart_id: cartId
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    row.remove();
                    updateGrandTotal();
                } else {
                    alert('An error occurred while removing the item.');
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while removing the item.');
            }
        });
    });

    $('#checkoutBtn').click(function() {
        window.location.href = 'checkout.php';
    });

    $('.total-cell').each(function() {
        var row = $(this).closest('tr');
        var total = calculateRowTotal(row);
        $(this).text('₱ ' + total.toFixed(2)).data('total', total);
    });

    updateGrandTotal();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>