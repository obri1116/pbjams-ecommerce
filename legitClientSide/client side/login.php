<!DOCTYPE html>
<html lang="en">
<?php
// login.php

// Check if the user has submitted the login form
session_start();

if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

if (isset($_COOKIE['username']) && isset($_SESSION['access_count'])) {
    echo "<span class='welcome-message'>Welcome, " . $_COOKIE['first_name'] . " " . $_COOKIE['username'] . "!<br></span>";
}
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $conn = mysqli_connect('localhost', 'root', '', 'client_db');

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM clients WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

     if (!empty($username) && !empty($password)) 
        {
            if (mysqli_num_rows($result) > 0) 
            {
                $row = mysqli_fetch_assoc($result);
                if (password_verify($password, $row['password'])) {
                    // Set a cookie to store the username
                    $_SESSION['username'] = $_POST['username'];
                    setcookie("username", $username, time() + (15 * 86400)); // 15 days
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['first_name'] = $row['first_name'];
                    $_SESSION['full_name'] = $row['first_name'] . ' ' .  $row['last_name'];
                    setcookie("first_name", $row['first_name'], time() + (15 * 86400)); // 15 days
                
                    echo '<script type="text/javascript">
                            alert("Access Granted");
                            location="index.php";
                            </script>';
                    header('Location: index.php');
                    exit;
                    // Start a new session
                  

                    // Set a session variable to count the number of page refreshes
                    if (!isset($_SESSION['access_count'])) {
                        $_SESSION['access_count'] = 0;
                    } else {
                        $_SESSION['access_count']++;
                             // Display session information
                             

                    }
                    if (!isset($_SESSION['start_time'])) {
                        $_SESSION['start_time'] = time();
                    }
                    ?>
                    
                    <div class="session-info">
                        Session ID: <?= session_id() ?><br>
                        Creation Time: <?= date("Y-m-d H:i:s", $_SESSION['start_time']) ?><br>
                        Time of Last Access: <?= date("Y-m-d H:i:s") ?><br>
                        Number of Previous Accesses: <?= $_SESSION['access_count'] ?><br>
                    </div>
                    <?php
                } 
                else 
                {
                    echo '<script>alert("Incorrect password")</script>';
                }
            } 
            else 
            {
                echo '<script>alert("Incorrect username")</script>';
            }

            mysqli_close($conn);
        }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

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

        #admin {
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

        .forgot-password {
            text-align: right;
            display: block;
            font-size: small;
            text-decoration: none;
            margin-top: -10px;
            margin-bottom: 15px;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="form_container">
        <form action="login.php" method="post">
            <h2>LOGIN</h2>
            <hr><br><br>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required><br><br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <a href="forget.php" name="forgot" id="forgot" class="forgot-password">Forgot password ?</a>

            <button type="submit">Login</button><br>

            <a href="register.php" name="register" id="register">
                <p>Do not have account yet? <span style="color: #F874A0;">Register now!</span></p>
            </a>
            <a href="../admin side/admin_login.php" id="admin">
                <p>Login as <span style="color: #F874A0;"> Admin</span></p>
            </a>
        </form>

        <div>
            <img src="./assets/pbjlogo.png">
        </div>
    </div>
</body>

</html>
