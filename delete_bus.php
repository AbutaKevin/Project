<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bus_id'])) {
    $busId = $_POST['bus_id'];

    // Delete the bus from the database
    $sql = "DELETE FROM bus WHERE bus_id = $busId";
    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Bus deleted successfully."); window.location.href = "Buses.php";</script>';
    } else {
        echo "Error deleting bus: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
