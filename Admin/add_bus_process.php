<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin_login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    // Validate and sanitize the input data
    $busNumber = mysqli_real_escape_string($conn, $data['bus_number']);
    $fare = mysqli_real_escape_string($conn, $data['fare']);
    $departureTime = mysqli_real_escape_string($conn, $data['departure_time']);
    $availableSeats = mysqli_real_escape_string($conn, $data['available_seats']);
    $fromLocation = mysqli_real_escape_string($conn, $data['from_location']);
    $toLocation = mysqli_real_escape_string($conn, $data['to_location']);

    // Use prepared statement to prevent SQL injection
    $insertQuery = $conn->prepare("INSERT INTO bus (bus_number, fare, departure_time, available_seats, from_location, to_location) VALUES (?, ?, ?, ?, ?, ?)");
   
    $insertQuery->bind_param("ssssss", $busNumber, $fare, $departureTime, $availableSeats, $fromLocation, $toLocation);

   
    if ($insertQuery->execute()) {
       
        echo "Bus added successfully.";
    } else {
       
        echo "Error: " . $conn->error;
    }

    
    $insertQuery->close();

    
    $conn->close();
} else {
    
    header("Location: add_bus.php");
    exit();
}
?>
