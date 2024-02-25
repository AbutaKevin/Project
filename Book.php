<?php
session_start();
include("connection.php");
include("function.php");

$user_data = check_login($conn);
if (!$user_data) {
    header("Location: login.php");
    exit();
}


if (isset($_POST['search'])) {
    $from = $_POST['from'];
    $to = $_POST['to'];
    $travelDate = $_POST['travelDate'];

    
    $totalSeatsConstant = 52;

   
    $sql = "SELECT bus.*, SUM((LENGTH(bookings.seat_numbers) - LENGTH(REPLACE(bookings.seat_numbers, ',', '')) + 1)) AS booked_seats
    FROM bus
    LEFT JOIN bookings ON bus.bus_number = bookings.bus_number AND bookings.travel_date = '$travelDate'
    WHERE bus.from_location = '$from' AND bus.to_location = '$to'
    GROUP BY bus.bus_id";
$result = $conn->query($sql);

   



}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Selection</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(image3.jpg) no-repeat;
            background-position: center;
            background-size: cover;
            color: #fff;
        }

        .center-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .bus-table {
            max-width: 800px;
            width: 100%;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        a {
            text-decoration: none;
            color: #db044c;
            font-weight: bold;
        }

        a:hover {
            color: #ff0066;
        }

        a.choose-another {
            display: inline-block;
            margin-top: 20px;
            color: #db044c;
            font-weight: bold;
            text-decoration: none;
        }

        a.choose-another:hover {
            color: #ff0066;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="center-container">
                    <div class="bus-table">
                        <!-- Displaying selected locations, travel date, and available buses -->
                        <?php
                        if (isset($_POST['search']) && $result->num_rows > 0) {
                            echo "<h2>Selected Locations:</h2>";
                            echo "<p>From: $from</p>";
                            echo "<p>To: $to</p>";
                            echo "<p>Travel Date: $travelDate</p>";
            
                            echo "<h2>Available Buses:</h2>";
                            echo "<table>";
                            echo "<tr><th>Bus Number</th><th>Departure Time</th><th>Fare</th><th>Available Seats</th><th>Action</th></tr>";
            
                            while ($row = $result->fetch_assoc()) {
                                $bus_id = $row['bus_id'];
                                $bus_number = $row['bus_number'];
                                $fare = $row['fare'];
                                $departure_time = $row['departure_time'];
                                $booked_seats = $row['booked_seats'];
                                $total_seats = $row['available_seats'];
            
                                $available_seats = $totalSeatsConstant - $booked_seats;
            
                                echo "<tr>";
                                echo "<td>{$bus_number}</td>";
                                echo "<td>{$departure_time}</td>";
                                echo "<td>{$fare}</td>";
                                echo "<td>{$available_seats}</td>";
                                echo "<td>
                    <a href='seatgrid.php?from={$from}&to={$to}&travelDate={$travelDate}&bus_id={$bus_id}&fare={$fare}&bus_number={$bus_number}&departure_time={$departure_time}'>Select Seat</a>
                  </td>";
                                echo "</tr>";
                            }
            
                            echo "</table>";
            
                            echo "<p><a href='Location.php' class='choose-another'>Choose Another Destination</a></p>";
                        } elseif (isset($_POST['search'])) {
                            echo "<p>No buses available for the selected route.</p>";
                        }
                        ?>
               
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
