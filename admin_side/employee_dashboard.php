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
if (isset($_SESSION['AdmOrEmp'])) {
    $adminstate = intval($_SESSION['AdmOrEmp']);
    
    // Ensure the user is an admin or employee and redirect accordingly
    if ($adminstate === 1) {
        header("Location: admin_dashboard.php");
    } elseif ($adminstate === 0) {
        $adminstate = "Employee"; // Set to "employee" if 0
    } else {
        // Invalid adminstate, redirect to login
        header("Location: admin_login.php");
        exit();
    }
} else {
    // Redirect if no AdmOrEmp is set
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'] ?? null;
$admin_name = '';
$admin_user = '';
// Query to get admin details
if ($admin_id) {
    $stmt = $conn->prepare("SELECT first_name, last_name, username FROM admins WHERE admin_id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $admin_name = htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']);
        $admin_user = htmlspecialchars($admin['username']);
    }
    $stmt->close();
}

// Query to get count of clients
$sql = "SELECT COUNT(*) AS client_count FROM clients";
$result = $conn->query($sql);

$client_count = 0;
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $client_count = $row['client_count'];
}

$sql = "SELECT rating, COUNT(*) as count FROM reviews GROUP BY rating";
$result = $conn->query($sql);

$rating_data = array();
while ($row = $result->fetch_assoc()) {
    $rating_data[$row['rating']] = $row['count'];
}

// Fill in missing ratings with 0 count
for ($i = 1; $i <= 5; $i++) {
    if (!isset($rating_data[$i])) {
        $rating_data[$i] = 0;
    }
}

// Query for restock reminder
$restock_sql = "SELECT Name, Quantity FROM products WHERE Quantity <= 10";
$restock_result = $conn->query($restock_sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <style>
      <?php include "./css/employee-dashboard.css" ?>
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>
<body id="body-pd">
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
            <div class ="nav_con">
                <div class="nav_list">
                    <a href="#" class="nav_link">
                       <br>
                    </a>
                    <a href="./employee_dashboard.php" class="nav_link active">
                        <i class='bx bx-grid-alt nav_icon'></i>
                        <span class="nav_name"> Dashboard</span>
                    </a>
                    <a href="./insert_product.php" class="nav_link ">
                        <i class="bi bi-plus"></i>
                        <span class="nav_name"> Products</span>
                    </a>
                    <a href="./manage_order.php" class="nav_link">
                        <i class="bi bi-bag-heart"></i>
                        <span class="nav_name"> Orders</span>
                    </a>
                    <a href="./manage_reviews.php" class="nav_link">
                    <i class="bi bi-chat-left-quote"></i>
                        <span class="nav_name"> Reviews</span>
                    <a href="./manage_gallery.php" class="nav_link ">
                        <i class="bi bi-images"></i>
                        <span class="nav_name"> Gallery</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>
    
    <!--Container Main start-->
    
    <div class="container-center mt-5">
        <div class="row justify-content-center">
            <h2 class="text-center">DASHBOARD</h2>
            <div class="col-md-6 col-sm-6 col-lg-6 no-gap d-flex justify-content-end">
                <div class="display-card text-center">
                    <div class="card-body">
                        <div class="row">
                            <div class="left-content col-5">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                            <div class="right-content col-7 d-flex flex-column">
                                <h6>Pending orders</h6>
                                <h5 class="card-title"><?php echo $client_count; ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 no-gap d-flex justify-content-start">
                <div class="display-card text-center">
                    <div class="card-body">
                        <div class="row">
                            <div class="left-content col-5">
                                <i class="bi bi-chat-quote-fill"></i>
                            </div>
                            <div class="right-content col-7 d-flex flex-column">
                                <h6>Pending Review</h6>
                                <h5 class="card-title"><?php echo $client_count; ?></h5>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-12 no-gap">
                <div class="rating-display-card text-center">
                    <div class="rating-card-body">
                        <div class="row">
                            <div>
                                <h3>RATING DISTRIBUTION</h3>
                                <canvas id="rating-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-container col-lg-9 col-md-6">
        <h1 colspan="1">RESTOCK REMINDER</h1>
        <table>
            <thead>
                <tr>
                    <th colspan="1" class="sub-tbl">Product Name</th>
                    <th colspan="1" class="sub-tbl">Number of stock</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($restock_result && $restock_result->num_rows > 0) { ?>
                    <?php while ($row = $restock_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['Quantity']); ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="2">No products need restocking.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
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

            /*===== LINK ACTIVE =====*/
            const linkColor = document.querySelectorAll('.nav_link')

            function colorLink(){
                if(linkColor){
                    linkColor.forEach(l=> l.classList.remove('active'))
                    this.classList.add('active')
                }
            }
            linkColor.forEach(l=> l.addEventListener('click', colorLink))

        });

        const ctx = document.getElementById('rating-chart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['1', '2', '3', '4', '5'],
                datasets: [{
                    label: 'Rating Distribution',
                    data: [
                        <?php echo $rating_data[1]; ?>,
                        <?php echo $rating_data[2]; ?>,
                        <?php echo $rating_data[3]; ?>,
                        <?php echo $rating_data[4]; ?>,
                        <?php echo $rating_data[5]; ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
