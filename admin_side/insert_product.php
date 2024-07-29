<!DOCTYPE html>
<html lang="en">
<?php
session_start(); // Ensure session is started

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'client_db';

// Db Connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

$adminstate = '';
if (isset($_SESSION['AdmOrEmp'])) {
    $adminstate = intval($_SESSION['AdmOrEmp']);
    // Ensure the user is an admin or employee and redirect accordingly
    if ($adminstate === 1) {
        $adminstate = "Admin";
    } elseif ($adminstate === 0) {
        $adminstate = "Employee";
    }
} else {
    // Redirect if no AdmOrEmp is set
    header("Location: admin_login.php");
    exit(); // Ensure script stops executing after redirection
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

// Handle form submission
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        // Delete product
        $product_id = $_POST['product_id'];
        $sql_delete_product = "DELETE FROM products WHERE p_id = ?";
        $stmt_delete_product = $conn->prepare($sql_delete_product);
        $stmt_delete_product->bind_param("i", $product_id);

        if ($stmt_delete_product->execute()) {
            $message = "Product deleted successfully.";
        } else {
            $message = "Error deleting product: " . $stmt_delete_product->error;
        }

        // Delete associated images
        $sql_delete_images = "DELETE FROM images WHERE p_id = ?";
        $stmt_delete_images = $conn->prepare($sql_delete_images);
        $stmt_delete_images->bind_param("i", $product_id);

        if (!$stmt_delete_images->execute()) {
            $message = "Error deleting images: " . $stmt_delete_images->error;
        }

        // Close delete statements
        $stmt_delete_product->close();
        $stmt_delete_images->close();
    } else {
        // Prepare data from POST
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_qty = $_POST['product_qty'];
        $product_details = $_POST['product_detail'];
        $persona_details = $_POST['product_persona'];

        // Insert into products table
        $sql_product = "INSERT INTO products (name, quantity, price, details, persona) VALUES (?, ?, ?, ?, ?)";
        $stmt_product = $conn->prepare($sql_product);
        $stmt_product->bind_param("sidss", $product_name, $product_qty, $product_price, $product_details, $persona_details);

        if ($stmt_product->execute()) {
            $product_id = $stmt_product->insert_id; // Get the auto-generated product ID
            
            // Handle image uploads
            for ($i = 1; $i <= 4; $i++) {
                $image_name = "image" . $i;

                if (isset($_FILES[$image_name]) && $_FILES[$image_name]['error'] === UPLOAD_ERR_OK) {
                    $file_tmp_name = $_FILES[$image_name]['tmp_name'];
                    $file_content = file_get_contents($file_tmp_name);
                    
                    // Insert image data into the images table
                    $sql_image = "INSERT INTO images (p_id, image) VALUES (?, ?)";
                    $stmt_image = $conn->prepare($sql_image);
                    $stmt_image->bind_param("ib", $product_id, $null);
                    $stmt_image->send_long_data(1, $file_content);

                    if (!$stmt_image->execute()) {
                        $message = "Error inserting image: " . $stmt_image->error;
                        break;
                    }
                    $stmt_image->close(); // Close image statement
                }
            }

            if ($message === '') {
                $message = "Product and images inserted successfully.";
            }
        } else {
            $message = "Error inserting product: " . $stmt_product->error;
        }

        // Close the product statement
        $stmt_product->close();
    }
    echo "<script>var message = '$message';</script>";
}



        // Fetch products for display
        if (isset($_GET['search'])) {
            $search = '%' . $_GET['search'] . '%';
        $stmt = $conn->prepare("SELECT 
            p.p_id, 
            p.name, 
            p.quantity, 
            p.price, 
            p.details, 
            p.persona, 
            i.image,
            COUNT(i.p_id) as image_count 
        FROM 
            products p 
        LEFT JOIN 
            images i ON p.p_id = i.p_id 
        WHERE 
            p.name LIKE ? 
        GROUP BY 
            p.p_id");
            $stmt->bind_param("s", $search);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $stmt = $conn->prepare("SELECT 
                p.p_id, 
                p.name, 
                p.quantity, 
                p.price, 
                p.details, 
                p.persona, 
                i.image,
                COUNT(i.p_id) as image_count 
            FROM 
                products p 
            LEFT JOIN 
                images i ON p.p_id = i.p_id 
            GROUP BY 
                p.p_id");
            $stmt->execute();
            $result = $stmt->get_result();
        }

?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/CIP-1101 FINAL PROJECT-20240522T032656Z-001/CIP-1101 FINAL PROJECT/nav.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <script type="text/javascript"
      src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js">
    </script>
    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./css/insert_product.css">


    <title>INSERT PRODUCT</title>
    
</head>

<body id="body-pd" style="background-image:url('./assets/bg.png');">
    <header class="header" id="header">
        <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
        <div class="header_logo"> <img src ="./assets/header.jpg"> </div>
        <div class="dropdown">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-circle"> <?php echo $adminstate; ?> <?php echo $admin_name; ?></i></a>
                <ul class="dropdown-menu">
                    <img src="./assets/logo.jpg"><br>
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
                    <a href="" class="nav_link">
                       <br>
                    </a>
                    <a href="./admin_dashboard.php" class="nav_link">
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
                    <?php if ($adminstate === "Admin"): ?>
                    <a href="./manage_employees.php" class="nav_link">
                        <i class="bi bi-people"></i>
                        <span class="nav_name"> Employees</span>
                    </a>
                    <a href="./manage_clients.php" class="nav_link">
                        <i class='bx bx-user nav_icon'></i>
                        <span class="nav_name"> Users</span>
                    </a>
                    <?php endif; ?>
                    <a href="./manage_reviews.php" class="nav_link">
                    <i class="bi bi-chat-left-quote"></i>
                        <span class="nav_name"> Reviews</span>
                    <a href="./manage_gallery.php" class="nav_link">
                        <i class="bi bi-images"></i>
                        <span class="nav_name"> Gallery</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>

 <!-- Modal -->
 <div class="modal fade col-lg-12" id="messageModal" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="modalMessage"></p>
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- Main Post Section Start -->
<section class="outer">
    <div class="container"><br>
        <h3>INSERT PRODUCT</h3>
        <form runat="server" action="insert_product.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <!-- Left side form fields -->
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="product_name"><span style="color: red;">&#42; </span>Product Name</label>
                        <input type="text" id="product_name" name="product_name" class="form-control" placeholder="Name" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="product_price"><span style="color: red;">&#42; </span>Product Price</label>
                            <input type="text" id="product_price" name="product_price" class="form-control" placeholder="Product Price" required>
                        </div>
                        <div class="col">
                            <label for="product_qty"><span style="color: red;">&#42; </span>Product Quantity</label>
                            <input type="text" id="product_qty" name="product_qty" class="form-control" placeholder="Product Quantity" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="product_detail"><span style="color: red;">&#42; </span>Product Details</label>
                        <input type="text" id="product_detail" name="product_detail" class="form-control" placeholder="Product Details" required>
                    </div>
                    <div class="mb-3">
                        <label for="product_persona"><span style="color: red;">&#42; </span>Product Personalization Details</label>
                        <input type="text" id="product_persona" name="product_persona" class="form-control" placeholder="Product Personalization Details" required>                            
                    </div>
                </div>
                <!-- Right side image uploads -->
                <div class="col-md-4 mt-0">
                    <label for="imageDropdown" class="form-label" style="margin-top: -100px;"><span style="color: red;">&#42;</span> Select Image to Upload</label>
                    <select id="imageDropdown" class="form-select mb-3 mt-0">
                        <option value="1">Image 1</option>
                        <option value="2">Image 2</option>
                        <option value="3">Image 3</option>
                        <option value="4">Image 4</option>
                    </select>
                    <div id="imageUploadContainer">
                        <label for="image1" class="form-label"><span style="color: red;">&#42;</span> Product Image/s</label>
                        <div class="card-container">
                            <img src="#" id="tempImg1" class="tempImg"/>
                            <img src="#" id="tempImg2" class="tempImg" style="display: none;"/>
                            <img src="#" id="tempImg3" class="tempImg" style="display: none;"/>
                            <img src="#" id="tempImg4" class="tempImg" style="display: none;"/>
                        </div>
                        <input type="file" id="image1" name="image1" class="form-control mb-3">
                        <input type="file" id="image2" name="image2" class="form-control mb-3" style="display: none;">
                        <input type="file" id="image3" name="image3" class="form-control mb-3" style="display: none;">
                        <input type="file" id="image4" name="image4" class="form-control mb-3" style="display: none;">
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button class="insert-btn" type="submit"><i class="bi bi-plus"></i> INSERT</button>
                <button class="clear-btn" type="reset"><i class="bi bi-x"></i> CLEAR</button>
            </div>
        </form>
    </div>
</section>
            


       
      <!-- PRODUCT OVERVIEW-->
     
      <div class="outer_container">
    <div class="table_container">
        <div class="container-lg my-5">
            <hr><br>
            <div class="container-lg">
                <form action="" method="GET">
                    <div class="search">
                        <label for="search">
                            <input type="text" id="search" name="search" placeholder="Search by product name">
                            <button type="submit" class="btn-search"><i class="bi bi-search"></i></button>
                        </label>
                    </div>
                </form>
            </div>

            <div class="table-wrapper col-lg-12 col-sm-5">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Details</th>
                        <th>Persona</th>
                        <th>Image Count</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['p_id'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['quantity'] . "</td>";
                            echo "<td>" . $row['price'] . "</td>";
                            echo "<td>" . $row['details'] . "</td>";
                            echo "<td>" . $row['persona'] . "</td>";
                            echo "<td>" . $row['image_count'] . "</td>";
                            echo "<td>
                                    <form method='get' action='update_product.php' class='form-action-edit d-inline'>
                                        <input type='hidden' name='product_id' value='" . $row['p_id'] . "' />
                                        <button type='submit' class='edit-btn'>
                                            <i class='bi bi-pencil'></i> Edit
                                        </button>
                                    </form>
                                    <form method='post' action='' class='form-action-delete d-inline'>
                                        <input type='hidden' name='product_id' value='" . $row['p_id'] . "' />
                                        <button type='submit' name='delete' class='delete-btn'>
                                            <i class='bi bi-trash'></i> Delete
                                        </button>
                                    </form>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No products found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>

   

    <!-- Script Imports -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-qjzZpLRIi5ls1ZXIsJd3i0rYBfZIHEq1hxTvzVHiAxd7ozxBRH4nN1Duu9gZX2+j" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBgP9TOMA2d4aJP2CV5oY5SmXKp4vFdKNLfz8e0CfGpYLg1K" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-cuGuq6ESjq6Hj/F6tf4A8H7KVsOh6DIvoLJ1JrXPLVvo9aLhNT44MTd5Mh1uXelA" crossorigin="anonymous"></script>
    
    <!-- Bootstrap and EmailJS scripts -->
    <!-- Include Bootstrap JS and EmailJS -->
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

    <!-- <script src="../script.js"></script> -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- script for navbar start-->
    <script>
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
        linkColor.forEach(l => l.addEventListener('click', colorLink));

        // Image Upload Display Functionality
        const imageDropdown = document.getElementById('imageDropdown');
        const fileInputs = document.querySelectorAll('input[type="file"]');
        const imgShow = document.querySelectorAll('.tempImg');

        function updateDisplay(selectedValue) {
            imgShow.forEach((img, index) => {
                img.style.display = (index + 1) == selectedValue ? 'block' : 'none';
            });

            fileInputs.forEach((fileInput, index) => {
                fileInput.style.display = (index + 1) == selectedValue ? 'block' : 'none';
            });
        }

        // Initial display setup
        updateDisplay(imageDropdown.value);

        imageDropdown.addEventListener('change', function() {
            updateDisplay(this.value);
        });

        fileInputs.forEach((fileInput, index) => {
            fileInput.addEventListener('change', function() {
                const img = document.getElementById('tempImg' + (index + 1));
                const [file] = fileInput.files;
                if (file) {
                    img.src = URL.createObjectURL(file);
                }
            });
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
    const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    let formToSubmit;

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            formToSubmit = button.closest('form');
            document.querySelector('.modal-body').textContent = 'Are you sure you want to delete this product?';
            document.getElementById('confirmButton').textContent = 'Delete';
            confirmationModal.show();
        });
    });

    document.querySelectorAll('.insert-btn').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            formToSubmit = button.closest('form');
            document.querySelector('.modal-body').textContent = 'Are you sure you want to insert this product?';
            document.getElementById('confirmButton').textContent = 'Insert';
            confirmationModal.show();
        });
    });

    document.getElementById('confirmButton').addEventListener('click', function () {
        formToSubmit.submit();
    });
});

    document.addEventListener('DOMContentLoaded', function () {
    const productNameInput = document.getElementById('product_name');
    const productPriceInput = document.getElementById('product_price');
    const productQtyInput = document.getElementById('product_qty');
    const productDetailInput = document.getElementById('product_detail');
    const productPersonaInput = document.getElementById('product_persona');
    const imageDropdown = document.getElementById('imageDropdown');
    const imageUploadContainer = document.getElementById('imageUploadContainer');
    const tempImages = document.querySelectorAll('.tempImg');
    const imageInputs = document.querySelectorAll('input[type="file"]');

    const nameRegex = /^[a-zA-Z0-9\s]{3,50}$/;
    const priceRegex = /^\d+(\.\d{1,2})?$/;
    const qtyRegex = /^\d+$/;
    const detailRegex = /^[a-zA-Z0-9\s.,-]{10,200}$/;

    productNameInput.addEventListener('input', function () {
        if (!nameRegex.test(productNameInput.value)) {
            productNameInput.setCustomValidity('Product name should be 3-50 characters long and contain only letters, numbers, and spaces.');
        } else {
            productNameInput.setCustomValidity('');
        }
    });

    productPriceInput.addEventListener('input', function () {
        if (!priceRegex.test(productPriceInput.value)) {
            productPriceInput.setCustomValidity('Please enter a valid price (e.g., 10.00).');
        } else {
            productPriceInput.setCustomValidity('');
        }
    });

    productQtyInput.addEventListener('input', function () {
        if (!qtyRegex.test(productQtyInput.value)) {
            productQtyInput.setCustomValidity('Please enter a valid quantity (e.g., 5).');
        } else {
            productQtyInput.setCustomValidity('');
        }
    });

    productDetailInput.addEventListener('input', function () {
        if (!detailRegex.test(productDetailInput.value)) {
            productDetailInput.setCustomValidity('Product details should be 10-200 characters long and can contain letters, numbers, spaces, periods, and hyphens.');
        } else {
            productDetailInput.setCustomValidity('');
        }
    });

    productPersonaInput.addEventListener('input', function () {
        if (!detailRegex.test(productPersonaInput.value)) {
            productPersonaInput.setCustomValidity('Personalization details should be 10-200 characters long and can contain letters, numbers, spaces, periods, and hyphens.');
        } else {
            productPersonaInput.setCustomValidity('');
        }
    });

    imageDropdown.addEventListener('change', function () {
        const selectedImageIndex = parseInt(imageDropdown.value) - 1;
        tempImages.forEach((img, index) => {
            img.style.display = index === selectedImageIndex ? 'block' : 'none';
        });
        imageInputs.forEach((input, index) => {
            input.style.display = index === selectedImageIndex ? 'block' : 'none';
        });
    });

    imageInputs.forEach((input, index) => {
        input.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    tempImages[index].src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });
});


</script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Display the modal with the message if set
        if (typeof message !== 'undefined' && message !== '') {
            document.getElementById('modalMessage').textContent = message;
            $('#messageModal').modal('show');
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>