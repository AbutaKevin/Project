<?php
session_start();
include("connection.php");
include("function.php");

$user_data = check_login($conn);

if (!$user_data) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteAccount'])) {
    // User has confirmed the account deletion
    $user_id = $user_data['user_id'];

    // Delete user data from the database
    $deleteUserQuery = "DELETE FROM signup WHERE user_id = '$user_id'";
    if ($conn->query($deleteUserQuery)) {
        // Logout user after successful deletion
        session_destroy();
        echo '<script>alert("Account deleted successfully."); window.location.href = "index.php";</script>';
        exit();
    } else {
        echo '<script>alert("Error deleting account: ' . $conn->error . '");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <link rel="icon" type="image/png" href="images/logo.png">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background-color: #f5f5f5;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            
        }

        .container {
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

        button {
            background-color: #db044c;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.1s;
        }

        button:hover {
            background-color: #ff3366;
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
    <div class="main-content">
        <div class="container">
            <h2>Delete Account</h2>
            <p>Are you sure you want to delete your account?</p>
            <form action="deleteAccount.php" method="post">
                <button type="submit" name="deleteAccount">Delete Account</button>
            </form>
        </div>
    </div>
</body>
</html>
