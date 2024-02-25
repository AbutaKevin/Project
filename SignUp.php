<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to top,rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url(image3.jpg)no-repeat;
            background-position: center;
            background-size: cover;
            background-attachment: fixed;
        }

        .signup-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            color: #555;
        }

        input {
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color:  dodgerblue;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.1s;
        }

        button:hover {
            background-color: #db044c;
        }

        a {
            font-size: 15px;
            color: darkblue;
            text-decoration: none;
          
        }
p{
    margin-bottom: -10px;
}
        .success-message {
            color: green;
            margin-bottom: 12px;
        }

        .error-message {
            color: red;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="signup-container bg-light p-4">
            <h2>Sign Up</h2>
            <?php
            session_start();
            include("connection.php");
            include("function.php");

            $existingFirstName = "";
            $existingLastName = "";
            $existingIdNumber = "";
            $existingPhoneNumber = "";
            $existingEmail = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $existingFirstName = $_POST["FirstName"];
                $existingLastName = $_POST["LastName"];
                $existingIdNumber = $_POST["IdNumber"];
                $existingPhoneNumber = $_POST["PhoneNumber"];
                $existingEmail = $_POST["Email"];

                $FirstName = mysqli_real_escape_string($conn, $existingFirstName);
                $LastName = mysqli_real_escape_string($conn, $existingLastName);
                $IdNumber = mysqli_real_escape_string($conn, $existingIdNumber);
                $PhoneNumber = mysqli_real_escape_string($conn, $existingPhoneNumber);
                $Email = mysqli_real_escape_string($conn, $existingEmail);
                $Password = $_POST["Password"];
                $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);


                // Check if the email already exists
                $checkEmailQuery = "SELECT * FROM signup WHERE Email = '$Email'";
                $checkEmailResult = $conn->query($checkEmailQuery);

                if ($checkEmailResult && mysqli_num_rows($checkEmailResult) > 0) {
                    echo '<div class="error-message">Email already exists. Please use a different email.</div>';
                } else {
                    // Validate phone number length
                    if (strlen($PhoneNumber) !== 10) {
                        echo '<div class="error-message">Please enter a valid 10-digit phone number.</div>';
                    }
                    // Validate ID number length
                    elseif (strlen($IdNumber) !== 8) {
                        echo '<div class="error-message">Please enter a valid 8-digit ID number.</div>';
                    } else {
                        $user_id = random_num(10);
                        $sql = "INSERT INTO signup (user_id, FirstName, LastName, IdNumber, PhoneNumber, Email, Password) VALUES ('$user_id', '$FirstName', '$LastName', '$IdNumber', '$PhoneNumber', '$Email', '$hashedPassword')";

                        if ($conn->query($sql) === TRUE) {
                            echo '<div class="success-message">Sign up successful! Redirecting to login page...</div>';
                            header("refresh:2;url=login.php"); // Redirect to login page after 3 seconds
                            die;
                        } else {
                            echo '<div class="error-message">Error: ' . $sql . '<br>' . $conn->error . '</div>';
                        }
                    }
                }
            }
        ?>
            <form action="Signup.php" method="post" onsubmit="return validateForm()">
                <div class="mb-3">
                    <label for="FirstName" class="form-label">First Name:</label>
                    <input type="text" class="form-control" name="FirstName" value="<?php echo $existingFirstName; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="LastName" class="form-label">Last Name:</label>
                    <input type="text" class="form-control" name="LastName" value="<?php echo $existingLastName; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="IdNumber" class="form-label">ID Number:</label>
                    <input type="text" class="form-control" name="IdNumber" id="idNumber" value="<?php echo $existingIdNumber; ?>" required>
                    <div class="error-message" id="idNumber-error"></div>
                </div>

                <div class="mb-3">
                    <label for="PhoneNumber" class="form-label">Phone Number:</label>
                    <input type="tel" class="form-control" name="PhoneNumber" id="phoneNumber" value="<?php echo $existingPhoneNumber; ?>" required>
                    <div class="error-message" id="phoneNumber-error"></div>
                </div>

                <div class="mb-3">
                    <label for="Email" class="form-label">Email:</label>
                    <input type="email" class="form-control" name="Email" id="email" value="<?php echo $existingEmail; ?>" required>
                    <div class="error-message" id="email-error"></div>
                </div>

                <div class="mb-3">
                    <label for="Password" class="form-label">Password:</label>
                    <input type="password" class="form-control" name="Password" id="password" required>
                    <span id="passwordToggle" style="cursor: pointer;">&#128065; Show Password</span>
                    <div class="error-message" id="password-error"></div>
                </div>

                <button type="submit" class="btn btn-primary">Sign Up</button><br>
                <p>Have an account?<a href="login.php"> Login Here</a></p><br><br>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('idNumber').addEventListener('input', function () {
            validateIdNumber();
        });

        document.getElementById('phoneNumber').addEventListener('input', function () {
            validatePhoneNumber();
        });

        document.getElementById('email').addEventListener('input', function () {
            validateEmail();
        });

        document.getElementById('password').addEventListener('input', function () {
            validatePassword();
        });

        

    function validateForm() {
        // Add more validation functions if needed
        if (!validateIdNumber() || !validatePhoneNumber() || !validateEmail() || !validatePassword()) {
            return false; // Prevent form submission
        }
        return true; // Allow form submission
    }

    function validateIdNumber() {
        var idNumber = document.getElementById('idNumber').value;
        var idNumberError = document.getElementById('idNumber-error');

        if (idNumber.length !== 8) {
            idNumberError.textContent = 'Please enter a valid 8-digit ID number.';
            return false;
        } else {
            idNumberError.textContent = '';
            return true;
        }
    }

    function validatePhoneNumber() {
        var phoneNumber = document.getElementById('phoneNumber').value;
        var phoneNumberError = document.getElementById('phoneNumber-error');

        if (phoneNumber.length !== 10) {
            phoneNumberError.textContent = 'Please enter a valid 10-digit phone number.';
            return false;
        } else {
            phoneNumberError.textContent = '';
            return true;
        }
    }

    function validateEmail() {
        var email = document.getElementById('email').value;
        var emailError = document.getElementById('email-error');

        var emailCheck = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

        if (!emailCheck) {
            emailError.textContent = 'Please enter a valid email address.';
            return false;
        } else {
            emailError.textContent = '';
            return true;
        }
    }

    function validatePassword() {
        var password = document.getElementById('password').value;
        var passwordError = document.getElementById('password-error');

        var lengthCheck = password.length >= 8;
        var uppercaseCheck = /[A-Z]/.test(password);
        var numberCheck = /\d/.test(password);
        var punctuationCheck = /[!@#$%^&*(),.?":{}|<>]/.test(password);

        if (!lengthCheck || !uppercaseCheck || !numberCheck || !punctuationCheck) {
            passwordError.textContent = 'Password must be at least 8 characters long, contain an uppercase letter, a number, and a punctuation mark.';
            return false;
        } else {
            passwordError.textContent = '';
            return true;
        }
    }
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const passwordToggle = document.getElementById('passwordToggle');

            passwordToggle.addEventListener('click', function () {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordToggle.textContent = '🙈 Hide Password';
                } else {
                    passwordInput.type = 'password';
                    passwordToggle.textContent = '👁️ Show Password';
                }
            });
        });
    </script>
</body>
</html>
