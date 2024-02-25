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
    $currentEmail = $_POST["currentEmail"];
    $newEmail = $_POST["newEmail"];
    $confirmEmail = $_POST["confirmEmail"];

    // Check if current email matches the one in the database
    $checkEmailQuery = "SELECT * FROM signup WHERE user_id = '{$user_data['user_id']}' AND Email = '$currentEmail'";
    $checkEmailResult = $conn->query($checkEmailQuery);

    if ($checkEmailResult && mysqli_num_rows($checkEmailResult) > 0) {
        // Check if new and confirm emails match
        if ($newEmail == $confirmEmail) {
            // Update email in the database
            $updateEmailQuery = "UPDATE signup SET Email = '$newEmail' WHERE user_id = '{$user_data['user_id']}'";
            $conn->query($updateEmailQuery);

            echo '<script>alert("Email updated successfully!"); window.location.href = "myAccount.php";</script>';
            exit();
        } else {
            echo '<script>alert("New email and confirm email do not match."); window.location.href = "myAccount.php";</script>';
            exit();
        }
    } else {
        echo '<script>alert("Current email is incorrect."); window.location.href = "myAccount.php";</script>';
        exit();
    }
}
?>
