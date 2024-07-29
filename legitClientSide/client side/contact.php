<!DOCTYPE html>
<html lang="en">
<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Initialize variables
$username = '';
$contact_number = '';
$email = '';
$name = '';

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = mysqli_connect('localhost', 'root', '', 'client_db');

        if (!$conn) {
            echo "<script>showModal('Connection failed: " . mysqli_connect_error() . "');</script>";
            die();
        }

        $username = mysqli_real_escape_string($conn, $username);

        if (!empty($username)) {
            $sql = "SELECT * FROM clients WHERE username='$username'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $email = $row['email'];
                $contact_number = $row['phone'];
                $name = $row['first_name'] . ' ' .  $row['last_name'];
            }

            mysqli_close($conn);
        } else {
            // Handle the case where username is empty
            $contact_number = 'none';
        }
    }
} else {
    $username = 'Anonymous';
    $contact_number = 'Anonymous';
}

if (isset($_POST["send"])) {
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);

    try {
        // Send email to the user
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'justinpritzdcorpuz@gmail.com';
        $mail->Password = 'kbrbewrdfsygdmfu';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('justinpritzdcorpuz@gmail.com');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = "<p>Dear <strong>" .  htmlspecialchars($name)  . "</strong>,</p>" .
                      "<p>Thank you for reaching out to us!</p>" .
                      "<p><strong>Your Message:</strong><br>" .
                      "<div style='margin-left: 20px; padding: 10px; border-left: 2px solid #ccc;'>" . "Subject: <strong>" . htmlspecialchars($subject) . "</strong>.</div>" .
                      "<div style='margin-left: 20px; padding: 10px; border-left: 2px solid #ccc;'>" . nl2br(htmlspecialchars($message)) . "</div>" .
                      "<p>Our team will get back to you as soon as possible. Your inquiry is important to us, and we strive to respond to all queries within 24-48 hours.</p>" .
                      "<p>In the meantime, if you have any additional information or questions, please feel free to reply to this email.</p>" .
                      "<p>Thank you for your patience and understanding.</p>" .
                      "<p>Best regards,</p>" .
                      "<p><strong>Personalised By Jams</strong><br>" .
                      "+91 987 654 3210<br>" .
                      "Glenroy, Melbourne Australia<br>" .
                      "<a href='https://www.yourwebsite.com'>Visit Our Website Here</a><br>" .  
                      "<strong>THIS IS AN AUTOMATED MESSAGE</strong><br></p>";

        $mail->send();

        // Reset the mail object to send the second email
        $mail->clearAddresses();
        $mail->clearAttachments();

        // Send email to the business
        $mail->addAddress('justinpritzdcorpuz@gmail.com'); // replace with the business email
        $mail->Subject = "New Inquiry from " . htmlspecialchars($name);
        $mail->Body = "<p>You have received a new message from <strong>" . htmlspecialchars($name) . "</strong>.</p>" .
                      "<p><strong>Contact Details:</strong></p>" .
                      "<p>Email: " . htmlspecialchars($email) . "<br>" .
                      "Phone: " . htmlspecialchars($contact_number) . "</p>" .
                      "<p><strong>Message:</strong><br>" .
                      "<div style='margin-left: 20px; padding: 10px; border-left: 2px solid #ccc;'>" . nl2br(htmlspecialchars($message)) . "</div>";

        $mail->send();

        echo "<script>showModal('Message has been sent');</script>";
    } catch (Exception $e) {
        echo "<script>showModal('Message could not be sent. Mailer Error: " . $mail->ErrorInfo . "');</script>";
    }
}
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
    <link rel="stylesheet" href="./css/nav.css">

    <title>Contact us</title>

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
          .card {
            padding: 50px;
            border-color: transparent;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            position: relative;
            float: center;
            background-color: rgba(255, 255, 254, 0.2);
          }
        /* Modal styles */
.modal {
  display: none; 
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%; 
  height: 100%; 
  overflow: auto; 
  background-color: rgb(0,0,0); 
  background-color: rgba(0,0,0,0.4);
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: 15% auto;
  border: 1px solid #888;
  width: auto; 
  max-width: 30%;
  max-height: 100%;
  height: auto;

}

/* Modal Header */
.modal-header {
  align-items: center; 
  justify-content: space-between;
  background-color: #f874a0;
  color: #f8f9fa;
  font-weight: bold;
  height: 15px;
  padding: 20px;
  display: flex;
  align-items: center;

}
.modal-header p {
  margin: 0; /* Remove default margin for better alignment */
  font-size: 20px;
}

.modal-header .close {
  cursor: pointer; /* Change cursor to pointer to indicate it's clickable */
}
#modalMessage {
  flex-grow: 1; 
  font-size: 20px;
  text-align: center;
  color:#333;
}

/* Close Button */
.close {
  color: #f8f9fa;
  font-size: 25px;
  font-weight: bold;
  cursor: pointer;
  margin-top: 10px;
}

.close:hover,
.close:focus {
  color: #d63384;
  text-decoration: none;
  cursor: pointer;
}
    </style>


   <!-- The Modal -->
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
                            <a href="./contact.php" class="nav-item nav-link active">Contact us</a>
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
<!-- START CONTACT US -->

<div class="container-lg my-5">
  <div class="row">
    <div class="col-md-8">
      <div class="card">
        <h4 class="card-header fw-bold" style="text-align: center; background: #f795bd; color: white;">Send a message</h4>
        <div class="card-body">
          <form id="contact-form" action="" method="POST">
              <div class="mb-3">
                    <h3 style="font-weight: bold; color:#f874a0;">Hello, PBJammers!</h3>
              </div>
              
              <div class="mb-3" >
                  <We>If you have any questions or need assistance, don't hesitate to reach out to us! We appreciate your feedback. Your opinions help us improve our services! Our customer support team is here to help you with any concerns you may have.</p>

                    <hr>
              </div>
            <!-- <div class="form-group">
              <label class="font-weight-bold" for="name">Username:</label>
              <input class="form-control" type="text" id="name" name="name" value="<?php echo htmlspecialchars($username); ?>" />
            </div>

            <div class="form-group">
              <label class="font-weight-bold" for="number">Number:</label>
              <input class="form-control" type="text" id="number" name="number" value="<?php echo htmlspecialchars($contact_number); ?>" />
            </div> -->

            <div class="form-group">
              <label class="font-weight-bold" for="email" style="font-weight: bold;">Subject:</label>
              <input class="form-control" type="text" id="subject" name="subject" placeholder="Subject of your inquiry/message" 
                <?php if (!isset($_SESSION['username'])) { echo 'readonly value="Login in order to send a message."'; } ?> required /><br>
            </div>

            <div class="form-group">
              <label class="font-weight-bold" for="bio" style="font-weight: bold;">Message:</label>
              <textarea class="form-control" id="message" name="message" cols="30" rows="10" 
                <?php if (!isset($_SESSION['username'])) { echo 'readonly value="PLEASE LOG IN FIRST"'; } ?> 
                placeholder="Enter details of your message" required><?php if (!isset($_SESSION['username'])) { echo 'Login in order to send a message.'; } ?></textarea>
            </div>
            <div style="padding: 10px; margin-bottom: -60px;">
              <button type="submit" name="send" class="btn" style="background-color: #f795bd; color: white; margin-left: 630px;" 
                <?php if (!isset($_SESSION['username'])) { echo 'disabled'; } ?>>Submit</button>
            </div>

          </form>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-body rounded pt-0 m-0 p-1" style="background-color:#f795bd;">
          <ul class="list-unstyled rounded m-3 text-dark p-0" style="background-color: #fff;">
            <h4 class="fw-bold m-2 p-3" style="text-align: center; font-size:20px">Contact Information</h4>
            <li class="d-flex align-items-center mb-0">
              <i class="bi bi-geo-alt-fill m-3 p-0 pt-0 flex-shrink-0 icon-color" ></i>
              <span style="font-size: 15px;">Glenroy, Melbourne Australia</span>
            </li>
            <li class="d-flex align-items-center mb-0">
              <i class="bi bi-envelope-at-fill m-3 p-0 flex-shrink-0 icon-color"></i>
              <span style="font-size: 13px;">personalisedbyjams@yahoo.com</span>
            </li>
            <li class="d-flex align-items-center mb-0">
              <i class="bi bi-telephone-fill m-3 p-0 flex-shrink-0 icon-color"></i>
              <span>+91 987 654 3210</span>
            </li>
          </ul>
          <div class="mt-4">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3157.1080640211853!2d144.93948657367224!3d-37.693661088484774!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad644b4aec2547b%3A0x5045675218cd210!2sGlenroy%20VIC%203046%2C%20Australia!5e0!3m2!1sen!2sph!4v1716383925455!5m2!1sen!2sph" width="100%" height="300" style="border: 0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
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
</div>

  <!-- END CONTACT US -->


<!-- Main Post Section End -->



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
<script>
function showModal(message) {
    document.getElementById('modalMessage').innerText = message;
    var modal = document.getElementById("myModal");
    var span = document.getElementsByClassName("close")[0];

    modal.style.display = "block";

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}

<?php
if (!isset($_SESSION['username']) || !isset($_SESSION['first_name'])) {
    echo "showModal('Please login or create an account.');";
}
?>
</script>

    <!-- script src end -->
</body>
  

</html>