<?php
session_start();
include("connection.php");

// Redirect to login if not logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin_login.php");
    exit();
}

// Initialize variables
$current_password = $new_password = $confirm_password = "";
$current_password_err = $new_password_err = $confirm_password_err = "";

// Process form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate current password
    if (empty(trim($_POST["current_password"]))) {
        $current_password_err = "Please enter your current password.";
    } else {
        $current_password = trim($_POST["current_password"]);
    }

    // Validate new password
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "Please enter a new password.";
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $new_password_err = "Password must have at least 6 characters.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check current password and update if validation passes
    if (empty($current_password_err) && empty($new_password_err) && empty($confirm_password_err)) {

        $username = $_SESSION['admin_username'];

        // Prepare a select statement to check the current password
        $query = "SELECT password FROM admin_users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($hashed_password);
            if ($stmt->fetch()) {
                if (password_verify($current_password, $hashed_password)) {
                    // Password is correct, update with the new password
                    $update_query = "UPDATE admin_users SET password = ? WHERE username = ?";
                    $update_stmt = $conn->prepare($update_query);
                    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_stmt->bind_param("ss", $hashed_new_password, $username);
                    $update_stmt->execute();
                    echo '<script>alert("Password changed successfully."); window.location = "admin_login.php";</script>';
                    exit();
                } else {
                    $current_password_err = "Invalid current password.";
                }
            }
        }

        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
            background-color: #f0f0f0;
        }

        .wrapper {
            width: 360px;
            padding: 20px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin: 10px 0;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #db044c;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #ff0066;
        }

        p {
            color: red;
            text-align: center;
            margin-top: 10px;

        }
        .error-message {
            color: red;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <h2>Change Password</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($current_password_err)) ? 'has-error' : ''; ?>">
                <label for="current_password">Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
                <span class="help-block"><?php echo $current_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" class="form-control" required>
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Change Password</button>
            </div>
        </form>
    </div>
</body>

</html>
