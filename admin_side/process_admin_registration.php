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
                    location="manage_employees.php";
                    </script>';
    } else {
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['con_pass']);

        if ($password !== $confirm_password) {
            echo '<script type="text/javascript">
                        alert("Passwords do not match.");
                        location="manage_employees.php";
                        </script>';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $first_name = mysqli_real_escape_string($conn, trim($_POST['first_name']));
            $last_name = mysqli_real_escape_string($conn, trim($_POST['last_name']));
            $middle_name = isset($_POST['middle_name']) ? mysqli_real_escape_string($conn, trim($_POST['middle_name'])) : '';
            $birthday = mysqli_real_escape_string($conn, trim($_POST['birthday']));
            $gender = mysqli_real_escape_string($conn, trim($_POST['gender']));
            $email = mysqli_real_escape_string($conn, trim($_POST['email']));   
            $age = mysqli_real_escape_string($conn, trim($_POST['age']));
            $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
            $is_admin = mysqli_real_escape_string($conn, trim($_POST['emptype']));
            $is_admin = ($is_admin === 'admin') ? 1 : 0;

            // Debugging step: Verify the connection and selected database
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            } else {
                echo "Connected successfully to database: " . $dbname . "<br>";
            }

            $sql = "SELECT admin_id FROM admins WHERE username='$username'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                echo '<script type="text/javascript">
                        alert("Username or email already exists.");
                        location="../manage_employees.php";
                        </script>';
            } else {
                $sql = "INSERT INTO admins (username, password, first_name, last_name, middle_name, birthday, gender, age, phone, email, is_admin)
                        VALUES ('$username', '$hashed_password', '$first_name', '$last_name', '$middle_name', '$birthday', '$gender', '$age', '$phone', '$email', $is_admin)";

                if (mysqli_query($conn, $sql)) {
                    echo '<script type="text/javascript">
                        alert("New User Registered Successfully");
                        location="../manage_employees.php";
                        </script>';
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            }
        }
    }
}

mysqli_close($conn);
?>
