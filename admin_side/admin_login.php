<!DOCTYPE html>
<html lang="en">
<?php
session_start();

function relax() {
    // Function body if needed
}

// Check session for AdmOrEmp
if (isset($_SESSION['AdmOrEmp'])) {
    $adminstate = intval($_SESSION['AdmOrEmp']);
    
    // Ensure the user is an admin or employee and redirect accordingly
    if ($adminstate === 1) {
        header("Location: admin_dashboard.php");
        exit();
    } elseif ($adminstate === 0) {
        header("Location: employee_dashboard.php");
        exit();
    } else {
        // Invalid adminstate, relax
        relax();
    }
} else {
    // No AdmOrEmp session set, relax
    relax();
}

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

function authenticate($username, $password, $conn) {
    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT admin_id, password, is_admin FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                $_SESSION['admin_id'] = $row['admin_id']; // Get admin ID
                $_SESSION['admin_user'] = $username; // Store username in session
                $_SESSION['AdmOrEmp'] = $row['is_admin'];
                setcookie("adminuser", $username, time() + (15 * 86400)); // 15 days
                
                // Check if user is admin
                if ($row['is_admin'] == 1) {
                    // Redirect to admin dashboard
                    header('Location: admin_dashboard.php');
                } else {
                    // Redirect to employee dashboard
                    header('Location: employee_dashboard.php');
                }
                exit();
            } else {
                echo '<script type="text/javascript">
                        alert("Invalid password.");
                      </script>';
            }
        } else {
            echo '<script type="text/javascript">
                    alert("Username does not exist.");
                  </script>';
        }
    } else {
        echo '<script type="text/javascript">
                alert("Please enter username and password.");
              </script>';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['admin_user'] ?? '';
    $password = $_POST['admin_pass'] ?? '';
    authenticate($username, $password, $conn);
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <style>
        body {
            font-family: century gothic;
            padding-left: 100px;
            padding-top: 100px;
            padding-bottom: 100px;
            background-image: url(./assets/bg.png);
            background-size: cover;
            background-repeat: no-repeat;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        p {
            text-align: center;
        }

        hr {
            height: 1px;
            background-color: #333;
            border: #333;
        }

        .container {
            float: center;
        }

        form {
            max-width: 700px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 40px;
            padding-left: 55px;
            padding-right: 55px;
            float: left;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid #F874A0;
            border-radius: 10px;
            box-sizing: border-box;
            float: center;
            font-family: century gothic;
        }

        button[type="submit"] {
            width: 300px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 100px;
            cursor: pointer;
            float: center;
            text-align: center;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 4px;
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            color: #555;
        }

        #register {
            font-size: small;
            text-decoration: none;
            font-weight: bold;
        }


        p {
            margin-top: 30px;
            color: black;
        }

        .form_container {
            margin-left: 210px;
        }
        .session-info {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
        .welcome-message {
            font-size: 24px;
            font-weight: bold;
            color: #00698f; /* blue color */
            margin-left: 580px;
        }
        .access-count {
            font-size: 18px;
            color: #666; /* gray color */
        }

        .login-prompt{
            font-weight: bold;
            padding: 20px;
        }
        .nav_link{
            text-decoration: none;
            justify-content: center;
            color: #333;
            font-weight: bold;
            font-size: 13px;
            float: right;
        }
        .nav_link:hover{
            color:#F874A0;
        }
    </style>
</head>

<body>
<div class="form_container">
        <form action="admin_login.php" method="post">
            <h2>ADMIN LOGIN</h2>
            <hr><br><br>
            <label for="username">Username:</label>
            <input type="text" name="admin_user" id="admin_user" required><br><br>
            <label for="password">Password:</label>
            <input type="password" name="admin_pass" id="admin_pass" required><br>
            <a href="adminforget.php" class="nav_link">Forgot Password?</a><br><br>
            <button type="submit" class="btn btn-primary" name="login_btn">Login</button>
        </form>
        <div>
            <img src="./assets/pbjlogo.png">
        </div>
    </div>
</body>
</html>