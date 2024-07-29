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

$admin_id = $_SESSION['admin_id'] ?? null;
$admin_name = '';

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

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $admin_id = $_SESSION['admin_id']; // Assuming admin_id is stored in the session

    if ($admin_id) {
        // Prepare and execute the delete statement
        $sql = "DELETE FROM admins WHERE admin_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Insert into change_log
        $log_sql = "INSERT INTO change_log (table_name, operation, record_id, admin_id, log_time) 
                    VALUES ('admins', 'DELETE', ?, ?, NOW())";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("ii", $id, $admin_id);
        $log_stmt->execute();
        $log_stmt->close();

        // Redirect to refresh the page after deletion
        header("Location: manage_employees.php");
        exit();
    } else {
        echo "Admin ID not found.";
    }
}

// Fetch all search results
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT * FROM admins WHERE username LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM admins";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./css/manage_employees.css">

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
                    <a href="admin_dashboard.php" class="nav_link">
                        <i class='bx bx-grid-alt nav_icon'></i>
                        <span class="nav_name"> Dashboard</span>
                    </a>
                    <a href="./insert_product.php" class="nav_link ">
                        <i class="bi bi-plus"></i>
                        <span class="nav_name"> Products</span>
                    </a>
                    <a href="#" class="nav_link">
                        <i class="bi bi-bag-heart"></i>
                        <span class="nav_name"> Orders</span>
                    </a>
                    <a href="./manage_employees.php" class="nav_link  active">
                        <i class="bi bi-people"></i>
                        <span class="nav_name"> Employees</span>
                    </a>
                    <a href="./manage_clients.php" class="nav_link">
                        <i class='bx bx-user nav_icon'></i>
                        <span class="nav_name"> Users</span>
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

    <div class="content" id="content">
    <h2><i class="bi bi-person-check-fill"></i> EMPLOYEE MANAGER</h2>
    <div class="container">
        <a href="admin_registerform.php" class="btn-add"><i class="bi bi-plus-lg"></i>Add New User</a>
    <form action="manage_employees.php" method="GET">
        <div class="search">
            <label for="search">
            <span>
                <input type="text" id="search" name="search" placeholder="Search username">
                <button type="submit" class="btn-search"><i class="bi bi-search"></i></button>
            </span>
            </label>
        </div>
    </form>
    </div>
    <div class="table_container">
    <div class="table-wrapper col-lg-12 col-sm-5">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Employee<br>Type</th>
                <th>Username</th>
                <th>Name</th>
                <th>Age</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['admin_id']; ?></td>
                    <td>
                        <?php 
                        if ($row['is_admin'] == 0) {
                            echo 'Employee';
                        } else {
                            echo 'Admin';
                        }
                        ?>
                    </td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                    <td><?php echo $row['age']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td>
                        
                        <a href="./admin_edit_profile.php?id=<?php echo $row['admin_id']; ?>" class="btn-update"><i class="bi bi-arrow-clockwise"></i> Update</a><br><br>
                        <a href="?delete=<?php echo $row['admin_id']; ?>" onclick="return confirm('Are you sure you want to delete this record?');" class="btn-delete"><i class="bi bi-trash3-fill"></i> Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</div>
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

            /*===== LINK ACTIVE =====*/
            const linkColor = document.querySelectorAll('.nav_link')

            function colorLink(){
                if(linkColor){
                    linkColor.forEach(l=> l.classList.remove('active'))
                    this.classList.add('active')
                }
            }
            linkColor.forEach(l=> l.addEventListener('click', colorLink))

            // Your code to run since DOM is loaded and ready
        });
    </script>
</body>
</html>
