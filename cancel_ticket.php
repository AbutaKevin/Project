<?php
session_start();
include("connection.php");

if (isset($_POST['cancel']) && isset($_POST['id'])) {
    $bookingId = $_POST['id'];

    // Delete the booking record from the database
    $deleteQuery = "DELETE FROM bookings WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $bookingId);

    if ($stmt->execute()) {
        $response = ['success' => true, 'message' => 'The ticket has been successfully cancelled. Wait for the refund in less than 24 hours!!!'];
    } else {
        $response = ['success' => false, 'message' => 'Error deleting booking.'];
        error_log("Error deleting booking: " . $stmt->error);
    }

    $stmt->close();

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
