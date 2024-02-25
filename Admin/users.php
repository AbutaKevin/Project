<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin_login.php");
    exit();
}

// Check if a search query is present
$searchQuery = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Modify the SQL query to include search functionality
$sql = "SELECT * FROM signup WHERE 
        FirstName LIKE '%$searchQuery%' OR 
        LastName LIKE '%$searchQuery%' OR 
        IdNumber LIKE '%$searchQuery%' OR 
        PhoneNumber LIKE '%$searchQuery%' OR 
        Email LIKE '%$searchQuery%'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="icon" type="image/png" href="logo.png">
    <style>
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f0f0;
}

.navbar {
    background-color: #333;
    overflow: hidden;
    padding: 10px;
}

.navbar a {
    float: left;
    display: block;
    color: #f2f2f2;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

.navbar a:hover {
    background-color: #ddd;
    color: black;
}

.container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    margin-top: 0px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 15px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #333;
    color: #fff;
}

tr:hover {
    background-color: #f5f5f5;
}

img {
    max-width: 50px;
    max-height: 50px;
}

.delete-button {
    padding: 8px 16px;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    background-color: #f44336;
    color: #fff;
    text-decoration: none;
}

.delete-button:hover {
    background-color: #d32f2f;
}

.search-container {
    float: right;
    margin-bottom: 10px;
}

.search-container input[type="text"] {
    padding: 6px;
    border: 1px solid #ddd;
}

.search-container button {
    padding: 6px;
    background: #f44336;
    border: none;
    cursor: pointer;
    color: white;
}

.search-container button:hover {
    background: #d32f2f;
}



    </style>
</head>

<body>
    <div class="navbar">
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="Buses.php">Available Buses</a>
    <a href="bookings.php">All Bookings</a>
    </div>
    
    <div class="container">
        <div class="search-container">
            <form action="users.php" method="GET">
                <input type="text" placeholder="Search..." name="search">
                <button type="submit">Search</button>
            </form>
        </div>
        

        <h2><a href="users.php" style="text-decoration: none;color:#333">All users</a></h2>

        <?php
        // Check if there are users in the database
        if ($result->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>User ID</th><th>First Name</th><th>Last Name</th><th>ID Number</th><th>Phone Number</th><th>Email</th><th>Profile Picture</th><th>Action</th></tr>";

            // Output data of each user
            while ($row = $result->fetch_assoc()) {
                $userId = $row['user_id'];
                $firstName = $row['FirstName'];
                $lastName = $row['LastName'];
                $idNumber = $row['IdNumber'];
                $phoneNumber = $row['PhoneNumber'];
                $email = $row['Email'];
                $profilePictureFilename = basename($row['ProfilePicture']); 
                $profilePicturePath = "images/$profilePictureFilename"; 
            
                echo "<tr>";
                echo "<td>$userId</td>";
                echo "<td>$firstName</td>";
                echo "<td>$lastName</td>";
                echo "<td>$idNumber</td>";
                echo "<td>$phoneNumber</td>";
                echo "<td>$email</td>";
                if (file_exists($profilePicturePath)) {
                    echo "<td><img src='$profilePicturePath' alt='Profile Picture' style='max-width: 50px; max-height: 50px;'></td>";
                } else {
                    echo "<td>No Image</td>";
                }
            
                echo "<td><a class='delete-button' href='#' onclick='deleteUser($userId)'>Delete</a></td>";
                echo "</tr>";
            }
            
        }

        ?>
    </div>


<script>
    function deleteUser(userId) {
        // Ask for confirmation before deleting
        var confirmDelete = confirm("Are you sure you want to delete this user?");

        if (confirmDelete) {
            // Perform AJAX request to delete_user.php
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_user.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        if (xhr.responseText.includes("successfully")) {
                            // Show an additional success alert
                            alert("User deleted successfully.");
                            window.location.href = "users.php";
                        }
                    } else {
                        // Handle the case when the request fails
                        alert("Failed to delete the user. Please try again.");
                    }
                }
            };

            // Send the request with the user ID
            xhr.send("user_id=" + encodeURIComponent(userId));
        }
    }
</script>


</body>

</html>
