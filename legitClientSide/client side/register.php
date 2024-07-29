    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Client Registration</title>
<script>
            function validateForm() {
                if (!document.getElementById('eula-agree').checked) {
                alert('You must agree to the terms and conditions to register.');
                return false;
                }
                var passwordInput = document.getElementById('password');
                var confirmPasswordInput = document.getElementById('con_pass');
                var password = passwordInput.value;
                var confirmPassword = confirmPasswordInput.value;

                // Clear any previous custom validity messages
                passwordInput.setCustomValidity('');
                confirmPasswordInput.setCustomValidity('');

                // Check if password is longer than 8 characters
                if (password.length < 8) {
                    passwordInput.setCustomValidity('Password must be 8 characters or longer.');
                    passwordInput.reportValidity();
                    return false;
                }

                var uppercasePattern = /[A-Z]/;
                var symbolPattern = /[!@#$%^&*(),.?":{}|<>]/;

                var missingUppercase = !uppercasePattern.test(password);
                var missingSymbol = !symbolPattern.test(password);

                // Check if password contains at least one uppercase letter and at least one symbol
                if (missingUppercase && missingSymbol) {
                    passwordInput.setCustomValidity('Password must contain at least one uppercase letter and one symbol.');
                    passwordInput.reportValidity();
                    return false;
                } else if (missingUppercase) {
                    passwordInput.setCustomValidity('Password must contain at least one uppercase letter.');
                    passwordInput.reportValidity();
                    return false;
                } else if (missingSymbol) {
                    passwordInput.setCustomValidity('Password must contain at least one symbol.');
                    passwordInput.reportValidity();
                    return false;
                }

                // Check if passwords match
                if (password !== confirmPassword) {
                    confirmPasswordInput.setCustomValidity('Passwords do not match.');
                    confirmPasswordInput.reportValidity();
                    return false;
                }

                // If all checks pass, return true
                return true;
            }

            function isValidDate(day, month, year) {
      var date = new Date(year, month - 1, day);
      return date.getDate() === day && date.getMonth() === month - 1 && date.getFullYear() === year;
    }



    </script>
    </head>
    <style>
        .modal-content textarea {
  width: 100%;
  height: 300px; /* adjust the height as needed */
  resize: none; /* remove the resize handle */
}
         #eula-modal {
        display: none; /* Add this line */
    }
            .toggle-password-container label {
              margin-left: -65px;
              font-size: 12px;
            }

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

            h3 {
                padding: 10px;
                text-align: left;
                color: #333;
                position: relative;
            }

            p {
                font-size: 12px;
            }

            hr {
                height: 2px;
                background-color: #F874A0;
                border: 0px;
            }

            input {
                width: 150px;
                border-radius: 10px;
                padding: 5px;
                font-family: century gothic;
                border-color: #F874A0;
                background-color: #fff;
            }

            form {
                max-width: 500px;
                max-height: 0 auto;
                margin: 0 auto;
                padding: 30px;
                padding-top: 10px;
                padding-bottom: 10px;
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
                position: relative;
            }

            .show-password-container {
                position: absolute;
                bottom: -20px; /* Adjust this value as needed */
                right: 0;
                display: flex;
                align-items: center;
            }

            select {
                width: 100%;
                margin: 0 auto;
                padding: 5px;
                border-color: #F874A0;
                border-width: 2px;
                border-radius: 10px;
                background-color: white;
                font-family: century gothic;
            }

            input[type="text"],
            input[type="password"],
            input[type="email"],
            input[type="gender"] {
                padding: 5px;
                width: 100%;
                margin-bottom: 10px;
                border-width: 2px;
                border-radius: 10px;
                box-sizing: border-box;
                font-size: century gothic;
                border-color: #F874A0;
            }

            input[id="email"] {
                width: 315px;
                font-family: century gothic;
            }

            input[type="date"] {
                width: 95%;
                padding: 4px;
                border-radius: 10px;
                background-color: white;
                font-size: century gothic;
            }

            input[type="checkbox"] {
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

            input[type="reset"] {
                float: center;
                font-weight: 600;
                text-decoration: underline;
                color: #333;
                padding: 10px;
                border: none;
                background-color: transparent;
                cursor: pointer;
                margin: 10px auto;
                display: block;
                text-align: center;
            }

            input[type="reset"]:hover {
                color: #F874A0;
            }

            .logo {
                margin-top: 90px;
                margin-right: 90px;
                margin-left: 90px;
            }

            span {
                color: red;
            }
            input[type="date"].readonly::-webkit-calendar-picker-indicator {
                display: block;
            }

        
        </style>
    </head>
    <body>
        
        <section class="outer">
            <div class="container">
                <form action="./process_registration.php" method="POST" onsubmit="return validateForm()">
                    <h2>REGISTRATION FORM</h2>
                    <h3>PERSONAL INFORMATION</h3>

                    <div class="flex-item">
                        <label for="first_name"><span>&#42;</span> First Name</label><br>
                        <input type="text" id="first_name" name="first_name" required placeholder="Juan" pattern="[a-zA-Z\s]+" oninput="validateAlphabets(this)">
                    </div>
                    <div class="flex-item">
                        <label for="last_name"><span>&#42;</span> Last Name</label>
                        <input type="text" id="last_name" name="last_name" required placeholder="Dela cruz" pattern="[a-zA-Z\s]+" oninput="validateAlphabets(this)">
                    </div>
                    <div class="flex-item">
                        <label for="middle_name">Middle Name</label><br>
                        <input type="text" id="middle_name" name="middle_name" placeholder="Santos"pattern="[a-zA-Z\s]+" oninput="validateAlphabets(this)">
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
                        <label for="address"><span>&#42;</span> Home Address</label><br>
                        <input type="text" id="address" name="address" required placeholder="Lot #, Block #, street, Barangay, city/province">
                    </div>

                    <div class="flex-container">
                        <div class="flex-item">
                            <label for="gender"><span>&#42;</span> Gender:</label><br>
                            <select id="gender" name="gender" required>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="others">Others</option>
                            </select>
                        </div>
                        <div class="flex-item">
                            <label for="birthday"><span>&#42;</span> Birthday</label><br>
                            <input type="date" id="birthday" name="birthday" onkeydown="return false;" onchange="calculateAge();" required>
                        </div>
                        <div class="flex-item">
                            <label for="age"><span>&#42;</span> Age</label><br>
                            <input type="text" id="age" name="age" readonly>
                        </div>
                    </div>

                    <div class="flex-container">
                        <div class="flex-item">
                            <label for="email"><span>&#42;</span> Email</label><br>
                            <input type="email" id="email" name="email" pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$" required placeholder="juan@gmail.com"><br><br>
                        </div>
                        <div class="flex-item">
                            <label for="phone"><span>&#42;</span> Contact number</label><br>
                            <input type="text" id="phone" name="phone" required placeholder="09123456789" oninput="validatePhoneNumber(this)" maxlength="11"><br><br>
                        </div>
                    </div>
                    <hr>
                    <div class="flex-item">
                        <h3>SIGN UP</h3>
                        <label for="username"><span>&#42;</span> Username</label><br>
                        <input type="text" id="username" name="username" required placeholder="juan_DC" maxlength="15"><br>                    </div>
                    <div class="flex-item">
                    <p style="margin-top: -5px;">Username maximum of 15 characters only!</p>
                        <label for="password"><span>&#42; </span>Password:</label><br>
                        <input type="password" id="password" name="password" required placeholder="Use a strong password">
                        <div class="show-password-container">
                            <input type="checkbox" id="show_password" onclick="showPassword()">
                            <label for="show_password" style="margin-left: -65px;"><p style="margin-top: 10px;">Show Password</p></label>
                        </div>
                        <p style="margin-top: -5px;">Password must be 8 characters, at least mixed with one uppercase, one symbol & numbers</p>
                    </div>
                    <div class="flex-item">
                        <label for="con_pass"><span>&#42;</span> Confirm password</label><br>
                        <input type="password" id="con_pass" name="con_pass" required placeholder="re-type password">
                        <div class="show-password-container">
                            <input type="checkbox" id="show_con_pass" onclick="showConfirmPassword()">
                            <label for="show_con_pass" style="margin-left: -65px;">Show Password</label>
                        </div>
                    </div>
                    <br>
                    <input type="submit" value="Register" onclick="openEulaModal();">
                    <input type="reset" value="Clear">
                </form>
                <div id="eula-modal" class="modal">
        <div class="modal-content">
            <h2>End User License Agreement</h2>
            <p>Please read and agree to our terms and conditions before registering.</p>
            <textarea readonly>
            Thank you for choosing PersonalizedByJams! This End User License Agreement ("EULA") outlines the terms and conditions governing your use of our personalized goods and services ("Products"). By placing an order, using, or accessing any Products, you agree to be bound by this EULA. If you disagree with any of these terms, please do not use our Products.

1. Products and Services

PersonalizedByJams offers a variety of customizable goods ("Goods") that you can personalize with your designs, text, or images. We may also offer additional services related to personalization, such as design assistance or image editing.

2. User Content

You are solely responsible for all content ("User Content") that you upload, submit, or create using our Products. This includes, but is not limited to, text, images, designs, and logos. You represent and warrant that you have all necessary rights and permissions to use, reproduce, and modify User Content and grant the licenses set forth herein.

3. License Grant

By uploading User Content, you grant PersonalizedByJams a non-exclusive, worldwide, royalty-free license to use, reproduce, modify, publish, and distribute your User Content solely for the purpose of fulfilling your order and creating your personalized Goods.

4. Ownership

PersonalizedByJams retains all ownership rights in the Products and the underlying technology. You acquire no ownership rights in the Products by using them.

5. User Conduct

You agree not to use our Products for any illegal or unauthorized purpose. This includes, but is not limited to:

Uploading User Content that is infringing, obscene, defamatory, or hateful.
Violating any third-party rights.
Interfering with or disrupting the Products or our servers.
6. Disclaimer of Warranties

The Products are provided "as is" and without warranties of any kind, express or implied. PersonalizedByJams disclaims all warranties, including, but not limited to, warranties of merchantability, fitness for a particular purpose, and non-infringement.

7. Limitation of Liability

PersonalizedByJams will not be liable for any damages arising out of or related to your use of the Products. This includes, but is not limited to, direct, indirect, incidental, consequential, and punitive damages.

8. Term and Termination

This EULA will remain in effect until terminated by either party. We may terminate this EULA at any time for any reason, with or without notice. You may terminate this EULA by ceasing to use the Products.

9. Governing Law

This EULA shall be governed by and construed in accordance with the laws of [insert jurisdiction where PersonalizedByJams is located].

10. Entire Agreement

This EULA constitutes the entire agreement between you and PersonalizedByJams regarding the use of the Products and supersedes all prior or contemporaneous communications and proposals, whether oral or written.

11. Updates

PersonalizedByJams may update this EULA from time to time. We will notify you of any changes by posting the new EULA on our website. You are responsible for periodically reviewing the EULA to stay informed of updates. Your continued use of the Products after the posting of the revised EULA constitutes your acceptance of the changes.

12. Contact Us

If you have any questions about this EULA, please contact us at [insert contact information for PersonalizedByJams].

Thank you for using PersonalizedByJams!
            </textarea>
            <br>
            <input type="checkbox" id="eula-agree" required>
            <label for="eula-agree">I agree to the terms and conditions.</label>
            <br>
            <button id="eula-accept">Accept</button>
            <button id="eula-decline">Decline</button>
        </div>
        </div>
    </div>
    </div>
            </div>
        </section>
            <script>
var eulaModal = document.getElementById('eula-modal');

document.getElementById('register-button').addEventListener('click', function() {
    eulaModal.style.display = 'block';
});
  var eulaAccept = document.getElementById('eula-accept');
  var eulaDecline = document.getElementById('eula-decline');

  function openEulaModal() {
    eulaModal.style.display = 'block';
  }

  eulaAccept.addEventListener('click', function() {
    if (document.getElementById('eula-agree').checked) {
      eulaModal.style.display = 'none';
      validateForm();
    } else {
      alert('You must agree to the terms and conditions to register.');
    }
  });

  eulaDecline.addEventListener('click', function() {
    eulaModal.style.display = 'none';
    alert('You must agree to the terms and conditions to register.');
  });

  window.addEventListener('click', function(event) {
    if (event.target === eulaModal) {
      eulaModal.style.display = 'none';
    }
  });
                function validatePhoneNumber(input) {
                var regex = /^[0-9]{1,11}$/; // allow only numbers, max 11 digits
                if (!regex.test(input.value)) {
                    input.value = input.value.replace(/[^0-9]/g, ''); // clear invalid characters
                }
            }
            function preventInput(event) {
                event.preventDefault();
            }

            function initDateInput() {
                var input = document.getElementById('birthday');
                input.addEventListener('keydown', preventInput);
                input.addEventListener('paste', preventInput);
            }

            document.addEventListener('DOMContentLoaded', initDateInput);     
            function showPassword() {
                var x = document.getElementById("password");
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";        
                }
            }
            function showConfirmPassword() {
                var x = document.getElementById("con_pass");
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
            }
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
        </section>
    </body>
