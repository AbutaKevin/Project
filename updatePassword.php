<?php
session_start();
include("connection.php");
include("function.php");

$user_data = check_login($conn);

if (!$user_data) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST["currentPassword"];
    $newPassword = $_POST["newPassword"];
    $confirmPassword = $_POST["confirmPassword"];

    // Check if current password matches the one in the database
    $checkPasswordQuery = "SELECT * FROM signup WHERE user_id = '{$user_data['user_id']}'";
    $checkPasswordResult = $conn->query($checkPasswordQuery);

    if ($checkPasswordResult && mysqli_num_rows($checkPasswordResult) > 0) {
        $user_data = mysqli_fetch_assoc($checkPasswordResult);

        // Verify the current password using password_verify()
        if (password_verify($currentPassword, $user_data['Password'])) {
            // Check if new and confirm passwords match
            if ($newPassword == $confirmPassword) {
                // Update password in the database with the new hashed password
                $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updatePasswordQuery = "UPDATE signup SET Password = '$hashedNewPassword' WHERE user_id = '{$user_data['user_id']}'";
                $conn->query($updatePasswordQuery);

                echo '<script>alert("Password updated successfully!"); window.location.href = "myAccount.php";</script>';
                exit();
            } else {
                echo '<script>alert("New password and confirm password do not match."); window.location.href = "myAccount.php";</script>';
                exit();
            }
        } else {
            echo '<script>alert("Current password is incorrect."); window.location.href = "myAccount.php";</script>';
            exit();
        }
    }
}
?>
