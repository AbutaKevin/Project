<?php
session_start();
include("connection.php");
include("function.php");

$user_data = check_login($conn);

if (!$user_data) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newFirstName = $_POST['newFirstName'];
    $newLastName = $_POST['newLastName'];
    $newPhoneNumber = $_POST['newPhoneNumber'];

    // Update the user's information in the database
    $updateInfoQuery = "UPDATE signup SET FirstName = ?, LastName = ?, PhoneNumber = ? WHERE Email = ?";
    $stmt = $conn->prepare($updateInfoQuery);
    $stmt->bind_param("ssss", $newFirstName, $newLastName, $newPhoneNumber, $user_data['Email']);
    $stmt->execute();
    $stmt->close();
}
// Redirect back to the main account page
header("Location: myaccount.php");
exit();
?>
