<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "client_db";

// Db Connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $pattern = '/^[a-zA-Z0-9_]+$/';
    if (!preg_match($pattern, $username)) {
        echo '<script type="text/javascript">
                    alert("Username can only contain letters, numbers, and underscores.");
                    location="register.php";
                    </script>';
    } else {
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['con_pass']);

        if ($password !== $confirm_password) {
            echo '<script type="text/javascript">
                        alert("Passwords do not match.");
                        location="register.php";
                        </script>';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $first_name = mysqli_real_escape_string($conn, trim($_POST['first_name']));
            $last_name = mysqli_real_escape_string($conn, trim($_POST['last_name']));
            $middle_name = isset($_POST['middle_name']) ? mysqli_real_escape_string($conn, trim($_POST['middle_name'])) : '';
            $address = mysqli_real_escape_string($conn, trim($_POST['address']));
            $gender = mysqli_real_escape_string($conn, trim($_POST['gender']));
            $birthday = mysqli_real_escape_string($conn, trim($_POST['birthday']));
            $age = mysqli_real_escape_string($conn, trim($_POST['age']));
            $email = mysqli_real_escape_string($conn, trim($_POST['email']));
            $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));

            $sql = "SELECT id FROM clients WHERE username='$username' OR email='$email'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                echo '<script type="text/javascript">
                        alert("Username or email already exists.");
                        location="register.php";
                        </script>';
            } else {
                $sql = "INSERT INTO clients (username, password, first_name, last_name, middle_name, address, gender, birthday, age, email, phone)
                        VALUES ('$username', '$hashed_password', '$first_name', '$last_name', '$middle_name', '$address', '$gender', '$birthday', '$age', '$email', '$phone')";

                if (mysqli_query($conn, $sql)) {
                    echo '<script type="text/javascript">
                        alert("New User Registered Successfully");
                        location="login.php";
                        </script>';
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            }
        }
    }
}

mysqli_close($conn);
