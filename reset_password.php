<?php
session_start();
include("connection.php");
include("function.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $Email = $_GET["email"];
    $ResetCode = $_GET["code"];

    // Check if the email and reset code match
    $checkCodeQuery = "SELECT * FROM signup WHERE Email = '$Email' AND ResetCode = '$ResetCode'";
    $checkCodeResult = $conn->query($checkCodeQuery);

    if ($checkCodeResult && mysqli_num_rows($checkCodeResult) > 0) {
        // Display the password reset form
    } else {
        echo '<div class="error-message">Invalid or expired reset code.</div>';
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];

    // Update the password and clear the reset code
    $updatePasswordQuery = "UPDATE signup SET Password = '$Password', ResetCode = '' WHERE Email = '$Email'";
    $conn->query($updatePasswordQuery);

    echo '<div class="success-message">Password reset successful! Redirecting to login page...</div>';
    header("refresh:3;url=login.php"); // Redirect to login page after 3 seconds
    die;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="icon" type="image/png" href="logo.png">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to top,rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url(image3.jpg)no-repeat;
            background-position: center;
            background-size: cover;
        }

        .reset-password-container {
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
            font-style: italic;
            font-size: 15px;
            color: darkblue;
            text-decoration: none;
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
    <div class="reset-password-container">
        <h2>Reset Password</h2>
        <form action="reset_password.php" method="post">
            <label for="Email">Email:</label>
            <input type="email" name="Email" value="<?php echo $Email; ?>" readonly>

            <label for="Password">New Password:</label>
            <input type="password" name="Password" required id="password">
            <span id="passwordToggle" style="cursor: pointer;">&#128065; Show Password</span>
            <div class="error-message" id="password-error"></div>

            <button type="submit">Reset Password</button></br>
            <a href="login.php">Remembered your password? Click to Login</a><br><br>
        </form>

        <script>
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
    </div>
</body>
</html>
