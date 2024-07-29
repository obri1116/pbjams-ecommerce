
<?php
    
    session_start();
    date_default_timezone_set('Asia/Manila');
    // Check if the user is logged in

    // Check if the user is logged in
    $username = '';


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $conn = mysqli_connect('localhost', 'root', '', 'client_db');
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Retrieve data from form
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];
        $date = date('Y/m/d h:i:sa'); // Current date
        $approved = 1; // Default value for approved  

        // Assuming you have the user's ID stored in a session variable or obtained from somewhere
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username']; // Adjust this according to your session handling

            if (!empty($username)) 
            {
                $sql = "SELECT * FROM clients WHERE username='$username'";      
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $id = $row['id'];

                    $sql = "INSERT INTO reviews (id, rating, comment, date, approved) VALUES ('$id', '$rating', '$comment', '$date', '$approved')";
                    if ($conn->query($sql) === TRUE) {
                    //lagay niyo dito ano ddisplay pag nagdisplay successfully  
                        echo "Review submitted successfully";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }

                    $conn->close();
                    header('Location: review.php');
                }
            }
        }
    }
    
    ?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/CIP-1101 FINAL PROJECT-20240522T032656Z-001/CIP-1101 FINAL PROJECT/nav.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./css/review.css">
    <!-- <link rel="stylesheet" href="./css/nav.css"> -->
    <!-- <link rel="stylesheet" href="./css/styles.css"> -->
    <title>REVIEWS</title>

    <script>

        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = parseInt(star.getAttribute('data-value'));
                    document.getElementById('rating').value = value;

                    // Update star colors based on user selection
                    stars.forEach(s => {
                        if (parseInt(s.getAttribute('data-value')) <= value) {
                            s.classList.add('text-warning');
                        } else {
                            s.classList.remove('text-warning');
                        }
                    });
                });
            });

            // Sorting functionality
            document.getElementById('filter').addEventListener('change', function() {
                const filterValue = this.value;
                const reviewsContainer = document.getElementById('reviews');
                const reviews = Array.from(reviewsContainer.getElementsByClassName('review-card'));

                // Sort reviews based on filter value
                reviews.sort((a, b) => {
                    const ratingA = parseInt(a.getAttribute('data-rating'));
                    const ratingB = parseInt(b.getAttribute('data-rating'));
                    const dateA = new Date(a.getAttribute('data-date'));
                    const dateB = new Date(b.getAttribute('data-date'));

                    if (filterValue === 'highest') {
                        if (ratingB !== ratingA) {
                            return ratingB - ratingA;
                        } else {
                            return dateB - dateA;
                        }
                    } else if (filterValue === 'lowest') {
                        if (ratingA !== ratingB) {
                            return ratingA - ratingB;
                        } else {
                            return dateB - dateA;
                        }
                    } else if (filterValue === 'latest') {
                        return dateB - dateA;
                    }
                });

                // Clear current reviews and append sorted reviews
                reviewsContainer.innerHTML = '';
                reviews.forEach(review => reviewsContainer.appendChild(review));
            });
        });
    </script>
<style>
    <?php include "review.css" ?>
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
        .dropdown-menu {
            max-width: 300px;
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
                            <a href="./review.php" class="nav-item nav-link active">Reviews</a>
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

        <div class="container-lg my-5">
            <div class="row">
                <!-- SEND A REVIEWS -->
                <div class="col-md-7">
                    <div class="card">
                        <h6 class="card-header fw-bold">SEND A REVIEW</h6>
                        <div class="card-body">
                                <div class="mb-3">
                                        <h3 style="font-weight: bold; color:#f874a0;">Hello, PBJammers!</h3>
                                </div>
                                <div class="mb-3">
                                    <p>We hope you’re enjoying your purchase! Your satisfaction is our top priority, and we’d love to hear your thoughts. By sharing your feedback, you help us understand what we’re doing right and where we can improve. Plus, your insights help other shoppers make informed decisions.</p>
                                    <p>Please take a moment to fill out our quick review form. Your feedback is incredibly valuable to us!</p>
                                    <hr>
                                </div>
                            <form id="reviewForm" name="reviewForm" method="POST" action="">
                                <div class="mb-3">
                                    <label for="rating" class="form-label" style="font-weight: bold;"> <i class="bi bi-list-stars"></i> Rate us</label>
                                    <div class="stars">
                                        <i class="star fas fa-star" data-value="1"></i>
                                        <i class="star fas fa-star" data-value="2"></i>
                                        <i class="star fas fa-star" data-value="3"></i>
                                        <i class="star fas fa-star" data-value="4"></i>
                                        <i class="star fas fa-star" data-value="5"></i>
                                    </div>
                                    <input type="hidden" name="rating" id="rating" value="0" required>
                                </div>
                                <div class="mb-3">
                                    <label for="comment" class="form-label"><i class="bi bi-chat-dots-fill"></i> Comment your feedback about our service</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="3" 
                                        <?php if (!isset($_SESSION['username'])) { echo 'readonly'; } ?> required>
                                        <?php if (!isset($_SESSION['username'])) { echo 'PLEASE LOG IN FIRST';} ?>
                                    </textarea>
                                </div>
                                <button type="submit" class="btn-sub" id="submit-review" 
                                    <?php if (!isset($_SESSION['username'])) { echo 'disabled'; } ?>>Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card">
                        <!-- REVIEWS CARD -->
                        <h4 class="card-header" style="font-size:25px; border:none; background-color:#f874a0;color:white; font-weight:bold; text-align:center">PBJ CUSTOMER REVIEWS</h4>
                        <div class="card-body text-center-custom" style="height: 600px; overflow-y: auto;">
                            <?php
                            date_default_timezone_set('Asia/Manila'); // Set timezone to Philippines (GMT+8)
                            // Connect to database
                            $conn = mysqli_connect('localhost', 'root', '', 'client_db');
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            // Fetch total number of reviews
                            $total_reviews_sql = "SELECT COUNT(*) AS total_reviews FROM reviews WHERE approved = 1";
                            $total_reviews_result = $conn->query($total_reviews_sql);
                            $total_reviews_row = $total_reviews_result->fetch_assoc();
                            $total_reviews = $total_reviews_row['total_reviews'];

                            // Fetch average rating
                            $average_rating_sql = "SELECT AVG(rating) AS avg_rating FROM reviews WHERE approved = 1";
                            $average_rating_result = $conn->query($average_rating_sql);
                            $average_rating_row = $average_rating_result->fetch_assoc();
                            $avg_rating = round($average_rating_row['avg_rating'], 1); // Round to one decimal place

                            // Display overall rating and total reviews
                            echo "<h5>Overall Rating / {$total_reviews} Total Reviews:</h5>";
                            echo "<div class='stars-reviews mb-3'>";
                            for ($i = 0; $i < 5; $i++) {
                                if ($i < $avg_rating) {
                                    echo "<i class='fas fa-star text-warning'></i>";
                                } else {
                                    echo "<i class='far fa-star text-warning'></i>";
                                }
                            }
                            echo "</div>";

                            // Fetch limited number of reviews along with usernames from the clients table
                            $reviews_limit = 10; // Limit number of reviews displayed
                            $reviews_sql = "SELECT reviews.rating, reviews.comment, reviews.date, clients.username
                                            FROM reviews
                                            JOIN clients ON reviews.id = clients.id
                                            WHERE reviews.approved = 1
                                            ORDER BY reviews.date DESC
                                            LIMIT $reviews_limit"; // Limiting to $reviews_limit
                            $reviews_result = $conn->query($reviews_sql);

                            if ($reviews_result->num_rows > 0) {
                                while ($row = $reviews_result->fetch_assoc()) {
                                    // Format the date
                                    $formatted_date = date("F j, Y h:iA", strtotime($row['date']));
                                    $rating = $row['rating'];
                                    $comment = $row['comment'];
                                    $username = $row['username'];

                                    echo "<div class='card col-md-10 mb-3 review-card' data-rating='$rating'>
                                    
                                            <div class='card-body'>
                                            <h4 class='card-title username'> <i class='bi bi-person-fill'></i> {$username}</small></h5>
                                                <div class='stars mb-2'>";
                                                    for ($i = 0; $i < 5; $i++) {
                                                        if ($i < $rating) {
                                                            echo "<i class='fas fa-star text-warning'></i>";
                                                        } else {
                                                            echo "<i class='far fa-star text-warning'></i>";
                                                        }
                                                    }
                                    echo            "</div>
                                                <div>
                                                <h4 class='card-title'><small class='text-muted compact-date'><i class='bi bi-calendar-check'></i> {$formatted_date}</small></h4>
                                                </div>
                                                <p class='card-text'><i class='bi bi-chat-dots-fill'></i> {$comment}</p>
                                            </div>
                                          </div>";

                                }
                            } else {
                                echo "<p>No reviews yet.</p>";
                            }

                            $conn->close();
                            ?>
                        </div>
                    </div>
                </div>
                
                
                
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
<script>
  document.getElementById('reviewForm').reset();
</script>
<script src="resource/js/jquery-1.11.0.js"></script>
<script src="resources/js/bootstrap.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $('dropdown-toggle').dropdown()
});
</script>
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
if (!isset($_SESSION['username']))  {
    echo "showModal('Please login or create an account.');";
}
?>

</script>
</body>
</html>