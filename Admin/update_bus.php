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
    $busId = mysqli_real_escape_string($conn, $_POST['bus_id']);
    $busNumber = mysqli_real_escape_string($conn, $_POST['bus_number']);
    $fare = mysqli_real_escape_string($conn, $_POST['fare']);
    $departureTime = mysqli_real_escape_string($conn, $_POST['departure_time']);
    $availableSeats = mysqli_real_escape_string($conn, $_POST['available_seats']);
    $fromLocation = mysqli_real_escape_string($conn, $_POST['from_location']);
    $toLocation = mysqli_real_escape_string($conn, $_POST['to_location']);

    // Use prepared statement to prevent SQL injection
    $updateQuery = $conn->prepare("UPDATE bus SET bus_number=?, fare=?, departure_time=?, available_seats=?, from_location=?, to_location=? WHERE bus_id=?");
    
    // Bind parameters
    $updateQuery->bind_param("ssssssi", $busNumber, $fare, $departureTime, $availableSeats, $fromLocation, $toLocation, $busId);

    // Execute the prepared statement
    if ($updateQuery->execute()) {
        // Successful update
        echo "Bus updated successfully.";
    } else {
        // Error in update
        echo "Error: " . $conn->error;
    }

    // Close the prepared statement
    $updateQuery->close();

    // Close the database connection
    $conn->close();
} else {
    // Redirect to the form page if accessed directly
    header("Location: Buses.php");
    exit();
}
?>
