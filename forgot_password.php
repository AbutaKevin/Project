<?php
session_start();
include("connection.php");
include("function.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST["Email"];

    $checkEmailQuery = "SELECT * FROM signup WHERE Email = '$Email'";
    $checkEmailResult = $conn->query($checkEmailQuery);

    if ($checkEmailResult && mysqli_num_rows($checkEmailResult) > 0) {
        // Generate a random code for password reset
        $resetCode = random_num(6);

        // Store the code in the database
        $updateCodeQuery = "UPDATE signup SET ResetCode = '$resetCode' WHERE Email = '$Email'";
        $conn->query($updateCodeQuery);

        // Send the reset code to the user's email (you may need to implement this part)
        $resetLink = "http://www.kevibus.com/reset_password.php?email=$Email&code=$resetCode";
        // Send the reset link to the user's email (you should implement this part)
        mail($Email, "Password Reset", "Click the link to reset your password: $resetLink");

        // Notify the user using JavaScript
        echo '<script>showNotification("success", "Password reset link sent to your email. Check your inbox!");</script>';
    } else {
        // Notify the user using JavaScript
        echo '<script>showNotification("error", "Email not found. Please enter a valid email.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="icon" type="image/png" href="logo.png">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to top,rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url(image3.jpg)no-repeat;
            background-position:center;
            background-size: cover;
        }

        .forgot-password-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 65px;
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
            margin-top: 18px;
            color: #555;
        }

        input {
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 17px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color:  dodgerblue;
            color: white;
            padding: 12px;
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

    <!-- Include the JavaScript function for notifications -->
    <script>
        function showNotification(type, message) {
            var notificationDiv = document.createElement("div");
            notificationDiv.className = type + "-message";
            notificationDiv.textContent = message;

            document.body.appendChild(notificationDiv);

            setTimeout(function() {
                document.body.removeChild(notificationDiv);
            }, 5000); // Display the notification for 5 seconds (adjust as needed)
        }

        function handleFormSubmission() {
            // You can add any additional client-side validation here before submitting the form
            document.forms[0].submit();
        }
    </script>
</head>
<body>
    <div class="forgot-password-container">
        <h2>Forgot Password</h2>
        <form action="forgot_password.php" method="post" onsubmit="event.preventDefault(); handleFormSubmission();">
            <label for="Email">Enter your Email:</label>
            <input type="email" name="Email" required>

            <button type="submit">Reset Password</button></br><br>
            <a href="login.php">Remembered your password? Click to Login</a><br><br>
        </form>
    </div>
</body>
</html>
