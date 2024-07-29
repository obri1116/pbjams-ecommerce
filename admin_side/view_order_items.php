<?php
session_start();

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'client_db';

// Db Connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check session for AdmOrEmp
if (!isset($_SESSION['AdmOrEmp'])) {
    // Redirect if no AdmOrEmp is set
    header("Location: admin_login.php");
    exit();
}

$adminstate = intval($_SESSION['AdmOrEmp']);
$admin_name = '';

if ($adminstate == 1) {
    $adminstate = "Admin";
} elseif ($adminstate == 0) {
    $adminstate = "Employee";
}

$admin_id = $_SESSION['admin_id'] ?? null;

// Query to get admin details
if ($admin_id) {
    $stmt = $conn->prepare("SELECT first_name, last_name, username FROM admins WHERE admin_id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result_admin = $stmt->get_result();
    
    if ($result_admin->num_rows > 0) {
        $admin = $result_admin->fetch_assoc();
        $admin_name = htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']);
        $admin_user = htmlspecialchars($admin['username']);
    }
    $stmt->close();
}

// Get o_id from the URL
$o_id = isset($_GET['o_id']) ? intval($_GET['o_id']) : 0;

// Query to get order items for the specific o_id
$sqlpull = "SELECT o.o_id, o.name AS client_name, o.contact, o.date, o.total_amount, o.shipping_address, o.payment_method, o.status, oi.personalization,
                   oi.quantity, p.name AS product_name, p.price, (oi.quantity * p.price) AS total_price, i.image
            FROM orders o
            INNER JOIN order_items oi ON o.o_id = oi.o_id
            INNER JOIN products p ON oi.p_id = p.p_id
            LEFT JOIN (SELECT p_id, MIN(image) AS image FROM images GROUP BY p_id) i ON p.p_id = i.p_id
            WHERE o.o_id = ?";

$stmt = $conn->prepare($sqlpull);
$stmt->bind_param("i", $o_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View product items</title>
    <style>
      <?php include "./css/view_orders_items.css" ?>
    </style>
    <style>
        .img-sizing {
    width: 50px;  /* Set the desired width */
    height: auto; /* Maintain aspect ratio */
    max-height: 50px; /* Set the maximum height */
    object-fit: cover; /* Ensures the image covers the given dimensions */
    }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="manage_order.css">
</head>
<body id="body-pd" style="background-image:url('./assets/bg.png');">
    <header class="header" id="header">
        <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
        <div class="header_logo"> <img src ="./assets/header.jpg"> </div>
        <div class="dropdown">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-circle"> <?php echo $adminstate; ?> <?php echo $admin_name; ?></i></a>
                <ul class="dropdown-menu">
                    <h6><?php echo $admin_name; ?></h6>
                    <p>@<?php echo $admin_user; ?></p>
                    <hr>
                    <li><a class="dropdown-item" href="admin_edit_profile.php"><i class="bi bi-pencil-square"></i> Edit profile</a></li>
                    <li><a class="dropdown-item" href="admin_logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
                </ul>
            </div>
    </header>
    
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div class="nav_con">
                <div class="nav_list">
                    <a href="#" class="nav_link">
                       <br>
                    </a>
                    <a href="#" class="nav_link">
                        <i class='bx bx-grid-alt nav_icon'></i>
                        <span class="nav_name"> Dashboard</span>
                    </a>
                    <a href="./insert_product.php" class="nav_link ">
                        <i class="bi bi-plus"></i>
                        <span class="nav_name"> Products</span>
                    </a>
                    <a href="./manage_order.php" class="nav_link  active">
                        <i class="bi bi-bag-heart"></i>
                        <span class="nav_name"> Orders</span>
                    </a>
                    <?php if ($adminstate === "Admin"): ?>
                        <a href="manage_employees.php" class="nav_link">
                            <i class="bi bi-people"></i>
                            <span class="nav_name">Employees</span>
                        </a>
                        <a href="manage_clients.php" class="nav_link">
                            <i class='bx bx-user nav_icon'></i>
                            <span class="nav_name"> Users</span>
                        </a>
                    <?php endif; ?>
                    <a href="./manage_reviews.php" class="nav_link">
                    <i class="bi bi-chat-left-quote"></i>
                        <span class="nav_name"> Reviews</span>
                    <a href="#" class="nav_link">
                        <i class="bi bi-receipt"></i>
                        <span class="nav_name"> Sales</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>
    
    <!--Container Main start-->
    <div class="content">
        <h2><i class="bi bi-bag-heart"></i> VIEW PRODUCT ITEMS</h2>
        <!-- Search form remains unchanged -->

        <div class="table_container">
            <div class="table-wrapper col-lg-12 col-sm-5">
                <table>
                    <thead>
                        <tr>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Personalization</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['image']) . "' class='img-sizing' alt='Product Image'></td>";
                                echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['personalization']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
                                echo "<td>₱" . number_format($row['price'], 2) . "</td>";
                                echo "<td>₱" . number_format($row['total_price'], 2) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No products found for this order.</td></tr>";
                        }
                        $stmt->close();
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class ="return-btn"><a href ="manage_order.php">MANAGE ORDER</a></div>
    
    <!--Container Main end-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            const showNavbar = (toggleId, navId, bodyId, headerId) =>{
                const toggle = document.getElementById(toggleId),
                      nav = document.getElementById(navId),
                      bodypd = document.getElementById(bodyId),
                      headerpd = document.getElementById(headerId)

                // Validate that all variables exist
                if(toggle && nav && bodypd && headerpd){
                    toggle.addEventListener('click', ()=>{
                        // show navbar
                        nav.classList.toggle('show')
                        // change icon
                        toggle.classList.toggle('bx-x')
                        // add padding to body
                        bodypd.classList.toggle('body-pd')
                        // add padding to header
                        headerpd.classList.toggle('body-pd')
                    })
                }
            }

            showNavbar('header-toggle','nav-bar','body-pd','header')

            // Link active
            const linkColor = document.querySelectorAll('.nav_link')

            function colorLink(){
                if(linkColor){
                    linkColor.forEach(l=> l.classList.remove('active'))
                    this.classList.add('active')
                }
            }
            linkColor.forEach(l=> l.addEventListener('click', colorLink))
        });

        // Add active class to the sidebar nav link
        $(document).ready(function () {
            $('.nav_link').click(function () {
                $('.nav_link').removeClass('active');
                $(this).addClass('active');
            });
        });

        // Handle order status change
        $(document).on('change', '.order-status', function() {
            var order_id = $(this).closest('tr').data('order-id');
            var status = $(this).val();

            $.ajax({
                url: 'update_order_status.php',
                type: 'POST',
                data: {
                    order_id: order_id,
                    status: status
                },
                success: function(response) {
                    alert('Order status updated successfully');
                },
                error: function() {
                    alert('An error occurred while updating the order status');
                }
            });
        });
    </script>
</body>
</html>
