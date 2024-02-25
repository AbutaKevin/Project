<?php
session_start();
include("connection.php");
include("function.php");

$user_data = check_login($conn);

if (!$user_data) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profileImage'])) {
    $uploadDir = 'images/'; // Set your desired upload directory
    $uploadFile = $uploadDir . basename($_FILES['profileImage']['name']);

    if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $uploadFile)) {
        // Update the user's profile image in the database
        $updateImageQuery = "UPDATE signup SET ProfilePicture = ? WHERE Email = ?";
        $stmt = $conn->prepare($updateImageQuery);
        $stmt->bind_param("ss", $uploadFile, $user_data['Email']);
        $stmt->execute();
        $stmt->close();

    } else {

    }
}
?>

            

            
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>My Account</title>
    <link rel="icon" type="image/png" href="images/logo.png">

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const newPasswordInput = document.getElementById('newPassword');
            const passwordToggle = document.getElementById('passwordToggle');
            const passwordStrength = document.getElementById('passwordStrength');

            passwordToggle.addEventListener('click', function () {
                if (newPasswordInput.type === 'password') {
                    newPasswordInput.type = 'text';
                    passwordToggle.textContent = '🙈 Hide ';
                } else {
                    newPasswordInput.type = 'password';
                    passwordToggle.textContent = '👁️ Show ';
                }
            });

            newPasswordInput.addEventListener('input', function () {
                // Validate password strength
                const password = newPasswordInput.value;
                const strength = getPasswordStrength(password);

                passwordStrength.className = strength.className;
            });

            // Function to determine password strength
            function getPasswordStrength(password) {
            var lengthCheck = password.length >= 8;
            var uppercaseCheck = /[A-Z]/.test(password);
            var numberCheck = /\d/.test(password);
            var punctuationCheck = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            
            if (!lengthCheck || !uppercaseCheck || !numberCheck || !punctuationCheck) {
                passwordStrength.textContent = 'Password must be at least 8 characters long, contain an uppercase letter, a number, and a punctuation mark.';
            } else {
                passwordStrength.textContent = '';
            }
        }
    });
  
    function uploadImage(input) {
        var form = document.getElementById('imageForm');
        var formData = new FormData(form);
        
        // Using AJAX to submit the form data
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // If the request was successful, update the image source
                displayImage(input);
            }
        };
        xhr.open('POST', form.action, true);
        xhr.send(formData);
    }

    function displayImage(input) {
    var img = document.getElementById('profileImage');
    var newImage;

    if (input.files.length > 0) {
        newImage = URL.createObjectURL(input.files[0]);
    } else {
        newImage = 'images/placeholder-circle.png';
    }

    img.src = newImage;
}

</script>

    <style>
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: teal;
            color: #fff;
            padding: 20px;
            width: 260px;
            align-items: center;
            overflow-y: auto;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #f5f5f5;
            display: flex;
            flex-wrap: wrap;
            margin-left: 260px;
        }

        .profile-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-picture {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
            color: white;
        }

        .upload-btn-wrapper {
            position: relative;
            overflow: visible;
            display: inline-block;
        }

        .btn {
            border: 2px solid gray;
            color: gray;
            background-color: white;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
        }

        .upload-btn-wrapper input[type=file] {
            font-size: 10px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }

        .container {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        .user-details-container,
        .update-password-container,
        .update-info-container,
        .change-email-container {
            flex-basis: calc(40.444% - 20px);
            max-width: calc(40.444% - 20px);
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd; /* Added border */
            border-radius: 15px;
            margin: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Added box shadow */
        }

        .booking-records-container {
            flex-grow: 2;
            max-width: calc(66.666% - 20px);
            padding: 50px;
            background-color: #fff;
            border: 1px solid #ddd; /* Added border */
            border-radius: 15px;
            margin: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Added box shadow */
            height: auto;
            overflow-y: auto;
            margin-left: 200px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 30px;
            font-style: inherit;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            color: #555;
        }

        input {
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            color: black;
        }

        #anchor {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }

        #anchor a {
            display: block;
            margin: 10px 0;
            padding: 10px;
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.2s;
            border-radius: 8px;
            margin-left: 8px;
            font-weight: bold;
        }

        #anchor a:hover {
            background-color: crimson;
        }

        button {
            background-color: dodgerblue;
            color: white;
            padding: 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.1s;
        }

        button:hover {
            background-color: blue;
        }

        .success-message {
            color: green;
            margin-bottom: 12px;
        }

        .error-message {
            color: red;
            margin-bottom: 12px;
        }

        #passwordStrength {
            margin-top: 5px;
            font-size: 12px;
            color: red;
        }
        .booking-records-container span{
            font-weight: bold;
        }
        .user-details-container span{
            font-weight: bold;
        }


    
    </style>
</head>
<body>

<div class="sidebar">
           <div class="profile-section">
            <h3 style="color:white;">Welcome, <?php echo $user_data['FirstName']; ?>!</h3>
            <img id="profileImage" class="profile-picture" src="<?php echo $user_data['ProfilePicture'] ? $user_data['ProfilePicture'] : 'images/placeholder-circle.png'; ?>" alt="Profile Picture">
            <div class="upload-btn-wrapper">
                <form id="imageForm" action="" method="post" enctype="multipart/form-data">
                    <label for="formFile" class="form-label" style="color:red;">Click Here to select an image</label>
                    <input onchange="uploadImage(this); displayImage(this);" class="form-control" type="file" name="profileImage" id="formFile">
                </form>
            </div>
        </div>

        <div id="anchor">
    <a href="Location.php"> <i class="bi bi-geo-alt" style="margin-right: 8px;"></i>  Book Bus Now</a>
    <a href="cancel.php"><i class="bi bi-x-octagon" style="margin-right: 8px;"></i>  Cancel Ticket</a>
    <a href="deleteAccount.php"><i class="bi bi-trash" style="margin-right: 8px;"></i>  Delete Account</a>
    <a href="index.php"><i class="bi bi-house" style="margin-right: 8px;"></i>  HomePage</a>
    <a href="logout.php" ><i class="bi bi-box-arrow-right" style="margin-right: 8px;"></i>  Logout</a>
</div>
</div>

    <div class="main-content">
        <div class="container">
            <!-- Container 1: User Details -->
            <div class="user-details-container">
                <!-- Display user details -->
                <h3>User Details</h3><hr>
                <p><span>First Name:</span> <?php echo $user_data['FirstName']; ?></p>
                <p><span>Last Name:</span> <?php echo $user_data['LastName']; ?></p>
                <p><span>ID Number:</span> <?php echo $user_data['IdNumber']; ?></p>
                <p><span>Phone Number:</span> <?php echo $user_data['PhoneNumber']; ?></p>
                <p><span>Email:</span> <?php echo $user_data['Email']; ?></p>

            </div>

            <!-- Container 2: Update Password -->
            <div class="update-password-container">
        <h3>Update Password</h3><hr>
        <form action="updatePassword.php" method="post">
            <label for="currentPassword">Current Password:</label>
            <input type="password" name="currentPassword" required>

            <label for="newPassword">New Password:</label>
            <div>
                <input type="password" name="newPassword" id="newPassword" required>
                <span id="passwordToggle" style="cursor: pointer;">&#128065; Show </span>
                <p id="passwordStrength"></p>
            </div>

            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" name="confirmPassword" required>

            <button type="submit">Update Password</button>
        </form>
    </div>
    </div>


        
        <div class="container">
        <!-- Container 4: Change Email -->
        <div class="change-email-container">
            <h3>Change Email</h3><hr>
            <form action="changeEmail.php" method="post">
                <label for="currentEmail">Current Email:</label>
                <input type="email" name="currentEmail" required>

                <label for="newEmail">New Email:</label>
                <input type="email" name="newEmail" required>

                <label for="confirmEmail">Confirm Email:</label>
                <input type="email" name="confirmEmail" required>

                <button type="submit">Change Email</button>
            </form>
        </div>
        <div class="update-info-container">
            <!-- Update Personal Info form -->
            <h3>Update Personal Info</h3><hr>
            <form action="updatePersonalInfo.php" method="post">
                <label for="newFirstName">New First Name:</label>
                <input type="text" name="newFirstName" required>

                <label for="newLastName">New Last Name:</label>
                <input type="text" name="newLastName" required>

                <label for="newPhoneNumber">New Phone Number:</label>
                <input type="tel" name="newPhoneNumber" required>

                <button type="submit">Update Information</button>
            </form>
        </div>
    </div>
        
        <!-- Container 3: Booking Records -->
        <div class="booking-records-container">
            <h3>Booking Records</h3><hr>
            <?php
        $userEmail = $user_data['Email'];
        $bookingQuery = "SELECT * FROM bookings WHERE Email = ?";
        $stmt = $conn->prepare($bookingQuery);
        $stmt->bind_param("s", $userEmail);
        $stmt->execute();
        $result = $stmt->get_result();


        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<p><span>Departure Town:</span> {$row['travel_from']}</p>";
                echo "<p><span>Destination Town:</span> {$row['travel_to']}</p>";
                echo "<p><span>Travel Date:</span> {$row['travel_date']}</p>";
                echo "<p><span>Bus Number:</span> {$row['bus_number']}</p>";
                echo "<p><span>Departure Time:</span> {$row['departure_time']}</p>";
                echo "<p><span>Fare:</span> {$row['fare']}</p>";
                echo "<p><span>Number of Seats:</span> {$row['number_of_seats']}</p>";
                echo "<p><span>Seat Numbers:</span> {$row['seat_numbers']}</p>";                
                echo "<hr>";
            }
        } else {
            echo "<p>No booking records found.</p>";
        }

        // Close the statement
        $stmt->close();
    ?>
        </div>

    </script>
</body>
</html>
