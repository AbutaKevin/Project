<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin_login.php");
    exit();
}

// Retrieve bus_number from GET parameter
$bus_number = $_GET['bus_number'];

// Perform calculations to get available seats for all dates (assuming 52 total seats)
$bookedSeatsQuery = "SELECT travel_date, SUM(number_of_seats) as booked_seats FROM bookings WHERE bus_number = ? GROUP BY travel_date";
$bookedSeatsStmt = $conn->prepare($bookedSeatsQuery);
$bookedSeatsStmt->bind_param("s", $bus_number);
$bookedSeatsStmt->execute();
$bookedSeatsResult = $bookedSeatsStmt->get_result();
$bookedSeatsStmt->close();

$totalSeats = 52; // Assuming a total of 52 seats

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Seats</title>
    <link rel="icon" type="image/png" href="logo.png">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f2f2f2;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
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
        a.choose-another {
    display: inline-block;
    color:white;
    font-weight: bold;
    text-decoration: none;
    background-color:dodgerblue;
            border-radius: 15px;
            padding: 10px;
}

    </style>
</head>

<body>
    <div class="container">
        <h2>Available Seats for Bus <?php echo $bus_number; ?></h2>
        <table border="1">
            <tr>
                <th>Travel Date</th>
                <th>Total Seats</th>
                <th>Booked Seats</th>
                <th>Available Seats</th>
            </tr>
            <?php
            while ($row = $bookedSeatsResult->fetch_assoc()) {
                $travelDate = $row['travel_date'];
                $bookedSeats = $row['booked_seats'];
                $availableSeats = $totalSeats - $bookedSeats;
            ?>
                <tr>
                    <td><?php echo $travelDate; ?></td>
                    <td><?php echo $totalSeats; ?></td>
                    <td><?php echo $bookedSeats; ?></td>
                    <td><?php echo $availableSeats; ?></td>
                </tr>
            <?php
            }
           
            ?>
            
        </table>
        <p><a href='bookings.php' class='choose-another'>Go back</a></p>

    </div>
</body>

</html>
