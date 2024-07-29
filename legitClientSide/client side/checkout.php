<!DOCTYPE html>
<html lang="en">
<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['username']) && isset($_SESSION['first_name'])) {
    // User is logged in
} else {
    echo "You are not logged in.";
    exit(); // Stop further execution if the user is not logged in
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "client_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user information
$username = $_SESSION['username'];
$sql_client = "SELECT id, first_name, last_name, address, phone 
               FROM clients 
               WHERE username = ?";
$stmt = $conn->prepare($sql_client);
$stmt->bind_param("s", $username);
$stmt->execute();
$result_client = $stmt->get_result();
$client = $result_client->fetch_assoc();

if (!$client) {
    echo "Client not found.";
    exit();
}

$client_id = $client['id'];
$client_full_name = $client['first_name'] . ' ' . $client['last_name'];

// Get cart items and product details for the client
$sql_cart = "SELECT c.p_id, c.quantity, c.personalization, p.name, p.price 
             FROM cart c 
             JOIN products p ON c.p_id = p.p_id 
             WHERE c.u_id = ?";
$stmt = $conn->prepare($sql_cart);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result_cart = $stmt->get_result();

$cart_items = [];
while ($row = $result_cart->fetch_assoc()) {
    $cart_items[] = [
        'p_id' => $row['p_id'],
        'quantity' => $row['quantity'],
        'personalization' => $row['personalization'],
        'name' => $row['name'],
        'price' => $row['price'],
    ];
}

// Calculate the total cost
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send'])) {
    // Capture form data
    $name = $_POST['name'];
    $contact = $_POST['number'];
    $address = $_POST['address'];
    $payment_method = $_POST['payment_method'];
    $total_amount = $total; // Total calculated earlier

    // Insert into orders table
    $sql_order = "INSERT INTO orders (u_id, name, contact, status, total_amount, shipping_address, payment_method) VALUES (?, ?, ?, 'Pending', ?, ?, ?)";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("ississ", $client_id, $name, $contact, $total_amount, $address, $payment_method);
    if ($stmt_order->execute()) {
        $order_id = $stmt_order->insert_id; // Get the new order ID

        // Insert into order_items table
        foreach ($cart_items as $item) {
            $sql_order_item = "INSERT INTO order_items (o_id, p_id, personalization, quantity, price, total) VALUES (?, ?, ?, ?, ?, ?)";
            $total_item_price = $item['price'] * $item['quantity'];
            $stmt_order_item = $conn->prepare($sql_order_item);
            $stmt_order_item->bind_param("iisidd", $order_id, $item['p_id'], $item['personalization'], $item['quantity'], $item['price'], $total_item_price);
            $stmt_order_item->execute();

            // Update product stock
            $sql_update_stock = "UPDATE products SET quantity = quantity - ? WHERE p_id = ?";
            $stmt_update_stock = $conn->prepare($sql_update_stock);
            $stmt_update_stock->bind_param("ii", $item['quantity'], $item['p_id']);
            $stmt_update_stock->execute()
;        }
        $sql_delete_cart = "DELETE FROM cart WHERE u_id = ?";
        $stmtdel = $conn->prepare($sql_delete_cart);
        $stmtdel->bind_param("i", $client_id);
        if ($stmtdel->execute()) {
            echo "Cart cleared successfully.";
        } else {
            echo "Error clearing cart: " . $stmtdel->error;
        }

        $stmtdel->close();
        echo "<script>alert('Order placed successfully!'); window.location.href = './index.php';</script>";
    } else {
        echo "Error placing order: " . $stmt_order->error;
    }
}
// Close the database connection
$conn->close();
?>


<!-- CHATBOT -->
<!-- <script  type="text/javascript">window.$crisp=[];window.CRISP_WEBSITE_ID="5f7df951-53df-4772-bf0a-74b6632c1c61";(function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();</script>   -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/CIP-1101 FINAL PROJECT-20240522T032656Z-001/CIP-1101 FINAL PROJECT/nav.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
      <?php include "./css/checkout.css" ?>
    </style>
    <title>CHECK OUT</title>

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
                            <a href="./main.php" class="nav-item nav-link active">Home</a>
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
<!-- START CHECK OUT FORM -->
<div class="container mt-5 mb-5">
  <div class="row">
    <div class="col-md-6">
      <div class="card" style="max-width: 1000px;">
        <h4 class="card-header fw-bold text-center" style="background: #f795bd; color: white;">Check Out form</h4>
        <div class="card-body">
          <form id="contact-form" action="" method="POST">
            <div class="form-group">
              <label class="Form-content fw-bold" for="name">Name <span>&#42;</span></label>
              <input class="form-control" type="text" id="name" name="name" value="<?php echo htmlspecialchars($client_full_name); ?>"><br>
            </div>

            <div class="form-group">
              <label class="form-content fw-bold" for="number">Contact Number <span>&#42;</span></label>
              <input class="form-control" type="text" id="number" name="number" value="<?php echo htmlspecialchars($client['phone']); ?>"><br>
            </div>

            <div class="form-group">
              <label class="form-content fw-bold" for="address">Shipping Address <span>&#42;</span></label>
              <input class="form-control" type="text" id="address" name="address" value="<?php echo htmlspecialchars($client['address']); ?>" required /><br>
            </div>

            <div class="form-group">
              <label class="form-content fw-bold">Payment Method <span>&#42;</span></label>
              <div class="payment-methods">
                <input type="radio" id="ccdd" name="payment_method" value="Credit/Debit card">
                <label class="method" for="ccdd">Credit/Debit card</label>

                <input type="radio" id="paypal" name="payment_method" value="Paypal">
                <label class="method" for="paypal">Paypal</label>

              </div>
            </div>
            <div class="text-center" style="padding: 10px;">
              <button type="submit" name="send" class="btn" style="background-color: #f795bd; color: white;">Place Order</button>
              <button type="button" name="exit" class="btn" style="background-color: none; color: black; margin-right:10px" onclick="cancelAction()">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- SUMMARY ORDERS -->
    <div class="col-md-6">
      <div class="scrollingDiv">
        <div class="card" style="max-width: 1000px;">
          <h5 class="card-header fw-bold text-center" style="color: black;"><i class="bi bi-receipt"></i> Summary Orders</h5>
          <div class="card-body">
            <p class="card-text">Total items: <?php echo count($cart_items); ?></p>
            <ul>
              <?php foreach ($cart_items as $item) { ?>
              <li>
                <?php echo htmlspecialchars($item['name']) . " - Quantity: " . htmlspecialchars($item['quantity']) . " - Price: $" . number_format($item['price'], 2); ?>
              </li>
              <?php } ?>
            </ul>
            <p class="card-text">Total amount: $<?php echo number_format($total, 2); ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modals and Scripts -->
<!-- Credit/Debit Card Modal -->
<div id="ccddModal" class="modal" style="display: none;">
  <div class="modal-content">
    <span class="close" data-modal="ccddModal">&times;</span>
    <h3 style="text-align: center; background-color: #f874a0; color:#faecf5; font-weight:bold; padding:20px"><i class="bi bi-credit-card-fill"></i> CREDIT CARD DETAILS</h3>
    <form id="ccdd-form" action="" method="POST">
      <div class="form-group">
        <label class="form-content fw-bold" for="cc_name">Card Holder Name <span>&#42;</span></label>
        <input class="form-control" type="text" id="cc_name" name="cc_name" required><br>
      </div>

      <div class="form-group">
        <label class="form-content fw-bold" for="cc_number">Account number <span>&#42;</span></label>
        <input class="form-control" type="text" id="cc_number" name="cc_number" required><br>
      </div>

      <!-- Container for Exp. date and CVC -->
      <div class="form-row">
        <div class="form-group">
          <label class="form-content fw-bold" for="exp_date">Exp. date <span>&#42;</span></label>
          <input class="form-control" type="text" id="exp_date" name="exp_date" placeholder="MM/YY" required />
        </div>
        <div class="form-group">
          <label class="form-content fw-bold" for="cvc">CVC <span>&#42;</span></label>
          <input class="form-control" type="text" id="cvc" name="cvc" placeholder="CVC" required />
        </div>
      </div>

      <div class="text-center" style="padding: 10px;">
        <button type="submit" name="send" class="btn" style="background-color: #f795bd; color: white;">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- Paypal Modal -->
<div id="paypalModal" class="modal" style="display: none;">
  <div class="modal-content">
    <span class="close" data-modal="paypalModal">&times;</span>
    <h3 style="text-align: center; background-color: #0079C1; color:#faecf5; font-weight:bold; padding:20px;"><i class="bi bi-paypal"></i> PayPal</h3>
    <form id="paypal-form" action="" method="POST">
      <div class="form-group">
        <label class="form-content fw-bold" for="paypal_email">PayPal-Account Email Address <span>&#42;</span></label>
        <input class="form-control" type="email" id="paypal_email" name="paypal_email" required><br>
      </div>

      <div class="form-group">
        <label class="form-content fw-bold" for="paypal_password">Password <span>&#42;</span></label>
        <input class="form-control" type="password" id="paypal_password" name="paypal_password" required><br>
      </div>
      <div class="text-center" style="padding: 10px;">
        <button type="submit" name="send" class="btn" style="background-color: #f795bd; color: white;">Save</button>
      </div>
    </form>
  </div>
</div>


<script src="script.js"></script>


<!-- <div class="modal" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successModalLabel">Success</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Your message has been sent successfully!
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div> -->

  <!-- END CONTACT US -->


<!-- Main Post Section End -->



 <!-- Footer start -->
 <footer class="bg-light text-center text-lg-start">
        <div class="container p-4">
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <h5 class="text-uppercase  fw-bold"><span class=" fw-bold" style="color: #f795bd;"> PBJams </span>Personalised by Jams</h5>
                    <p>
                        Looking for unique and personalized gifts that will make a lasting impression? Look no further than Personalised by Jams! Personalised by Jams is a small business that specializes in creating custom gifts that are perfect for any occasion
                    </p>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase fw-bold" style="color: #f795bd;">LINKS</h5>
                    <ul class="list-unstyled mb-0">
                        <li><a href="../index.html" class="footer-menu" >Home</a></li>
                        <li><a href="./about.html" class="footer-menu">About</a></li>
                        <li><a href="./store-menu.html" class="footer-menu">Products & Services</a></li>
                        <li><a href="./contact.html" class="footer-menu">Contact us</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase fw-bold" style="color: #f795bd;">STAY CONNECTED</h5>
                    <ul class="list-unstyled mb-0" >
                        <li><i class="bi bi-facebook icon-color"></i><a href="#!" class="footer-menu"> Facebook</a></li>
                        <li><i class="bi bi-instagram icon-color"></i><a href="#!" class="footer-menu"> Instagram</a></li>
                        <li><i class="bi bi-linkedin icon-color"></i><a href="#!" class="footer-menu"> Linkedin</a></li>
                        <li><i class="bi bi-twitter-x icon-color"></i><a href="#!" class="footer-menu"> Twitter</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer end -->


  
    <!-- script src start-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

<script src="../script.js"></script>
    <!-- script src end -->
<script>
    function cancelAction() {
        if (window.confirm('Are you sure you want to cancel checking out?')) {
            window.location.href = './pradak.php';
        }
    }

    document.addEventListener('DOMContentLoaded', (event) => {
        const modals = {
            ccdd: document.getElementById("ccddModal"),
            paypal: document.getElementById("paypalModal")
        };

        const closeButtons = document.querySelectorAll('.close');

        document.querySelectorAll('input[name="payment_method"]').forEach((input) => {
            input.addEventListener('change', (event) => {
                if (event.target.value === "Credit/Debit card") {
                    modals.ccdd.style.display = "block";
                } else if (event.target.value === "Paypal") {
                    modals.paypal.style.display = "block";
                }
            });
        });

        // When the user clicks on <span> (x), close the modal
        closeButtons.forEach((button) => {
            button.onclick = function() {
                const modalId = this.getAttribute('data-modal');
                document.getElementById(modalId).style.display = "none";
            }
        });

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = "none";
            }
        }

        // Function to hide the modal
        function hideModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Add event listener to the credit/debit card form to handle submission
        document.getElementById('ccdd-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Hide the modal
            hideModal('ccddModal');

            // Optionally, you can add an AJAX request here to send the data to the server
            // without refreshing the page.
        });

        // Add event listener to the PayPal form to handle submission
        document.getElementById('paypal-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Hide the modal
            hideModal('paypalModal');

            // Optionally, you can add an AJAX request here to send the data to the server
            // without refreshing the page.
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
    const ccNameInput = document.getElementById('cc_name');
    const ccNumberInput = document.getElementById('cc_number');
    const expDateInput = document.getElementById('exp_date');
    const cvcInput = document.getElementById('cvc');
    const paypalEmailInput = document.getElementById('paypal_email');
    const paypalPasswordInput = document.getElementById('paypal_password');

    ccNameInput.addEventListener('input', function () {
        ccNameInput.value = ccNameInput.value.replace(/[^A-Za-z\s]/g, '');
    });

    ccNumberInput.addEventListener('input', function () {
        let input = ccNumberInput.value.replace(/\D/g, ''); // Remove all non-digit characters
        input = input.substring(0, 16); // Limit input to 16 digits
        const formattedInput = input.match(/.{1,4}/g)?.join(' ') || ''; // Add space after every 4 digits
        ccNumberInput.value = formattedInput;
    });

    expDateInput.addEventListener('input', function () {
        expDateInput.value = expDateInput.value.replace(/[^0-9\/]/g, '');
        if (expDateInput.value.length === 2 && !expDateInput.value.includes('/')) {
            expDateInput.value += '/';
        }
        if (expDateInput.value.length > 5) {
            expDateInput.value = expDateInput.value.substring(0, 5);
        }
    });

    cvcInput.addEventListener('input', function () {
        cvcInput.value = cvcInput.value.replace(/\D/g, '').substring(0, 3);
    });

    paypalEmailInput.addEventListener('input', function () {
        // Allow only valid characters for email input
        paypalEmailInput.value = paypalEmailInput.value.replace(/[^a-zA-Z0-9@._-]/g, '');
    });

    paypalPasswordInput.addEventListener('input', function () {
        // Allow only valid characters for password input
        paypalPasswordInput.value = paypalPasswordInput.value.replace(/[^a-zA-Z0-9!@#$%^&*()_+=-]/g, '');
    });
});



</script>
</body>
  </html>