<?php

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $token = $_GET["token"] ?? null;

    if (!$token) {
        die("Token not found");
    }

    $token_hash = hash("sha256", $token);

    // Db Connection
    $conn = mysqli_connect("localhost", 'root', '', 'client_db');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM clients WHERE hash_lostpass = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token_hash);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user === null) {
        die("Token not found");
    }

    if (strtotime($user["lostpass_expiry"]) <= time()) {
        die("Token has expired");
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["token"] ?? null;
    $password = $_POST["password"] ?? null;
    $password_confirmation = $_POST["password_confirmation"] ?? null;

    if (!$token) {
        die("Token not found");
    }

    $token_hash = hash("sha256", $token);
    $conn = mysqli_connect("localhost", 'root', '', 'client_db');
    $sql = "SELECT * FROM clients WHERE hash_lostpass = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token_hash);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user === null) {
        die("Token not found");
    }

    if (strtotime($user["lostpass_expiry"]) <= time()) {
        die("Token has expired");
    }

    if (strlen($password) < 8) {
        die("Password must be at least 8 characters");
    }

    if (!preg_match("/[a-z]/i", $password)) {
        die("Password must contain at least one letter");
    }

    if (!preg_match("/[0-9]/", $password)) {
        die("Password must contain at least one number");
    }

    if ($password !== $password_confirmation) {
        die("Passwords must match");
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE clients SET password = ?, hash_lostpass = NULL, lostpass_expiry = NULL WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $password_hash, $user["id"]);
    $stmt->execute();

    $stmt->close();
    $conn->close();
    echo '<script type="text/javascript">
                            alert("Password Successfully reset");
                            location="login.php";
                            </script>';
} else {
    die("Invalid request method");
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
    <script type="text/javascript"
    src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js">
</script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="./css/forgetpass.css">
<link rel="stylesheet" href="css/nav.css">

    <title>RESET PASSWORD</title>
</head>
<body style="background-image:url('./assets/bg.png');">
<!-- Main Post Section Start -->

<div class="box" style="width: 30%;">
<?php if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($user)): ?>
    <h1>Reset Password</h1>
    <small>Password must be 8 characters, at least mixed with one uppercase, one symbol & numbers</small>
    <hr>
    <form method="post" action="">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <label for="password">New password</label><br>
        <input type="password" id="password" name="password" 
            style="width: 100%;
            padding: 5px;
            margin-bottom: 15px;
            border: 2px solid #F874A0;
            border-radius: 10px;
            box-sizing: border-box;
            float: center;
            font-family: century gothic;"><br>
        <label for="password_confirmation">Repeat password</label><br>
        <input type="password" id="password_confirmation" name="password_confirmation" style="width: 100%;
            padding: 5px;
            margin-bottom: 15px;
            border: 2px solid #F874A0;
            border-radius: 10px;
            box-sizing: border-box;
            float: center;
            font-family: century gothic;"><br>
        <button style=" width: 100%;
            background-color: #4CAF50;
            color: white;
            margin-bottom:10px;
            padding: 5px 5px;
            border: none;
            border-radius: 100px;
            cursor: pointer;
            float: center;
            text-align: center;">Send</button>
    </form>
<?php endif; ?>
</div>


  
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