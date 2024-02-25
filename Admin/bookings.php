<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin_login.php");
    exit();
}

// Check if search query is set
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT * FROM bookings WHERE 
            first_name LIKE '%$search%' OR 
            last_name LIKE '%$search%' OR 
            Email LIKE '%$search%' OR 
            travel_from LIKE '%$search%' OR 
            travel_to LIKE '%$search%' OR 
            travel_date LIKE '%$search%' OR 
            bus_number LIKE '%$search%' OR 
            departure_time LIKE '%$search%' OR 
            fare LIKE '%$search%' OR 
            number_of_seats LIKE '%$search%' OR 
            seat_numbers LIKE '%$search%' OR 
            created_at LIKE '%$search%'";
    $result = $conn->query($sql);
} else {
    // If no search query, retrieve all bookings
    $sql = "SELECT * FROM bookings";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>
    <link rel="icon" type="image/png" href="logo.png">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f2f2f2;
        }

        .navbar {
            background-color: #333;
            overflow: hidden;
            padding: 10px;
            text-align: right;
            margin-right: -200px;
        }

        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            cursor: pointer;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .navbar input[type="text"] {
            padding: 5px;
            margin: 5px;
        }

        .navbar button {
            padding: 5px;
            margin: 5px;
            cursor: pointer;
            background-color: #db044c;
            color: #fff;
            border: none;
        }

        .navbar button:hover {
            background-color: #ff0066;
        }

        .container {
            max-width: 2500px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            margin-top: 2px;
            margin-right: -200px;
            margin-left: -19px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: -20px;
            margin-left: 0px;
        }

        th,
        td {
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

        form {
            display: inline-block;
        }

        button {
            background-color: #db044c;
            color: #fff;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
        }

        button:hover {
            background-color: #ff0066;
        }
        .view-seats-button {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
        }

        .view-seats-button:hover {
            background-color: #2980b9;
        }
        #search{
margin-right: 150px;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <form action="#" method="GET" id="search">
            <input type="text" name="search" placeholder="Search...">
            <button type="submit">Search</button>
        </form>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="Buses.php">Available Buses</a>
        <a href="bookings.php">All Bookings</a>
    </div>

    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            // Fetching and displaying bookings in a table
            echo "<table border='1'>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Travel From</th>
                        <th>Travel To</th>
                        <th>Travel Date</th>
                        <th>Bus Number</th>
                        <th>Departure Time</th>
                        <th>Fare</th>
                        <th>Number of Seats</th>
                        <th>Seat Numbers</th>
                        <th>Created At</th>
                        <th>Action</th>
                        <th>View Seats</th>
                    </tr>";

            while ($row = $result->fetch_assoc()) {
                $bookingId = $row['id'];
                $travelDate = $row['travel_date'];
                $currentDate = date("Y-m-d");

                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['first_name']}</td>";
                echo "<td>{$row['last_name']}</td>";
                echo "<td>{$row['Email']}</td>";
                echo "<td>{$row['travel_from']}</td>";
                echo "<td>{$row['travel_to']}</td>";
                echo "<td>{$row['travel_date']}</td>";
                echo "<td>{$row['bus_number']}</td>";
                echo "<td>{$row['departure_time']}</td>";
                echo "<td>{$row['fare']}</td>";
                echo "<td>{$row['number_of_seats']}</td>";
                echo "<td>{$row['seat_numbers']}</td>";
                echo "<td>{$row['created_at']}</td>";

                // Check if travel date has passed
                if ($travelDate < $currentDate) {
                    echo "<td>Expired</td>";
                } else {
                    echo "<td>
                            <form action='cancel_booking.php' method='post'>
                                <input type='hidden' name='booking_id' value='$bookingId'>
                                <button type='submit'>Cancel</button>
                            </form>
                          </td>";
                
                    // Add a link or button to view available seats
                    echo "<td>
                            <form action='available_seats.php' method='get'>
                                <input type='hidden' name='bus_number' value='{$row['bus_number']}'>
                                <input type='hidden' name='travel_date' value='{$row['travel_date']}'>
                                <button class='view-seats-button' type='submit'>View Available Seats</button>
                            </form>
                          </td>";
                }

                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No bookings found.</p>";
        }
        ?>
    </div>

</body>

</html>
