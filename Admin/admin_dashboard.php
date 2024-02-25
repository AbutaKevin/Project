<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/png" href="logo.png">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
        }

        p {
            color: #555;
            margin-bottom: 30px;
            font-size: 16px;
        }

        a {
            display: inline-block;
            background-color: #db044c;
            color: #fff;
            padding: 15px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
            transition: background-color 0.3s;
            font-weight: bold;
            font-size: 16px;
            border: 2px solid #db044c;
        }

        a:hover {
            background-color: #ff0066;
            border-color: #ff0066;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
        session_start();
        include("connection.php");
        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            header("Location: admin_login.php");
            exit();
        }

        // Check if admin_username is set in the session
        $username = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : "Unknown";
        ?>
        <h2>Welcome to KeviBus Admin Dashboard</h2>
        <p>Manage your bus booking system here.</p>
        <p><?php echo "Welcome, $username!"; ?></p>
        <a href="bookings.php">View Bookings</a>
        <a href="Buses.php">Manage Buses</a>
        <a href="users.php">Users</a>
        <a href="Admin_reg.php">Add Admin</a>
        <a href="admin_change_password.php">Change Password</a>
        <a href="admin_management.php">Admin Management</a>
        <a href="admin_login.php">Logout</a>

    </div>
</body>

</html>
