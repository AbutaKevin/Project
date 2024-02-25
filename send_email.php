<?php
session_start();
include("connection.php");
include("function.php");

$user_data = check_login($conn);

// Check if the user is logged in
if (!$user_data) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $to = $user_data['Email'];  
    $subject = "Ticket Details";
    $message = $_POST["ticketDetails"];

    $headers = "From: abutakevin254@gmail.com"; 

    mail($to, $subject, $message, $headers);
}
?>

