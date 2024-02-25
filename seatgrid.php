<?php
session_start();
include("connection.php");
include("function.php");

$user_data = check_login($conn);
if (!$user_data) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['bus_id']) && isset($_GET['fare'])) {
    $bus_id = $_GET['bus_id'];
    $fare = $_GET['fare'];
    $bus_number = $_GET['bus_number'];
    $departure_time = $_GET['departure_time'];
    $to = $_GET['to'];
    $from = $_GET['from'];
    $travelDate = $_GET['travelDate'];
}

// Store details in session for later retrieval
$_SESSION['busDetails'] = [
    'bus_id' => $bus_id,
    'fare' => $fare,
    'bus_number' => $bus_number,
    'departure_time' => $departure_time,
    'to' => $to,
    'from' => $from,
    'travelDate' => $travelDate,
];

$query = "SELECT seat_numbers FROM bookings WHERE bus_number = ? AND travel_date = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $bus_number, $travelDate);
$stmt->execute();
$result = $stmt->get_result();
$bookedSeats = [];

while ($row = $result->fetch_assoc()) {
    $bookedSeats[] = json_decode($row['seat_numbers'], true); // Decode JSON string into an array
}

// Flatten the array of booked seat numbers
$bookedSeats = array_merge(...$bookedSeats);

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seat Selection</title>
    <link rel="icon" type="image/png" href="logo.png">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <style>
                body, html {
            height: 100%;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 100vh;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5));
            background-position: center;
            background-size: cover;
            color: #fff;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            color: #000;
        }

        .seat-selection-section {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .seat-selection-section h2 {
            margin-top: 0px;
        }

        .seat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 5px;
        }

        .seat {
            width: 40px;
            height: 30px;
            border: 1px solid #bbb;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            user-select: none;
            background-color: #bdc3c7;
            color: black;
        }

        .available {
            background-color: #bdc3c7;
            color: #fff;
        }

        .selected {
            background-color: #2196F3;
            color: #fff;
        }

        .booked {
            background-color: #e74c3c;
            color: #fff;
        }

        .between {
            width: 40px;
            background-color: transparent;
            visibility: visible;
        }

        .legend {
            margin-top: -10px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
        }

        .legend-item span {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 5px;
            border-radius: 5px;
        }

        .button {
            padding: 10px 20px;
            background-color: dodgerblue;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 0px;
            border: none;
        }

        .button:hover {
            background-color: blue;
        }



#totalFare {
    font-weight: bold;
}
.fare p{
    font-weight: bold;
}
a.choose-another {
    display: inline-block;
    color: #db044c;
    font-weight: bold;
    text-decoration: none;
}

a.choose-another:hover {
    color: #ff0066;}
    </style>
</head>

<body>

<div class="container">
    <section class="seat-selection-section">
        <h2>Seat Selection</h2>

        <div class="legend">
            <div class="legend-item"><span class="available"></span> Available</div>
            <div class="legend-item"><span class="selected"></span> Selected</div>
            <div class="legend-item"><span class="booked"></span> Booked</div>
        </div>
        <div class="seat-grid" id="seatGrid">
            <?php
            $totalRows = 13;
            $totalColumns = 4;
            $totalSeats = $totalRows * $totalColumns;

            for ($row = 1; $row <= $totalRows; $row++) {
                for ($column = 1; $column <= $totalColumns; $column++) {
                    $seatNumber = ($row - 1) * $totalColumns + $column;
                    $seatClass = 'seat';

                    if (in_array($seatNumber, $bookedSeats)) {
                        $seatClass .= ' booked';
                    } else {
                        $seatClass .= ' available';
                    }

                    echo '<div class="' . $seatClass . '" data-seat="' . $seatNumber . '" style="color: black;">' . $seatNumber . '</div>';

                }
            }
            ?>
        </div>
        <div class="fare">
            <?php
            if (isset($_GET['bus_id']) && isset($_GET['fare'])) {
                echo "<p>Fare per Seat: $fare</p>";
                echo "<p>Total Fare: <span id='totalFare'>0</span></p>";;
            }
            ?>
        </div>

        <button class="button book-button" onclick="bookSelectedSeats(<?php echo $fare; ?>)">Book Selected Seats</button>
        <p><a href='Book.php' class='choose-another'>Choose Another Bus</a>
    </section>
</div>

<script>
    var selectedSeats = [];

    // Function to calculate total fare
    function calculateTotalFare(fare) {
        var totalFare = selectedSeats.length * fare;
        document.getElementById('totalFare').innerText = totalFare;
    }

    // Function to handle seat selection
    function selectSeat(seat) {
        var seatNumber = seat.getAttribute('data-seat');

        // Check if the seat is available
        if (seat.classList.contains('available')) {
            // Toggle selection
            if (!selectedSeats.includes(seatNumber)) {
                selectedSeats.push(seatNumber);
                seat.classList.add('selected');
            } else {
                selectedSeats = selectedSeats.filter(item => item !== seatNumber);
                seat.classList.remove('selected');
            }

            calculateTotalFare(<?php echo $fare; ?>);
        } else {
            alert('This seat is already booked. Please choose another seat.');
        }
    }

    // Populate seats
    var seatElements = document.querySelectorAll('.seat');
    seatElements.forEach(seat => {
        seat.addEventListener('click', function () {
            selectSeat(this);
        });
    });

    function bookSelectedSeats(fare) {
        var totalFare = selectedSeats.length * fare;

        // Redirect to the payment page with the total fare only if seats are selected
        if (selectedSeats.length > 0) {
            // Use AJAX to send selected seats to the server
            $.ajax({
                type: "POST",
                url: "store_selected_seats.php",
                data: {
                    numberOfSeats: selectedSeats.length,
                    seatNumbers: JSON.stringify(selectedSeats)
                },
                success: function (response) {
                    // Redirect to the payment page with the total fare
                    window.location.href = 'payment.php?fare=' + totalFare;
                }
            });
        } else {
            alert('Please select at least one seat before booking.');
        }
    }
</script>

</body>
</html>
