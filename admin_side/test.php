<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/CIP-1101 FINAL PROJECT-20240522T032656Z-001/CIP-1101 FINAL PROJECT/nav.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
    <title>INSERT PRODUCT</title>
    <style>
        .small-img {
            width: 285px; /* Set the width to the desired size */
            height: auto; /* Maintain the aspect ratio */
            display: block; /* Ensure the image is displayed as a block element */
        }
    </style>
    <style>
        <?php include "insert_product.css" ?>
    </style>
</head>

<body id="body-pd">
    <header class="header" id="header">
        <div class="header_toggle"><i class='bx bx-menu' id="header-toggle"></i></div>
        <div class="header_logo"><img src="./assets/header.png" alt="Header Logo"></div>
        <div class="dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"> Admin John Lord De guzman</i>
            </a>
            <ul class="dropdown-menu">
                <img src="./assets/logo.jpg" alt="Logo"><br>
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
                    <a href="#" class="nav_link">
                        <i class='bx bx-grid-alt nav_icon'></i>
                        <span class="nav_name"> Dashboard</span>
                    </a>
                    <a href="./update_product.php" class="nav_link active">
                        <i class="bi bi-plus"></i>
                        <span class="nav_name"> Products</span>
                    </a>
                    <a href="#" class="nav_link">
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
                    <a href="#" class="nav_link">
                        <i class="bi bi-receipt"></i>
                        <span class="nav_name"> Sales</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Post Section Start -->
    <section class="outer">
        <div class="container">
            <br>
            <h3>INSERT PRODUCT</h3>
            <form runat="server" action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <!-- Left side form fields -->
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="product_name" class="form-label"><span>&#42;</span> Product Name</label>
                            <input type="text" id="product_name" name="product_name" class="form-control">
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="product_price" class="form-label"><span>&#42;</span> Product Price</label>
                                <input type="text" id="product_price" name="product_price" class="form-control">
                            </div>
                            <div class="col">
                                <label for="product_qty" class="form-label"><span>&#42;</span>Product Quantity</label>
                                <input type="text" id="product_qty" name="product_qty" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="textarea" class="form-label"><span>&#42;</span> Product Details</label>
                            <input type="text" id="product_detail" name="product_detail" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="textarea" class="form-label"><span>&#42;</span> Personalization Details</label>
                            <input type="text" id="product_persona" name="product_persona" class="form-control">
                        </div>
                    </div>

                    <!-- Right side image uploads -->
                    <div class="col-md-4">
                        <label for="imageDropdown" class="form-label"><span>&#42;</span> Select Image to Upload</label>
                        <select id="imageDropdown" class="form-select mb-3">
                            <option value="1">Image 1</option>
                            <option value="2">Image 2</option>
                            <option value="3">Image 3</option>
                            <option value="4">Image 4</option>
                        </select>
                        <div id="imageUploadContainer">
                            <label for="image1" class="form-label"><span>&#42;</span> Product Image/s</label>
                            <!-- Palagyan nalang ng card dito para d out of place tignan ung image -->
                            <img src="#" alt="your image" id="tempImg1" class="tempImg small-img"/>
                            <img src="#" alt="your image" id="tempImg2" class="tempImg small-img" style="display: none;"/>
                            <img src="#" alt="your image" id="tempImg3" class="tempImg small-img" style="display: none;"/>
                            <img src="#" alt="your image" id="tempImg4" class="tempImg small-img" style="display: none;"/>
                            <input type="file" id="image1" name="image1" class="form-control mb-3">
                            <input type="file" id="image2" name="image2" class="form-control mb-3" style="display: none;">
                            <input type="file" id="image3" name="image3" class="form-control mb-3" style="display: none;">
                            <input type="file" id="image4" name="image4" class="form-control mb-3" style="display: none;">
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-plus"></i> INSERT</button>
                    <button class="btn btn-secondary" type="reset"><i class="bi bi-x"></i> CLEAR</button>
                </div>
            </form>
        </div>
    </section>

    <!-- PRODUCT OVERVIEW-->
    <div class="outer_container">
        <div class="container-lg my-5">
            <hr><br>
            <h1> PRODUCT ADDED</h1><br>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-5">
                <div class="col">
                    <div class="product_card">
                        <img src="./assets/personalized tumbler.jpg" class="card-img-top" alt="Personalized tumbler">
                        <div class="product_card-body">
                            <h5 class="product-name">Personalized tumbler</h5>
                            <div class="product-price">
                                <p><i class="bi bi-tag"></i>$25.00</p>
                                <h6 style="font-size: 12px; font-weight:bold; color:red; text-align:right;"> stock: 100pcs</h6>
                            </div>
                            <p class="product-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                            <div class="parent">
                                <div class="update">
                                    <button class="btn" type="submit"><i class="bi bi-arrow-clockwise"></i> UPDATE</button>
                                </div>
                                <div class="delete">
                                    <button class="btn"><i class="bi bi-trash"></i> DELETE</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Add more product cards as needed -->
            </div>
        </div>
    </div>

    <!-- Script Imports -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-qjzZpLRIi5ls1ZXIsJd3i0rYBfZIHEq1hxTvzVHiAxd7ozxBRH4nN1Duu9gZX2+j" crossorigin="anonymous"></script>
    <script src="/CIP-1101 FINAL PROJECT-20240522T032656Z-001/CIP-1101 FINAL PROJECT/nav.js"></script>

    <!-- JS for image selection -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageDropdown = document.getElementById('imageDropdown');
            const fileInputs = document.querySelectorAll('input[type="file"]');
            const imgShow = document.querySelectorAll('.tempImg');

            imageDropdown.addEventListener('change', function() {
                const selectedValue = imageDropdown.value;

                imgShow.forEach((img, index) => {
                    if ((index + 1) == selectedValue) {
                        img.style.display = 'block';
                    } else {
                        img.style.display = 'none';
                    }
                });

                fileInputs.forEach((fileInput, index) => {
                    if ((index + 1) == selectedValue) {
                        fileInput.style.display = 'block';
                    } else {
                        fileInput.style.display = 'none';
                    }
                });
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

        ImgShow.forEach((fileInput, index) => {
            if (index == 0) {
                img.style.display = 'block';
            } else {
                img.style.display = 'none';
            }
        });

        fileInputs.forEach((fileInput, index) => {
            if (index == 0) {
                fileInput.style.display = 'block';
            } else {
                fileInput.style.display = 'none';
            }
        });

        document.addEventListener("DOMContentLoaded", function(event) {
            const showNavbar = (toggleId, navId, bodyId, headerId) => {
                const toggle = document.getElementById(toggleId),
                    nav = document.getElementById(navId),
                    bodypd = document.getElementById(bodyId),
                    headerpd = document.getElementById(headerId);

                if (toggle && nav && bodypd && headerpd) {
                    toggle.addEventListener('click', () => {
                        nav.classList.toggle('show');
                        toggle.classList.toggle('bx-x');
                        bodypd.classList.toggle('body-pd');
                        headerpd.classList.toggle('body-pd');
                    });
                }
            }

            showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header');

            const linkColor = document.querySelectorAll('.nav_link');

            function colorLink() {
                if (linkColor) {
                    linkColor.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                }
            }
            linkColor.forEach(l => l.addEventListener('click', colorLink));
        });

        imgInp.onchange = evt => {
          const [file] = imgInp.files
          if (file) {
            tempImg.src = URL.createObjectURL(file)
          }
        }
    </script>

    <?php
    ini_set('max_execution_time', 300);
    ini_set('memory_limit', '128M');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        } else {
            echo "Error inserting product: " . $stmt_product->error;
        }

        $image_files = array();

        for ($i = 1; $i <= 4; $i++) {
            $image_name = "image" . $i;

            // Check if the file is uploaded without errors
            if (isset($_FILES[$image_name]) && $_FILES[$image_name]['error'] === UPLOAD_ERR_OK) {
                $file_tmp_name = $_FILES[$image_name]['tmp_name'];
                $file_content = file_get_contents($file_tmp_name);
                $image_files[] = $file_content;
            }
        }

        // Insert image data into the images table
        $sql_image = "INSERT INTO images (p_id, image) VALUES (?, ?)";
        $stmt_image = $conn->prepare($sql_image);

        if ($stmt_image === false) {
            die("Error preparing statement: " . $conn->error);
        }

        foreach ($image_files as $file_content) {
            $stmt_image->bind_param("ib", $product_id, $null);
            $stmt_image->send_long_data(1, $file_content);

            if (!$stmt_image->execute()) {
                die("Error inserting image: " . $stmt_image->error);
            } else {
                echo "Image inserted successfully.<br>";
            }
        }
    

        // Close statements and connection
        $stmt_product->close();
        $stmt_image->close();
        $conn->close();

        echo "successful sql query: ";
        exit();
    } else {
        echo "Error sql query: ";
        exit();
    }
    ?>
</body>
</html>