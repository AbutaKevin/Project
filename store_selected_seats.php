<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store selected seats in the session
    $_SESSION['busDetails']['numberOfSeats'] = $_POST['numberOfSeats'];
    $_SESSION['busDetails']['seatNumbers'] = $_POST['seatNumbers'];

    // Send a response back to the client (you can handle this as needed)
    echo "Seats stored successfully";
} else {
    // If not a POST request, handle accordingly
    echo "Invalid request";
}
?>
