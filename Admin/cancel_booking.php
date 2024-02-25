<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'])) {
    $bookingId = $_POST['booking_id'];

    // Delete the booking from the database
    $sql = "DELETE FROM bookings WHERE id = $bookingId";
    if ($conn->query($sql) === TRUE) {
        // Use JavaScript to show an alert and redirect to the bookings page
        echo '<script>alert("Booking canceled successfully."); window.location.href = "bookings.php";</script>';
    } else {
        echo "Error canceling booking: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
