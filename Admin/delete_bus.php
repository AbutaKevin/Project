<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    // Redirect to login page if not logged in
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bus_id'])) {
    $busId = $_POST['bus_id'];

    // Perform the deletion from the database
    $deleteSql = "DELETE FROM bus WHERE bus_id = $busId";
    if ($conn->query($deleteSql) === TRUE) {
        echo "Bus deleted successfully.";
    } else {
        echo "Error deleting bus: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
