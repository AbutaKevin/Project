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

// Retrieve total fare from the URL
$totalFare = isset($_GET['fare']) ? $_GET['fare'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    $firstName = $user_data['FirstName'];
    $lastName = $user_data['LastName'];
    $email = $user_data['Email'];

    $busDetails = isset($_SESSION['busDetails']) ? $_SESSION['busDetails'] : [];
    $from = $busDetails['from'] ?? '';
    $to = $busDetails['to'] ?? '';
    $travelDate = $busDetails['travelDate'] ?? '';
    $fare = $busDetails['fare'] ?? '';
    $bus_number = $busDetails['bus_number'] ?? '';
    $departure_time = $busDetails['departure_time'] ?? '';
    $numberOfSeats = $busDetails['numberOfSeats'] ?? 0;
    $seatNumbersArray = $busDetails['seatNumbers'] ?? [];
    if (is_string($seatNumbersArray)) {
        $seatNumbersArray = json_decode($seatNumbersArray, true);
    } elseif (!is_array($seatNumbersArray)) {
        $seatNumbersArray = [];
    }

 
    $checkQuery = "SELECT * FROM bookings WHERE first_name = ? AND last_name = ? AND Email = ? AND travel_from = ? AND travel_to = ? AND travel_date = ? AND bus_number = ? AND departure_time = ? AND fare = ? AND number_of_seats = ? AND seat_numbers = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("sssssssssss", $firstName, $lastName, $email, $from, $to, $travelDate, $bus_number, $departure_time, $fare, $numberOfSeats, json_encode($seatNumbersArray));
    $checkStmt->execute();
    $checkStmt->store_result();
    $existingRecords = $checkStmt->num_rows;
    
  
    if ($existingRecords == 0) {
        $insertQuery = "INSERT INTO bookings (first_name, last_name, Email, travel_from, travel_to, travel_date, bus_number, departure_time, fare, number_of_seats, seat_numbers) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("sssssssssss", $firstName, $lastName, $email, $from, $to, $travelDate, $bus_number, $departure_time, $fare, $numberOfSeats, json_encode($seatNumbersArray));
        $insertStmt->execute();
        $insertStmt->close();

        header("Location: ticket.php");
        exit();

    } else {
        echo "<script>alert('You have already booked!!!.');</script>";
        header("Location: ticket.php");
        exit();
    }

    $checkStmt->close();
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/png" href="logo.png">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            padding: 20px;
            margin-top: 50px;
        }

        h2 {
            color: #333;
        }

        .payment-methods {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .payment-method {
            text-align: center;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .payment-method:hover {
            background-color: #f0f0f0;
        }

        .payment-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .payment-button:hover {
            background-color: #45a049;
        }

        #fareAmount {
            margin-top: 20px;
            font-size: 18px;
            color: #333;
        }

        .payment-method img {
            max-width: 50px;
            max-height: 30px;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Payment Options</h2>
        <div id="fareAmount">
            <?php
            echo "Fare Amount: KSH. " . $totalFare;
            ?>
        </div>
        <div class="payment-methods">
            <div class="payment-method" onclick="payWithMpesa()">
                <img src="mpesa_logo.png" alt="M-pesa Logo">
                M-pesa
            </div>
            <div class="payment-method" onclick="payWithAirtelMoney()">
                <img src="airtel_logo.png" alt="Airtel Money Logo">
                Airtel Money
            </div>
            <div class="payment-method" onclick="payWithCard()">
                <img src="card_logo.jpg" alt="Card Logo">
                Credit/Debit Card
            </div>
        </div>
        <form id="confirmPaymentForm" method="post" action="">
            <!-- Your HTML form elements here -->
            <button type="button" class="payment-button" onclick="confirmPayment()">Confirm Payment</button>
            <input type="hidden" name="confirm_payment" value="1">
        </form>
    </div>

    <script>
        function payWithMpesa() {
            // Implement M-pesa payment logic here
            alert('Redirecting to M-pesa payment gateway');
}

        function payWithAirtelMoney() {
            // Implement Airtel Money payment logic here
            alert('Redirecting to Airtel Money payment gateway');
        }

        function payWithCard() {
            // Implement Credit/Debit Card payment logic here
            alert('Redirecting to Credit/Debit Card payment gateway');
        }

        function confirmPayment() {
            document.getElementById('confirmPaymentForm').submit();
            alert('Payment confirmed. Booked successfully!!');
        }
    </script>
</body>

</html>