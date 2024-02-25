<?php
session_start();
include("connection.php");
include("function.php");

$incorrectCredentials = false;
$accountNotExist = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];

    $sql = "SELECT * FROM signup WHERE Email = '$Email'";
    $result = $conn->query($sql);

    if (!$result) {
        die('Error executing the query: ' . $conn->error);
    }

    if ($result && mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);

        // Verify the password using password_verify()
        if (password_verify($Password, $user_data['Password'])) {
            $_SESSION['user_id'] = $user_data['user_id'];
            $_SESSION['user_logged_in'] = true; // Set user logged in status
            header("Location: index.php");
            die;
        } else {
            $incorrectCredentials = true;
        }
    } else {
        $accountNotExist = true;
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(image3.jpg) no-repeat;
            background-position: center;
            background-size: cover;
        }

        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px
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
            background-color: dodgerblue;
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
            margin-bottom: 5px;
        }

        #incorrectPasswordMessage {
            color: red;
            margin-top: 5px;
            display: <?php echo $incorrectPassword ? 'block' : 'none'; ?>;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="login-container bg-light p-4">
        <h2 class="mb-4">Login</h2>
        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="Email" class="form-label">Email:</label>
                <input type="email" class="form-control" name="Email" required>
            </div>

            <div class="mb-3">
                <label for="Password" class="form-label">Password:</label>
                <input type="password" class="form-control" name="Password" required id="password">
                <span id="passwordToggle" style="cursor: pointer;">&#128065; Show Password</span><br>
            </div>
            

<!-- Display account does not exist message -->
<p id="accountNotExistMessage" style="color: red; margin-top: 5px; display: <?php echo $accountNotExist ? 'block' : 'none'; ?>;">
    Account does not exist. Please check your email.
</p>

<!-- Display incorrect password message -->
<p id="incorrectPasswordMessage" style="color: red; margin-top: 5px; display: <?php echo $incorrectCredentials ? 'block' : 'none'; ?>;">
    Incorrect password. Please try again.
</p>

            <button type="submit" class="btn btn-primary">Login</button><br>
           <p> Don't have an account?<a href="SignUp.php"> Signup Here</a></p><br>
            <a href="forgot_password.php" >Forgot Password?</a><br>
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
</div>
</body>
</html>
