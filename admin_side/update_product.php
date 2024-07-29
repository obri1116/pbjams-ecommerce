<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'client_db';

$conn = mysqli_connect($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
} elseif (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
} else {
    die("Product ID is not specified.");
}

// Fetch product details
$sql_product = "SELECT * FROM products WHERE p_id = ?";
$stmt_product = $conn->prepare($sql_product);
$stmt_product->bind_param("i", $product_id);
$stmt_product->execute();
$product_result = $stmt_product->get_result();
$product = $product_result->fetch_assoc();

// Fetch product images
$sql_images = "SELECT * FROM images WHERE p_id = ?";
$stmt_images = $conn->prepare($sql_images);
$stmt_images->bind_param("i", $product_id);
$stmt_images->execute();
$images_result = $stmt_images->get_result();
$images = [];
while ($row = $images_result->fetch_assoc()) {
    $images[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = intval($_POST['product_id']);
    $product_name = $conn->real_escape_string(trim($_POST['product_name']));
    $product_price = $conn->real_escape_string(trim($_POST['product_price']));
    $product_qty = $conn->real_escape_string(trim($_POST['product_qty']));
    $product_detail = $conn->real_escape_string(trim($_POST['product_detail']));
    $product_persona = $conn->real_escape_string(trim($_POST['product_persona']));
    
    // Update product details
    $sql_update = "UPDATE products SET Name = ?, Quantity = ?, Price = ?, Details = ?, Persona = ? WHERE p_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssssi", $product_name, $product_qty, $product_price, $product_detail, $product_persona, $product_id);

    if ($stmt_update->execute()) {
        echo '<script type="text/javascript">
                alert("Record updated successfully");
                location.href = "insert_product.php";
              </script>';
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Image deletion
    if (isset($_POST['delete_image_ids'])) {
        $delete_image_ids = $_POST['delete_image_ids'];
        foreach ($delete_image_ids as $image_id) {
            $sql_delete_image = "DELETE FROM images WHERE i_id = ?";
            $stmt_delete_image = $conn->prepare($sql_delete_image);
            $stmt_delete_image->bind_param("i", $image_id);
            $stmt_delete_image->execute();
        }
    }

    // New image upload
    for ($i = 1; $i <= 4; $i++) {
        if (isset($_FILES["image$i"]) && $_FILES["image$i"]['error'] == UPLOAD_ERR_OK) {
            $image = file_get_contents($_FILES["image$i"]['tmp_name']);
            $sql_insert_image = "INSERT INTO images (p_id, image) VALUES (?, ?)";
            $stmt_insert_image = $conn->prepare($sql_insert_image);
            $stmt_insert_image->bind_param("ib", $product_id, $image);
            $stmt_insert_image->send_long_data(1, $image);
            $stmt_insert_image->execute();
        }
    }

    $stmt_update->close();
}

$stmt_product->close();
$stmt_images->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="./css/update_product.css">
    <title>UPDATE PRODUCT</title>

    

</head>
<body id="body-pd" style="background-image:url('./assets/bg.png');">
<header class="header" id="header">
    <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
    <div class="header_logo"> <img src ="./assets/header.jpg"> </div>
    <div class="dropdown">
        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-circle"> Admin John Lord De guzman</i></a>
        <ul class="dropdown-menu">
            <img src="./assets/logo.jpg"><br>
            <h6>John Lord De guzman</h6>
            <p>@username</p>
            <hr>
            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil-square"></i> Edit profile</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
        </ul>
    </div>
</header>
<div class="l-navbar" id="nav-bar">
    <nav class="nav">
        <div class="nav_con">
            <div class="nav_list">
                <a href="#" class="nav_link"><br></a>
                <a href="admin_dashboard.php" class="nav_link">
                    <i class='bx bx-grid-alt nav_icon'></i>
                    <span class="nav_name"> Dashboard</span>
                </a>
                <a href="./insert_product.php" class="nav_link active">
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
                <a href="./manage_clients.php" class="nav_link">
                    <i class='bx bx-user nav_icon'></i>
                    <span class="nav_name"> Users</span>
                </a>
                <a href="./manage_reviews.php" class="nav_link">
                    <i class="bi bi-chat-left-quote"></i>
                    <span class="nav_name"> Reviews</span>
                </a>
                <a href="./manage_gallery.php" class="nav_link">
                        <i class="bi bi-images"></i>
                        <span class="nav_name"> Gallery</span>
                </a>
            </div>
        </div>
    </nav>
</div>
<script>
     function onlyAlphabetsAndSpaces(event) {
  var inputValue = event.target.value;
  var regex = /^[a-zA-Z\s]+$/;
  if (regex.test(inputValue)) {
    return true;
  } else {
    return false;
  }
}

function onlyNumbers(event) {
  var inputValue = event.target.value;
  var regex = /^\d+$/;
  if (!regex.test(inputValue)) {
    event.target.value = inputValue.replace(/[^0-9]/g, '');
  }
}
</script>

<!-- Main Post Section Start -->
<section class="outer_container">
    <div class="container">
        <br>
        <h3 class="text-center">UPDATE PRODUCT</h3>
        <form action="update_product.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['p_id']); ?>">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="product_name" class="form-label"><span>&#42;</span> Product Name</label>
                        <input type="text" id="product_name" name="product_name" class="form-control" value="<?php echo htmlspecialchars($product['Name']); ?>" required onkeypress="return onlyAlphabetsAndSpaces(event);">
                        </div>
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label for="product_price" class="form-label"><span>&#42;</span> Product Price</label>
                            <input type="text" id="product_price" name="product_price" class="form-control" value="<?php echo htmlspecialchars($product['Price']); ?>" required oninput="onlyNumbers(event)">                            </div>
                        <div class="col-sm-6">
                            <label for="product_qty" class="form-label"><span>&#42;</span> Product Quantity</label>
                            <input type="text" id="product_qty" name="product_qty" class="form-control" value="<?php echo htmlspecialchars($product['Quantity']); ?>" required oninput="onlyNumbers(event)">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="product_detail" class="form-label"><span>&#42;</span> Product Details</label>
                        <textarea required onkeypress="return onlyAlphabetsAndSpaces(event);" sid="product_detail" name="product_detail" class="form-control" required><?php echo htmlspecialchars($product['Details']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="product_persona" class="form-label"><span>&#42;</span> Product Persona</label>
                        <textarea id="product_persona" name="product_persona" class="form-control" required onkeypress="return onlyAlphabetsAndSpaces(event);"><?php echo htmlspecialchars($product['Persona']); ?></textarea>
                    </div>
                </div>
                <!-- IMAGE START -->
                <div class="col-md-12">
                    <label class="form-label">Uploaded Images</label>
                    <div class="col">
                    <?php foreach ($images as $index => $image): ?>
                    <div class="row">
                        <!-- <div class="card"> -->
                        <div class="col-md-6">
                            <div class="imageUploadContainer">
                               <div class="card-container">
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($image['image']); ?>" class="card-img-top product-img" alt="Product Image">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="delete_image_ids[]" value="<?php echo htmlspecialchars($image['i_id']); ?>">
                                            <label class="form-check-label">Delete</label>
                                        </div>
                                </div>   
                          
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="imageUploadContainer">
                                    <div class="card-container">
                                        <label for="image<?php echo $index + 1; ?>" class="form-label">Upload New Image <?php echo $index + 1; ?></label>
                                        <input type="file" id="image<?php echo $index + 1; ?>" name="image<?php echo $index + 1; ?>" class="form-control" accept="image/*">

                                        </div>
                            </div>  
                        </div>   
                    </div>
                        
                        <?php endforeach; ?>
                        <!-- For additional images if needed -->
                    <div class="row">
                        <div class="col-md-6">
                        <?php for ($i = count($images) + 1; $i <= 4; $i++): ?>
                                <div id="imageUploadContainer" >
                                    <div class="card-container">
                                            <img src="#" id="tempImg<?php echo $i; ?>" class="tempImg" style="display: none;" />
                                    </div>
                                </div>
                        <?php endfor; ?>
                        </div>
                        <div class="col-md-6">
                        <?php for ($i = count($images) + 1; $i <= 4; $i++): ?>
                                <div id="imageUploadContainer">
                                        <div class="card-container">        
                                        <label for="image<?php echo $i; ?>" class="form-label">Upload New Image <?php echo $i; ?></label>
                                        <input type="file" id="image<?php echo $i; ?>" name="image<?php echo $i; ?>" class="form-control mb-3" onchange="previewImage(event, <?php echo $i; ?>)">
                                        </div>
                                </div>
                        <?php endfor; ?>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            <!-- IMAGE END -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Update Product</button>
            </div>
        </form>
    </div>
</section>

<!-- Main Post Section End -->

<!-- Custom JavaScript for Menu -->
<script src="./assets/custom.js"></script>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QF22U15Ra19EwyHyQIcFG0QaVnLOub4A3tfkqZ/Ut5P5B8JkUUvBUlWIGAvhM/aU" crossorigin="anonymous"></script>

<script>
//show container image

function previewImage(event, imgIndex) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('tempImg' + imgIndex);
        output.src = reader.result;
        output.style.display = 'block';
        if (imgIndex < 4) {
            document.getElementById('image' + (imgIndex + 1)).style.display = 'block';
        }
    };
    reader.readAsDataURL(event.target.files[0]);
}

//navbar
document.addEventListener("DOMContentLoaded", function() {
        // Navbar Toggle Functionality
        const showNavbar = (toggleId, navId, bodyId, headerId) => {
            const toggle = document.getElementById(toggleId);
            const nav = document.getElementById(navId);
            const bodypd = document.getElementById(bodyId);
            const headerpd = document.getElementById(headerId);

            if (toggle && nav && bodypd && headerpd) {
                toggle.addEventListener('click', () => {
                    nav.classList.toggle('show');
                    toggle.classList.toggle('bx-x');
                    bodypd.classList.toggle('body-pd');
                    headerpd.classList.toggle('body-pd');
                });
            }
        };

        showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header');

        const linkColor = document.querySelectorAll('.nav_link');
        function colorLink() {
            if (linkColor) {
                linkColor.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            }
        }
        linkColor.forEach(l => l.addEventListener('click', colorLink));})

</script>
</body>
</html>
