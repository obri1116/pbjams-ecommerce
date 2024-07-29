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

    $admin = array();

    // Fetch client data using the session's username
    if (isset($_SESSION['admin_user'])) {
        $username = $_SESSION['admin_user'];
        $sql = "SELECT * FROM admins WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        $stmt->close();
    }

    // Update client data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $username = $conn->real_escape_string(trim($_POST['username']));
    $first_name = $conn->real_escape_string(trim($_POST['first_name']));
    $last_name = $conn->real_escape_string(trim($_POST['last_name']));
    $middle_name = $conn->real_escape_string(trim($_POST['middle_name']));
    $gender = $conn->real_escape_string(trim($_POST['gender']));
    $birthday = $conn->real_escape_string(trim($_POST['birthday']));
    $age = intval($_POST['age']);
    $email = $conn->real_escape_string(trim($_POST['email']));          
    $phone = $conn->real_escape_string(trim($_POST['phone']));
    $current_password = $conn->real_escape_string(trim($_POST['current_password']));
    $new_password = $conn->real_escape_string(trim($_POST['password']));

    // Check if the new email is already registered
    $sql = "SELECT id FROM admins WHERE email=? AND admin_id != ?";
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
        $sql = "SELECT password FROM admins WHERE admin_id=?";
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

            $sql = "UPDATE admins SET 
            username=?, first_name=?, last_name=?, middle_name=?, address=?, gender=?, birthday=?, age=?, phone=?, password=?
            WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssssi", $username, $first_name, $last_name, $middle_name, $address, $gender, $birthday, $age, $phone, $hashed_password, $id);

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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <title>Edit Account</title>
        <style>
            <?php  include './css/admin_edit_profile.css'; ?>
        </style>

      
    </head>

    <body>
    
    <h2>Welcome, <?php echo $_SESSION['admin_user']; ?></h2>
        <section class="outer">
            <form action="" method="POST">
                <h2>EDIT ACCOUNT</h2>
                <input type="hidden" name="id" value="<?php echo isset($admin['admin_id']) ? $admin['admin_id'] : ''; ?>">

                <h3>PERSONAL INFORMATION</h3>
                <div class="flex-item">
                    <label for="first_name">First Name</label><br>
                    <input type="text" id="first_name" name="first_name" pattern="[a-zA-Z\s]+" oninput="validateAlphabets(this)" value="<?php echo isset($admin['first_name']) ? $admin['first_name'] : ''; ?>" required><br><br>
                </div>
                <div class="flex-item">
                    <label for="last_name">Last Name</label><br>
                    <input type="text" id="last_name" name="last_name" pattern="[a-zA-Z\s]+" oninput="validateAlphabets(this)" value="<?php echo isset($admin['last_name']) ? $admin['last_name'] : ''; ?>" required><br><br>
                </div>
                <div class="flex-item">
                    <label for="middle_name">Middle Name</label><br>
                    <input type="text" id="middle_name" name="middle_name" pattern="[a-zA-Z\s]+" oninput="validateAlphabets(this)" value="<?php echo isset($admin['middle_name']) ? $admin['middle_name'] : ''; ?>"><br><br>
                </div>
                <script>
                    function validateAlphabets(input) {
                        var regex = /^[a-zA-Z\s]+$/; // added \s to allow spaces
                        if (!regex.test(input.value)) {
                            input.value = input.value.replace(/[^a-zA-Z\s]/g, ''); // clear invalid characters
                        }
                    }
                </script>
               
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
                        <input type="date" id="birthday" name="birthday" value="<?php echo isset($admin['birthday']) ? $admin['birthday'] : ''; ?>" onkeydown="return false;" onchange="calculateAge();" required><br>

                    </div>
                    <div class="flex-item">
                        <label for="age"> Age</label><br>
                        <input type="text" id="age" name="age" value="<?php echo isset($admin['age']) ? $admin['age'] : ''; ?>" required readonly><br><br>
                    </div>
                </div>


                <div class="flex-container">
                    <div class="flex-item">
                        <label for="email">Email</label><br>
                        <input type="email" id="email" name="email" pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$" value="<?php echo isset($admin['email']) ? $admin['email'] : ''; ?>" required><br><br>
                    </div>
                    <div class="flex-item">
                        <label for="phone">Contact number</label><br>
                        <input type="text" id="phone" name="phone" maxlength="11" pattern="[0-9]{11}" oninput="validateIntegers(this)" value="<?php echo isset($admin['phone']) ? $admin['phone'] : ''; ?>"><br><br>
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
                  <input readonly type="text" id="username" name="username" value="<?php echo $_SESSION['admin_user']; ?>" required><br><br> 
                </div>

                <div class="flex-item">
                    <div class="toggle-password-container">
                        <label for="togglePasswordButton"><br></label>
                        <button type="button" id="togglePasswordButton" onclick="togglePasswordFields()"><i class="bi bi-lock"></i> Change Password</button>    
                    </div>
                    
                    <div id="newPasswordFields" style="display: none;">
    <div class="password-container">
        <label for="password">New Password</label><br>
        <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">
        <p style="font-size: smaller;">Password must be 8 characters, at least mixed with one uppercase, one symbol & numbers</p>
        <div class="show-password-container">
            <input type="checkbox" id="show_password" onclick="showPassword('password')">
            <label for="show_password">Show Password</label>
        </div>
    </div>
    <div class="password-container">
        <label for="confirm_password">Confirm New Password</label><br>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter new password">
        <div class="show-password-container">
            <input type="checkbox" id="show_confirm_password" onclick="showPassword('confirm_password')">
            <label for="show_confirm_password">Show Password</label>
        </div>
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
                        
                <input type="submit" value="Update">
                <a href="index.php" class= "a">
                <button type="button" class="cancel-btn" value="Cancel" style="background-color: red;">Cancel</button></a>
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
    <script>
        function showPassword(fieldId) {
            var field = document.getElementById(fieldId);
            if (field.type === "password") {
                field.type = "text";
            } else {
                field.type = "password";
            }
        }
    </script>
    </body>

    </html>

    <?php $conn->close(); ?>