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
if (isset($_SESSION['AdmOrEmp'])) 
{
    $adminstate = intval($_SESSION['AdmOrEmp']);
    // Ensure the user is an admin or employee and redirect accordingly
    if ($adminstate == 1) 
    {
        $adminstate = "Admin";
    } 
    elseif ($adminstate == 0)
    {
        $adminstate = "Employee";
    } 
} 
else 
{
    header("Location: admin_login.php");

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

// Delete review record if delete button is clicked
if (isset($_GET['delete'])) {
    $r_id = intval($_GET['delete']);
    $admin_id = $_SESSION['admin_id']; 

    if ($admin_id) {
        // Prepare and execute the delete statement
        $sql = "DELETE FROM reviews WHERE r_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $r_id);
        $stmt->execute();
        $stmt->close();

        // Insert into change_log
        $log_sql = "INSERT INTO change_log (table_name, operation, record_id, admin_id, log_time) 
                    VALUES ('reviews', 'DELETE', ?, ?, NOW())";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("ii", $r_id, $admin_id);
        $log_stmt->execute();
        $log_stmt->close();

        // Redirect to refresh the page after deletion
        header("Location: manage_reviews.php");
        exit();
    } else {
        echo "Admin ID not found.";
    }
}   


if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $reviews_sql = "SELECT reviews.r_id, clients.username, clients.id, clients.email, clients.phone, reviews.rating, reviews.comment, reviews.date, reviews.approved
                    FROM reviews
                    JOIN clients ON reviews.id = clients.id
                    WHERE reviews.comment LIKE '%$search%'
                    ORDER BY reviews.date DESC";
} else {
    $reviews_sql = "SELECT reviews.r_id, clients.username, clients.id, clients.email, clients.phone, reviews.rating, reviews.comment, reviews.date, reviews.approved
                    FROM reviews
                    JOIN clients ON reviews.id = clients.id
                    ORDER BY reviews.date DESC";
}
$result = $conn->query($reviews_sql);

if (isset($_GET['action']) && isset($_GET['r_id'])) {
    $r_id = intval($_GET['r_id']);
    $action = $_GET['action'];
    $admin_id = $_SESSION['admin_id'];

    if ($action === 'hide') {
        $sql = "UPDATE reviews SET approved = 0 WHERE r_id = ?";
        $operation = 'HIDE';
    } elseif ($action === 'show') {
        $sql = "UPDATE reviews SET approved = 1 WHERE r_id = ?";
        $operation = 'SHOW';
    } else {
        // Invalid action
        header("Location: manage_reviews.php");
        exit();
    }

    // Prepare and execute the SQL query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $r_id);
    $stmt->execute();
    $stmt->close();

    // Insert into change_log
    if ($admin_id) {
        $log_sql = "INSERT INTO change_log (table_name, operation, record_id, admin_id, log_time) 
                    VALUES ('reviews', ?, ?, ?, NOW())";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("sii", $operation, $r_id, $admin_id);
        $log_stmt->execute();
        $log_stmt->close();
    }

    // Redirect back to the reviews management page
    header("Location: manage_reviews.php");
    exit();
}   
?>

<!DOCTYPE html>
<html lang="en">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./css/manage_review.css">
    
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
            <div class ="nav_con">
                <div class="nav_list">
                    <a href="#" class="nav_link">
                       <br>
                    </a>
                    <a href="admin_dashboard.php" class="nav_link">
                        <i class='bx bx-grid-alt nav_icon'></i>
                        <span class="nav_name"> Dashboard</span>
                    </a>
                    <a href="insert_product.php" class="nav_link">
                        <i class="bi bi-plus"></i>
                        <span class="nav_name"> Products</span>
                    </a>
                    <a href="./manage_order.php" class="nav_link">
                        <i class="bi bi-bag-heart"></i>
                        <span class="nav_name">Orders</span>
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
                    <a href="./manage_reviews.php" class="nav_link active">
                    <i class="bi bi-chat-left-quote"></i>
                        <span class="nav_name"> Reviews</span>
                    </a>
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
<h2><p style="margin-top: 10px"> <i class="bi bi-chat-left-quote"></i> REVIEWS MANAGER </p></h2>


<form action="manage_reviews.php" method="GET">
        <div class="search">
            <label for="search">
                <input type="text" id="search" name="search" placeholder="Search comments">
                <button type="submit" class="btn-search"><i class="bi bi-search"></i></button>
            </label>
        </div>
    </form>
<table >
    <thead>
        <tr>
            <th>R_ID</th>
            <th>Username</th>
            <th>U_ID</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Rating</th>
            <th>Comments</th>
            <th>Date</th>
            <th>Approved</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $row['r_id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td>
                    <?php
                    $rating = $row['rating'];
                    for ($i = 0; $i < 5; $i++) {
                        if ($i < $rating) {
                            echo "<i class='fas fa-star text-warning'></i>";
                        } else {
                            echo "<i class='far fa-star text-warning'></i>";
                        }
                    }
                    ?>
                </td>
                <td><?php echo $row['comment']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo ($row['approved'] == 1) ? 'true' : 'false'; ?></td>
                <td>
                    <?php if ($row['approved'] == 1) : ?>
                        <a href="?action=hide&r_id=<?php echo $row['r_id']; ?>" class="btn-hide"><i class="bi bi-eye-slash-fill"></i> Hide</a>
                    <?php else : ?>
                        <a href="?action=show&r_id=<?php echo $row['r_id']; ?>" class="btn-show"><i class="bi bi-eye-fill"></i> Show</a>
                    <?php endif; ?>
                    <a href="?delete=<?php echo $row['r_id']; ?>" onclick="return confirm('Are you sure you want to delete this record?');" class="btn-delete"><i class="bi bi-trash3-fill"></i> Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
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