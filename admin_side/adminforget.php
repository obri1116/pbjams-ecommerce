
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/CIP-1101 FINAL PROJECT-20240522T032656Z-001/CIP-1101 FINAL PROJECT/nav.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <script type="text/javascript"
    src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js">
</script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/forgetpass.css">
    <title>FORGET PASSWORD</title>
</head>
<body style="background-image:url('./assets/bg.png');">

<!-- Main Post Section Start -->

<div class="box"  style="width: 30%;">
        <p>Personalised by Jams</p>
        <h1>Forgot Password?</h1>
        <hr>
        <div class="form-group">
            <form method="POST" action="">
                <p><label for="username" style="color:#333; padding-top: 15px">Enter your account's registered email</label></p>
                <input type="email" name="email" id="email" style="width: 100%;
            padding: 5px;
            margin-bottom: 15px;
            border: 2px solid #F874A0;
            border-radius: 10px;
            box-sizing: border-box;
            float: center;
            font-family: century gothic;">
                <button type="submit" style=" width: 100%;
            background-color: #4CAF50;
            color: white;
            margin-bottom:10px;
            padding: 5px 5px;
            border: none;
            border-radius: 100px;
            cursor: pointer;
            float: center;
            text-align: center;">Reset Password</button><br>
            </form>
        </div>
        <div class="footer">
            <h5>New here? <a href="register.php"><span><small class ="small">Sign Up</small></span> </a></h5>
            <h5>Already have an account? <a href="login.php"><span><small class ="small">Login</small></span></a></h5>
        </div>
    </div>
<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require 'phpmailer/src/Exception.php';
  require 'phpmailer/src/PHPMailer.php';
  require 'phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {

    $email = $_POST["email"];
    $token = bin2hex(random_bytes(16));
    $token_hash = hash("sha256", $token);
    $expiry = date("Y-m-d H:i:s", time() + 60 * 60); //ONE HOUR

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

    $sql = "UPDATE admins
            SET hash_lostpass = ?,
                lostpass_expiry = ?
            WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $token_hash, $expiry, $email);
    $stmt->execute();

    $user = array();
    $query = "SELECT first_name, last_name FROM Admins WHERE email = ?";
    $prep_stmt = $conn->prepare($query);
    $prep_stmt->bind_param("s", $email); // Assuming $email is the email you are querying for
    $prep_stmt->execute();
    $result = $prep_stmt->get_result();
    $row = $result->fetch_assoc();
    $full_name = $row['first_name'] . ' ' . $row['last_name'];

    if ($stmt->affected_rows > 0) {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        // Set your own email address and app password
        $mail->Username = 'justinpritzdcorpuz@gmail.com';
        //app password -> generate code 
        $mail->Password = 'kbrbewrdfsygdmfu';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('justinpritzdcorpuz@gmail.com');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';
        // FIX WEBSITE LOCATION AS SOON AS WEBSITE IS PUBLISHED
        $mail->Body = "<p>Dear <strong>" . htmlspecialchars($full_name) . "</strong>,</p>" .
              "<p>We received a request to reset your password for your account associated with this email address. If you did not make this request, you can ignore this email.</p>" .
              "<p>To reset your password, please click the link below:</p>" .
              "<p><a href='http://localhost/works/primary/admin_side/adminreset.php?token=" . urlencode($token) . "'>Reset Your Password</a></p>" .
              "<p>This link will expire in an hour for your security. If you do not reset your password within this time frame, you will need to request a new password reset.</p>" .
              "<p>If you have any questions or need further assistance, please do not hesitate to contact our support team.</p>" .
              "<p>Thank you for your attention to this matter.</p>" .
              "<p>Best regards,</p>" .
              "<p><strong>Personalised By Jams</strong><br>" .
              "+91 987 654 3210<br>" .
              "Glenroy, Melbourne Australia<br>" .
              "<a href='https://www.yourwebsite.com'>Visit Our Website Here</a><br>" .
              "<strong>THIS IS AN AUTOMATED MESSAGE. PLEASE DO NOT REPLY.</strong></p>";

        try {
            $mail->send();
            echo '<script type="text/javascript">
                    alert("Message sent, kindly check your email inbox");
                    window.location.href = "admin_login.php";
                  </script>';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
        }
    } else {
        echo '<script type="text/javascript">
                            alert("Invalid Email, Account not found in database.");
                            </script>';
    }
    $stmt->close();
    $conn->close();
}
?>
  
    <!-- script src start-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <!-- Bootstrap and EmailJS scripts -->
<!-- Include Bootstrap JS and EmailJS -->
<script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

<script src="../script.js"></script>
    <!-- script src end -->

   

</body>
</html>