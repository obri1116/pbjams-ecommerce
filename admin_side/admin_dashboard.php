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
        $adminstate = "Admin"; // Set to "admin" if 1
    } elseif ($adminstate === 0) {
        $adminstate = "Employee"; // Set to "employee" if 0
        header("Location: employee_dashboard.php");
        exit();
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



$change_log_sql = "SELECT change_log.table_name, change_log.operation, change_log.record_id, admins.username, change_log.log_time FROM change_log JOIN admins ON change_log.admin_id = admins.admin_id ORDER BY change_log.log_time DESC LIMIT 5";// Adjust LIMIT as needed
$change_log_result = $conn->query($change_log_sql);

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
// Query to get gender and age distribution
// Query to get gender distribution
$sql = "SELECT gender, COUNT(*) as count FROM clients GROUP BY gender";
$result = $conn->query($sql);

$gender_data = array();
while ($row = $result->fetch_assoc()) {
    $gender_data[$row['gender']] = $row['count'];
}

// Query the database to retrieve product data
$sql = "SELECT Name, Quantity FROM products";
$result = $conn->query($sql);

$productData = array();
while ($row = $result->fetch_assoc()) {
    $productData[] = array(
        'label' => $row['Name'], // Use the product name as the label
        'value' => $row['Quantity']
    );

}
// Convert the data to a format suitable for Chart.js
$productLabels = array_column($productData, 'label');
$productValues = array_column($productData, 'value');

$query = "SELECT AVG(rating) as average_rating FROM reviews";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $average_rating = round($row['average_rating'], 1); // Round to one decimal place
} else {
    $average_rating = 0;
}
$sql = "SELECT DATE(date) AS date, SUM(total_amount) AS total_amount 
        FROM orders 
        WHERE status = 'Completed' 
        GROUP BY DATE(date) 
        ORDER BY date ASC";
$result = $conn->query($sql);

// Initialize arrays to store data
$dates = array();
$totalAmounts = array();

// Fetch data from query result
while ($row = $result->fetch_assoc()) {
    $dates[] = $row['date'];
    $totalAmounts[] = $row['total_amount'];
}

$query = "SELECT Name, Quantity FROM products ORDER BY quantity ASC LIMIT 3";
$result = mysqli_query($conn, $query);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
      <?php include "./css/dashboard.css" ?>
    </style>
    <style>
        .scrollable-card {
    max-height: 300px; /* Adjust the height as needed */
    overflow-y: auto; /* Enables vertical scrolling */
    }

    .scrollable-content {
        max-height: 100%; /* Ensures the content fits within the card */
    }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>
<body id="body-pd">
    <header class="header" id="header">
        <div class="header_toggle"><i class='bx bx-menu' id="header-toggle"></i></div>
        <div class="header_logo"><img src ="./assets/header.jpg" alt="Header Logo"></div>
        <div class="dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"> <?php echo $adminstate; ?> <?php echo $admin_name; ?></i>
            </a>
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
                    </a>
                    <a href="./admin_dashboard.php" class="nav_link active">
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
                    <a href="./manage_employees.php" class="nav_link">
                        <i class="bi bi-people"></i>
                        <span class="nav_name"> Employees</span>
                    </a>
                    <a href="./manage_clients.php" class="nav_link ">
                        <i class='bx bx-user nav_icon'></i>
                        <span class="nav_name"> Users</span>
                    </a>
                    <a href="./manage_reviews.php" class="nav_link">
                    <i class="bi bi-chat-left-quote"></i>
                        <span class="nav_name"> Reviews</span></a>

                        <a href="./manage_gallery.php" class="nav_link ">
                        <i class="bi bi-images"></i>
                        <span class="nav_name"> Gallery</span>
                    </a>
                </div>
                </div>
            </div>
        </nav>
    </div>
    
    <!--Container Main start-->
    
    <div class="container-center mt-5">
            
        <div class="row">
        <h2>DASHBOARD</h2>
            <div class="col-md-6 col-sm-6 col-lg-3 ">
                <div class="display-card text-center" >
                    <div class="card-body">
                        <div class="row">
                            <div class="left-content col-5">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="right-content col-7 d-flex flex-column">
                                <h6>Total User</h6>
                                <h5 class="card-title"><?php echo $client_count; ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-3 ">
            <div class="display-card text-center" >
                <div class="card-body">
                    <div class="row">
                        <div class="left-content col-5">
                        <i class="star bi bi-star-fill"></i>
                        </div>
                        <div class="right-content col-7 d-flex flex-column">
                            <h6>Rating</h6>
                            <h5 class="card-title"><?php echo $average_rating; ?>/5</h5>
                        </div>
                    </div>
                </div>
            </div>
</div>
            
            <div class="col-md-6 col-sm-6 col-lg-3">
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
            <div class="col-md-6 col-sm-6 col-lg-3">
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
        </div>
    </div>
<br>    
<div class="container">
    <div class="row">
        <div class="scroll-container col-md-3 col-sm-12">
            <div class="card text-center">
                <div class="card-header">
                    Admin Activity Log
                </div>
                <div class="scrollable-card mb-3">
                    <div class="card-body">
                        <div class="scrollable-content">
                            <?php while ($log = $change_log_result->fetch_assoc()): ?>
                                <div class="mb-3">
                                    <h5 class="card-title"><?php echo htmlspecialchars($log['operation']); ?> in <?php echo htmlspecialchars($log['table_name']); ?></h5>
                                    <p class="card-text">
                                        Record ID: <?php echo htmlspecialchars($log['record_id']); ?><br>
                                        <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($log['username']); ?><br>
                                        <i class="bi bi-clock-fill"></i> <?php echo htmlspecialchars($log['log_time']); ?>
                                    </p>
                                    <hr>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-container col-lg-9 col-md-12">
            <h1>RESTOCK REMINDER</h1>
            <table>
                <thead>
                    <tr>
                        <th class="sub-tbl">Product Name</th>
                        <th class="sub-tbl">Number of stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['Name']; ?></td>
                            <td><?php echo $row['Quantity']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="age card-body">
                <canvas id="gender-age-chart"></canvas>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="rating-container">
                <div class="rating card-body">
                    <canvas id="rating-chart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="product-container">
                <div class="product card-body">
                    <canvas id="product-chart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="orders card-body">
                <canvas id="orders-chart"></canvas>
            </div>
        </div>
    </div>
</div>


    <!--Container Main end-->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
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

        /*bar graph*/
        const ctx = document.getElementById('rating-chart').getContext('2d');
        const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['⭐️', '⭐️⭐️', '⭐️⭐️⭐️', '⭐️⭐️⭐️⭐️', '⭐️⭐️⭐️⭐️⭐️⭐️'],
        datasets: [{
            label: '⭐️Rating',
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
        },
        legend: {
            labels: {
                fontSize: 50
            }
        }
    }
});

    /* pie chart */
    const ctxpie = document.getElementById('gender-age-chart').getContext('2d');
const chartpie = new Chart(ctxpie, {
    type: 'pie',
    data: {
        labels: [
            <?php foreach ($gender_data as $gender => $count) {
                echo "'". $gender. "', ";
            }?>
        ],
        datasets: [{
            label: 'Gender Distribution',
            data: [
                <?php foreach ($gender_data as $count) {
                    echo $count. ", ";
                }?>
            ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        title: {
            display: true,
            text: 'Gender Demographic',
            fontSize: 24
        }
    }
});

const ctxprod = document.getElementById('product-chart').getContext('2d');
const chartprod = new Chart(ctxprod, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($productData, 'label')); ?>,
        datasets: [{
            label: 'Product Quantities',
            data: <?php echo json_encode(array_column($productData, 'value')); ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(201, 203, 207, 0.2)',
                'rgba(100, 149, 237, 0.2)',
                'rgba(205, 92, 92, 0.2)',
                'rgba(139, 195, 74, 0.2)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(201, 203, 207, 1)',
                'rgba(100, 149, 237, 1)',
                'rgba(205, 92, 92, 1)',
                'rgba(139, 195, 74, 1)',
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                max: 200 // Set the maximum value to 200
            },
            x: {
                ticks: {
                    font: {
                        size: 24 // Increase font size to 18
                    }
                }
            }
        },
        title: {
            display: true,
            text: 'Product Stock',
            fontSize: 50
        }
    }
});
    const ctxorder = document.getElementById('orders-chart').getContext('2d');
    const chartorder = new Chart(ctxorder, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Total Amount of Complete Orders',
                data: <?php echo json_encode($totalAmounts); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            title: {
                display: true,
                text: 'Total Amount of Complete Orders Over Time',
                fontSize: 24
            }
        }
    });
    </script>
</body>
</html>
