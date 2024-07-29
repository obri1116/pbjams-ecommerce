<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "client_db";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$client = array();

// Fetch client data if ID is provided
if (isset($_GET['admin_id'])) {
    $id = intval($_GET['admin_id']);
    $sql = "SELECT * FROM admins WHERE admin_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $client = $result->fetch_assoc();
    $stmt->close();
}

// Update client data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $username = $conn->real_escape_string(trim($_POST['username']));
    $first_name = $conn->real_escape_string(trim($_POST['first_name']));
    $last_name = $conn->real_escape_string(trim($_POST['last_name']));
    $middle_name = $conn->real_escape_string(trim($_POST['middle_name']));
    $address = $conn->real_escape_string(trim($_POST['address']));
    $gender = $conn->real_escape_string(trim($_POST['gender']));
    $birthday = $conn->real_escape_string(trim($_POST['birthday']));
    $age = intval($_POST['age']);
    $email = $conn->real_escape_string(trim($_POST['email']));
    $phone = $conn->real_escape_string(trim($_POST['phone']));
    $current_password = $conn->real_escape_string(trim($_POST['current_password']));
    $new_password = $conn->real_escape_string(trim($_POST['password']));

    // Fetch the stored password
    $sql = "SELECT password FROM clients WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stored_password = $result->fetch_assoc()['password'];
    $stmt->close();

    // Verify the current password
    if (password_verify($current_password, $stored_password)) {
        // Update the password only if a new password is provided
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        } else {
            $hashed_password = $stored_password;
        }


        $sql = "UPDATE clients SET 
        username=?, first_name=?, last_name=?, middle_name=?, address=?, gender=?, birthday=?, age=?, email=?, phone=?, password=?
        WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssssi", $username, $first_name, $last_name, $middle_name, $address, $gender, $birthday, $age, $email, $phone, $hashed_password, $id);

        if ($stmt->execute()) {
            echo '<script type="text/javascript">
                    alert("Record updated successfully");
                    location="manage_clients.php";
                    </script>';
        } else {
            echo "Error updating record: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo '<script type="text/javascript">
        alert("Incorrect current password");
        location="update_client.php";
        </script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Client</title>

    <style>
        body {
            background-image: url(./assets/bg.png);
            background-size: cover;
            background-repeat: no-repeat;
            font-family: century gothic;
        }

        .outer {
            padding-top: 50px;
            padding-bottom: 50px;
        }

        h2 {
            text-align: center;
            background-color: rgba(248, 116, 160, 0.4);
            padding: 10px;
            padding-left: 20px;
            border-radius: 15px 15px 0px 0px;
            color: #333;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            padding-top: 20px;
        }

        hr {
            height: 2px;
            background-color: #F874A0;
            border: 0px;
        }

        h3 {
            padding: 10px;
            text-align: left;
            color: #333;
            position: relative;
        }

        form {
            max-width: 500px;
            max-height: 0 auto;
            margin: 0 auto;
            padding: 30px;
            padding-top: 20px;
            padding-bottom: 20px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            position: relative;
            float: center;
            background-color: rgba(255, 255, 255, 0.2);

        }

        form::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            filter: blur(4px);
            z-index: -1;
        }

        label {
            font-size: 12px;
            font-weight: 550;
        }

        .flex-container {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;


        }

        .flex-item {
            flex-basis: 30%;
            margin: 5px;
            align-items: center;
        }

        input {
            width: 150px;
            border-radius: 10px;
            padding: 5px;
            font-family: century gothic;
            border-color: #F874A0;
            background-color: #fff;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="age"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 7px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 10px;
            border-color: #F874A0;
            font-family: century gothic;
            font-size: 15px;

        }

        input[id="email"] {
            width: 300px;
            font-family: century gothic;
        }

        input[id="phone"] {
            width: 127px;
            font-family: century gothic;
        }

        select {
            width: 100%;
            margin: 0 auto;
            padding: 7px;
            border-color: #F874A0;
            border-width: 1px;
            border-radius: 10px;
            background-color: white;
            font-family: century gothic;
        }

        .password-container {
            display: flex;
            flex-direction: column;
            position: relative;
            width: 100%;
        }

        .password-container input[type="password"] {
            margin-bottom: 5px;
        }

        .show-password-container {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 10px;
        }

        .show-password-container label {
            margin-left: 5px;
            font-size: 12px;
        }

        input[type="submit"] {
            font-weight: 600;
            background-color: #4CAF50;
            color: white;
            width: 100%;
            padding: 10px 25px;
            border: none;
            border-radius: 100px;
            cursor: pointer;
            margin: 10px auto;
            display: block;
            font-family: century gothic;

        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        

        p {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <section class="outer">
        <form action="update_client.php" method="POST">
            <h2>UPDATE ACCOUNT</h2>
            <input type="hidden" name="id" value="<?php echo isset($client['id']) ? $client['id'] : ''; ?>">

            <h3>PERSONAL INFORMATION</h3>
            <div class="flex-item">
                <label for="first_name">First Name</label><br>
                <input type="text" id="first_name" name="first_name" value="<?php echo isset($client['first_name']) ? $client['first_name'] : ''; ?>" required><br><br>
            </div>
            <div class="flex-item">
                <label for="last_name">Last Name</label><br>
                <input type="text" id="last_name" name="last_name" value="<?php echo isset($client['last_name']) ? $client['last_name'] : ''; ?>" required><br><br>
            </div>
            <div class="flex-item">
                <label for="middle_name">Middle Name</label><br>
                <input type="text" id="middle_name" name="middle_name" value="<?php echo isset($client['middle_name']) ? $client['middle_name'] : ''; ?>"><br><br>
            </div>
            <div class="flex-item">
                <label for="address">Home Address</label><br>
                <input type="text" id="address" name="address" value="<?php echo isset($client['address']) ? $client['address'] : ''; ?>" required><br><br>
            </div>
            <!-- GENDER
    BIRTHDAY
    AGE -->
            <div class="flex-container">
                <div class="flex-item">
                    <label for="gender">Gender</label><br>
                    <select id="gender" name="gender">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="others">Others</option>
                    </select>
                </div>
                <div class="flex-item">
                    <label for="birthday"> Birthday</label><br>
                    <input type="text" id="birthday" name="birthday" value="<?php echo isset($client['birthday']) ? $client['birthday'] : ''; ?>" required><br><br>

                </div>
                <div class="flex-item">
                    <label for="age"> Age</label><br>
                    <input type="age" id="age" name="age" value="<?php echo isset($client['age']) ? $client['age'] : ''; ?>" required><br><br>
                </div>
            </div>


            <div class="flex-container">
                <div class="flex-item">
                    <label for="email">Email</label><br>
                    <input type="email" id="email" name="email" value="<?php echo isset($client['email']) ? $client['email'] : ''; ?>" required><br><br>
                </div>
                <div class="flex-item">
                    <label for="phone">Contact number</label><br>
                    <input type="text" id="phone" name="phone" value="<?php echo isset($client['phone']) ? $client['phone'] : ''; ?>"><br><br>
                </div>
            </div>
            <hr>
            <h3>LOGIN INFORMATION</h3>

            <div class="flex-item">
                <label for="username">Username</label><br>
                <input type="text" id="username" name="username" value="<?php echo isset($client['username']) ? $client['username'] : ''; ?>" required><br><br>
            </div>
            <div class="flex-item">
                <label for="current_password">Current Password</label><p style="margin-top: 5px;margin-bottom:-10px;">Password must be 8 characters, at least mixed with one uppercase, one symbol & numbers</p><br>
                <div class="password-container">
                    <input type="password" id="current_password" name="current_password" required>
                    <div class="show-password-container">
                        <input type="checkbox" id="show_current_password" onclick="showPassword('current_password')">
                        <label for="show_password" style="margin-left: -65px;"><p style="margin-top: 10px;">Show Password</p></label>
                    </div>
                </div>
            </div>
            <div class="flex-item">
                <label for="password">New Password</label><br>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">
                    <div class="show-password-container">
                        <input type="checkbox" id="show_password" onclick="showPassword('password')">
                        <label for="show_password" style="margin-left: -65px;"><p style="margin-top: 10px;">Show Password</p></label>
                    </div>
                </div>
            </div>
            <input type="submit" value="Update">
            <a href="./manage_clients.php">
                <button type="button" class="btn btn-danger" style="font-weight: 600; 
            text-decoration:none;
            background-color: red;
            color: white;
            width: 100%;
            padding: 10px 25px;
            border: none;
            border-radius: 100px;
            cursor: pointer;
            margin: 10px auto;
            display: block;
            font-family: century gothic; ">Cancel</button></a>
        </form>
    </section>
        <script>
    function showPassword(id) {
        var x = document.getElementById(id);
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>
    </section>
</body>

</html>

<?php $conn->close(); ?>