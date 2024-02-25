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

// Check if the search form is submitted
if (isset($_POST['search'])) {
    $from = $_POST['from'];
    $to = $_POST['to'];
    $travelDate = $_POST['travelDate'];

    // Store values in session variables
    $_SESSION['from'] = $from;
    $_SESSION['to'] = $to;
    $_SESSION['travelDate'] = $travelDate;

    $currentDate = date("Y-m-d");
    if ($travelDate < $currentDate) {
        echo "<script>alert('Please select a date from today onwards.');</script>";
    } else {
        $sql = "INSERT INTO location (from_location, to_location, travel_date) VALUES ('$from', '$to', '$travelDate')";

        if ($conn->query($sql) === TRUE) {
            header("Location: Book.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location</title>
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script>
    function booknow(){
        window.location.href = 'Book.php';
    }
</script>

    <style>
        body {
            height: 100vh;
            width: 100%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(image3.jpg)no-repeat;
            background-position: center;
            background-size: cover;
        }

        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .search-section {
            max-width: 500px;
            width: 100%;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-radius: 15px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: black;
            font-weight: bold;
        }

        select,
        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        .button {
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            transition: background-color 0.1s;
            text-decoration: none;
            color: white;
        }

        .book-button {
            background-color: #101010;
        }

        .book-button:hover {
            background-color: #db044c;
        }

        .cancel-button {
            background-color: red;
        }

        .cancel-button:hover {
            background-color: blue;
        }
    </style>

    <script>
        window.onload = function () {
            var currentDate = new Date().toISOString().split('T')[0];
            document.getElementById("travelDate").min = currentDate;
        }
    </script>
</head>

<body>
    <div class="center-container">
        <div class="search-section">
            <h2>Find Your Next Destination</h2>
            <form action="Book.php" method="post">
                <div class="form-group">
                    <label for="from">From:</label>
                    <select id="from" name="from">
                        <option value="Eldoret">Eldoret</option>
                        <option value="Kakamega">Kakamega</option>
                        <option value="KIsumu">kisumu</option>
                        <option value="Kisii">Kisii</option>
                        <option value="Malindi">Malindi</option>
                        <option value="Migori">Migori</option>
                        <option value="Mombasa">Mombasa</option>
                        <option value="Nairobi">Nairobi</option>
                        <option value="Nakuru">Nakuru</option>
                        <option value="Narok">Narok</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="to">To:</label>
                    <select id="to" name="to">
                        <option value="Eldoret">Eldoret</option>
                        <option value="Kakamega">Kakamega</option>
                        <option value="KIsumu">kisumu</option>
                        <option value="Kisii">Kisii</option>
                        <option value="Malindi">Malindi</option>
                        <option value="Migori">Migori</option>
                        <option value="Mombasa">Mombasa</option>
                        <option value="Nairobi">Nairobi</option>
                        <option value="Nakuru">Nakuru</option>
                        <option value="Narok">Narok</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="travelDate">Travel Date:</label>
                    <input type="date" id="travelDate" name="travelDate" required>
                </div>

                <button type="submit" name="search" onclick="booknow()" class="button book-button">Search Bus</button>
                <a href="index.php" class="button cancel-button">Cancel</a>
            </form>
        </div>
    </div>
</body>

</html>
