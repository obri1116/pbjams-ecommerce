    <?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "client_db";


    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $client = array();

    // Fetch client data using the session's username
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM clients WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
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

    // Check if the new email is already registered
    $sql = "SELECT id FROM clients WHERE email=? AND id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $email, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists
        echo '<script type="text/javascript">
                alert("Email is already registered.");
                document.getElementById("email").setCustomValidity("This email is already in use.");
              </script>';
    } else {
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
            $hashed_password = !empty($new_password) ? password_hash($new_password, PASSWORD_DEFAULT) : $stored_password;

            $sql = "UPDATE clients SET 
            username=?, first_name=?, last_name=?, middle_name=?, address=?, gender=?, birthday=?, age=?, email=?, phone=?, password=?
            WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssssi", $username, $first_name, $last_name, $middle_name, $address, $gender, $birthday, $age, $email, $phone, $hashed_password, $id);

            if ($stmt->execute()) {
                echo '<script type="text/javascript">
                        alert("Record updated successfully");
                        location="index.php";
                      </script>';
            } else {
                echo "Error updating record: " . $conn->error;
            }

            $stmt->close();
        } else {
            echo '<script type="text/javascript">
                    alert("Incorrect current password");
                    location="edit_profile.php";
                  </script>';
        }
    }
}
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Profile</title>

        
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
  background-color: #f874a0;
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
.flex-item button {
  background-color: transparent;
  border-color: transparent;
  font-weight: bold;
  cursor: pointer;
}
.flex-item button:hover {
  color: #f874a0;
}

/* input {
    width: 150px;
    border-radius: 10px;
    padding: 5px;
    font-family: century gothic;
    border-color: #f874a0;
    background-color: #fff;
  } */

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
  border-color: #f874a0;
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
input[type="button"] {
  font-weight: 600;
  text-decoration: none;
  background-color: red;
  color: white;
  width: 100%;
  padding: 10px 25px;
  border: none;
  border-radius: 100px;
  cursor: pointer;
  margin: 10px auto;
  display: block;
  text-decoration: none;
  font-family: century gothic;
}
select {
  width: 100%;
  margin: 0 auto;
  padding: 7px;
  border-color: #f874a0;
  border-width: 1px;
  border-radius: 10px;
  /* background-color: white; */
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
  background-color: #4caf50;
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
.password-container {
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

.toggle-password-container {
  display: flex;
  justify-content: flex-end;
  align-items: center;
}

.toggle-password-container label {
  font-size: 12px;
}
.show-password-container {
  display: flex;
  align-items: center;
}

.show-password-container input[type="checkbox"] {
  margin-right: 5px;
}
.show-password-container {
  display: inline-flex;
  align-items: center;
}

.show-password-container input[type="checkbox"] {
  margin-right: 5px;
}
.cancel-btn {
  font-weight: 600;
  background-color: red;
  color: white;
  width: 100%;
  padding: 10px 25px;
  border: none;
  border-radius: 100px;
  cursor: pointer;
  margin: 10px auto;
  display: block;
  font-family: century gothic;
  text-align: center;
  text-decoration: none;
}

.a {
  text-decoration: none;
}

    </style>
    </head>

    <body>
    <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
        <section class="outer">
            <form action="" method="POST">
                <h2>EDIT PROFILE</h2>
                <input type="hidden" name="id" value="<?php echo isset($client['id']) ? $client['id'] : ''; ?>">

                <h3>PERSONAL INFORMATION</h3>
                <div class="flex-item">
                    <label for="first_name">First Name</label><br>
                    <input type="text" id="first_name" name="first_name" pattern="[a-zA-Z\s]+" oninput="validateAlphabets(this)" value="<?php echo isset($client['first_name']) ? $client['first_name'] : ''; ?>" required><br><br>
                </div>
                <div class="flex-item">
                    <label for="last_name">Last Name</label><br>
                    <input type="text" id="last_name" name="last_name" pattern="[a-zA-Z\s]+" oninput="validateAlphabets(this)" value="<?php echo isset($client['last_name']) ? $client['last_name'] : ''; ?>" required><br><br>
                </div>
                <div class="flex-item">
                    <label for="middle_name">Middle Name</label><br>
                    <input type="text" id="middle_name" name="middle_name" pattern="[a-zA-Z\s]+" oninput="validateAlphabets(this)" value="<?php echo isset($client['middle_name']) ? $client['middle_name'] : ''; ?>"><br><br>
                </div>
                <script>
                    function validateAlphabets(input) {
                        var regex = /^[a-zA-Z\s]+$/; // added \s to allow spaces
                        if (!regex.test(input.value)) {
                            input.value = input.value.replace(/[^a-zA-Z\s]/g, ''); // clear invalid characters
                        }
                    }
                </script>
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
                        <input type="date" id="birthday" name="birthday" value="<?php echo isset($client['birthday']) ? $client['birthday'] : ''; ?>" onkeydown="return false;" onchange="calculateAge();" required><br>

                    </div>
                    <div class="flex-item">
                        <label for="age"> Age</label><br>
                        <input type="text" id="age" name="age" value="<?php echo isset($client['age']) ? $client['age'] : ''; ?>" required readonly><br><br>
                    </div>
                </div>


                <div class="flex-container">
                    <div class="flex-item">
                        <label for="email">Email</label><br>
                        <input type="email" id="email" name="email" pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$" value="<?php echo isset($client['email']) ? $client['email'] : ''; ?>" required><br><br>
                    </div>
                    <div class="flex-item">
                        <label for="phone">Contact number</label><br>
                        <input type="text" id="phone" name="phone" maxlength="11" pattern="[0-9]{11}" oninput="validateIntegers(this)" value="<?php echo isset($client['phone']) ? $client['phone'] : ''; ?>"><br><br>
                    </div>
                    <script>
                        function validateIntegers(input) {
                            var regex = /^[0-9]+$/; // Only allow integers
                            if (!regex.test(input.value)) {
                                input.value = input.value.replace(/[^0-9]/g, ''); // Clear invalid characters
                            }
                        }
                    </script>
                </div>
                <hr>
                <h3>LOGIN INFORMATION</h3>

                <div class="flex-item">
                  <label for="username">Username</label><br>
                  <input readonly type="text" id="username" name="username" value="<?php echo $_SESSION['username']; ?>" required><br><br> 
                </div>

                <div class="flex-item">
                    <div class="toggle-password-container">
                        <label for="togglePasswordButton">Click to change password<br></label>
                        <button type="button" id="togglePasswordButton" onclick="togglePasswordFields()">Change Password</button>    
                    </div>
                    
                    <div id="newPasswordFields" style="display: none;">
                        <div class="password-container">
                            <label for="password">New Password</label><br>
                            <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">
                            <p style="font-size:smaller;">Password must be 8 characters, at least mixed with one uppercase, one symbol & numbers</p>
                            <input type="checkbox" id="show_password" onclick="showPassword('password')">
                            <label for="show_password">Show Password</label>
                        </div>
                        <div class="password-container">
                            <label for="confirm_password">Confirm New Password</label><br>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter new password">
                            <input type="checkbox" id="show_confirm_password" onclick="showPassword('confirm_password')">
                            <label for="show_confirm_password">Show Password</label>
                        </div>
                    </div>

                    <div class="password-container">
                        <label for="current_password">Input Password To Save Changes</label><br>
                        <input type="password" id="current_password" name="current_password" required>
                        <div class="show-password-container">
                            <input type="checkbox" id="show_current_password" onclick="showPassword('current_password')">
                            <label for="show_current_password">Show Password</label>    
                        </div>
                    </div>
                </div>
                <input type="submit" value="Update">
                <a href="index.php">
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
        function validatePassword() {
            var password = document.getElementById('password');
            var confirmPassword = document.getElementById('confirm_password');

            // Check conditions
            var hasUpperCase = /[A-Z]/.test(password.value);
            var hasLowerCase = /[a-z]/.test(password.value);
            var hasNumbers = /\d/.test(password.value);
            var hasSpecialChar = /[\W_]/.test(password.value);
            var hasMinLength = password.value.length >= 8;

            // Construct custom validity message
            if (!hasMinLength) {
                password.setCustomValidity('Password must be at least 8 characters long.');
            } else if (!hasUpperCase) {
                password.setCustomValidity('Password must include at least one uppercase letter.');
            } else if (!hasLowerCase) {
                password.setCustomValidity('Password must include at least one lowercase letter.');
            } else if (!hasNumbers) {
                password.setCustomValidity('Password must include at least one number.');
            } else if (!hasSpecialChar) {
                password.setCustomValidity('Password must include at least one symbol.');
            } else {
                password.setCustomValidity(''); // Clear custom validity if valid
            }

            // Check if confirm password matches
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Passwords do not match.');
            } else {
                confirmPassword.setCustomValidity(''); // Clear custom validity if valid
            }
        }

        function togglePasswordFields() {
            var newPasswordFields = document.getElementById('newPasswordFields');
            newPasswordFields.style.display = newPasswordFields.style.display === 'block' ? 'none' : 'block';
        }

        function showPassword(inputId) {
            var input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password'; // Toggle visibility
        }

        // Add listeners after the DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('togglePasswordButton').addEventListener('change', togglePasswordFields);
            document.getElementById('password').addEventListener('input', validatePassword);
            document.getElementById('confirm_password').addEventListener('input', validatePassword);
        });
         document.getElementById('show-password').addEventListener('change', function() {
            var passwordInput = document.getElementById('password');
            passwordInput.type = this.checked ? 'text' : 'password';
        });

        document.getElementById('show-confirm-password').addEventListener('change', function() {
            var confirmPasswordInput = document.getElementById('confirm_password');
            confirmPasswordInput.type = this.checked ? 'text' : 'password';
        });

        function calculateAge() {
            var birthday = document.getElementById('birthday').value;
            var birthDate = new Date(birthday);
            var today = new Date();
            var age = today.getFullYear() - birthDate.getFullYear();
            var monthDifference = today.getMonth() - birthDate.getMonth();

            if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            document.getElementById('age').value = age;

            // Check age restrictions
            if (age < 18 || age > 99) {
                alert("Age must be between 18 and 99 years.");
                document.getElementById('birthday').value = '';
                document.getElementById('age').value = '';
            }
        }

        function restrictDateRange() {
            var today = new Date();
            var minAge = 18;
            var maxAge = 99;

            var minDate = new Date(today.getFullYear() - maxAge, today.getMonth(), today.getDate());
            var maxDate = new Date(today.getFullYear() - minAge, today.getMonth(), today.getDate());

            var minDateString = minDate.toISOString().split('T')[0];
            var maxDateString = maxDate.toISOString().split('T')[0];

            document.getElementById('birthday').setAttribute('min', minDateString);
            document.getElementById('birthday').setAttribute('max', maxDateString);
        }

        restrictDateRange();
    </script>
    </body>

    </html>

    <?php $conn->close(); ?>