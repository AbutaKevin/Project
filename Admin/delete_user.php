<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin_login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize the input data
    $userId = mysqli_real_escape_string($conn, $_POST['user_id']);

    // Perform the deletion
    $deleteQuery = "DELETE FROM signup WHERE user_id = '$userId'";

    if ($conn->query($deleteQuery) === TRUE) {
        // Successful deletion
        echo "User deleted successfully.";
    } else {
        // Error in deletion
        echo "Error: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    // Redirect to the admin_dashboard.php page if accessed directly
    header("Location: admin_dashboard.php");
    exit();
}
?>
