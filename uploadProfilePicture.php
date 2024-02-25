<?php
session_start();
include("connection.php");
include("function.php");

$user_data = check_login($conn);

if (!$user_data) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profilePicture"])) {
    $target_dir = "uploads/";  // Specify your upload directory
    $target_file = $target_dir . basename($_FILES["profilePicture"]["name"]);
    $uploadOk = 1;

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["profilePicture"]["tmp_name"]);
    if ($check !== false) {
        // Allow only certain file formats
        $allowed_formats = array("jpg", "jpeg", "png", "gif");
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!in_array($imageFileType, $allowed_formats)) {
            echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // Move the file to the desired directory
            if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $target_file)) {
                // Update the user's profile picture path in the database
                $update_query = "UPDATE signup SET ProfilePicture = '$target_file' WHERE Email = '$user_data[Email]'";
                mysqli_query($conn, $update_query);
                echo "The file " . basename($_FILES["profilePicture"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
} else {
    echo "Invalid request.";
}
?>
