<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']) {
    // If logged in, destroy the session
    session_destroy();
}

// Redirect to the index page after logout
header("Location: index.php");
exit();
?>
