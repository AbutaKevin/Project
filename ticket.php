<?php
session_start();
include("connection.php");
include("function.php");

$user_data = check_login($conn);


if (!$user_data) {
    header("Location: login.php");
    exit();
}


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
    $numberOfSeatsValue = $numberOfSeats; 
    $seatNumbers = $busDetails['seatNumbers'] ?? [];

    $seatNumbersArray = [];
    if (is_string($seatNumbers)) {
        $seatNumbersArray = json_decode($seatNumbers, true);
    } elseif (is_array($seatNumbers)) {
        $seatNumbersArray = $seatNumbers;
    }
 

?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/logo.png">
    <title>KeviBus Travellers - Ticket Details</title>
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
            text-align: center;
            text-transform: uppercase;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 10px;
            color: #555;
        }

        .ticket-details {
            padding: 20px;
            background-color: #eee;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .ticket-details span {
            font-weight: bold;
        }

        .footer {
            text-align: center;
            color: #777;
            margin-top: 20px;
        }

        .button {
            display: inline-block;
            margin: 10px auto;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.1s;
            margin-left: 5px;
            color: white;
            background-color: dodgerblue;
        }

        .buttons {
            text-align: center;
        }

        .button:hover {
            background-color: blue;
        }
    </style>

</head>

<body>
    <div class="container">
        <h2>KeviBus Travellers - Ticket Details</h2>
        <div class="ticket-details">
            <p><span>Passenger:</span> <?php echo $firstName . ' ' . $lastName; ?></p>
            <p><span>Email:</span> <?php echo $email; ?></p>
            <p><span>From:</span> <?php echo $from; ?></p>
            <p><span>To:</span> <?php echo $to; ?></p>
            <p><span>Travel Date:</span> <?php echo $travelDate; ?></p>
            <p><span>Bus Number:</span> <?php echo $bus_number; ?></p>
            <p><span>Fare:</span> <?php echo $fare; ?></p>
            <p><span>Departure Time:</span> <?php echo $departure_time; ?></p>
            <p><span>Number of Seats:</span> <?php echo $numberOfSeats; ?></p>
            <p><span>Seat Numbers:</span> <?php echo implode(', ', $seatNumbersArray); ?></p>
        </div>
    </div>

    <div class="buttons">
        <div class="button" onclick="sendToEmail()">Send to Email</div>
        <a class="button" href="#" id="downloadLink" download="bus_ticket.html" onclick="downloadTicket()">Download</a>
        <div class="button" onclick="goToHomepage()">Go To Homepage</div>
        <div class="button" onclick="cancel()">Cancel Ticket</div>
    </div>

    <script>
        function sendToEmail() {
            var ticketDetails = document.querySelector('.container').outerHTML;

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "send_email.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("ticketDetails=" + encodeURIComponent(ticketDetails));

            // Dummy alert for demonstration purposes
            alert("Email sent successfully!");
        }

        function downloadTicket() {
            var ticketDetails = document.querySelector('.container').outerHTML;
            var blob = new Blob([ticketDetails], { type: 'text/html' });
            var url = window.URL.createObjectURL(blob);

            var link = document.createElement('a');
            link.href = url;
            link.download = 'bus_ticket.html';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(url);
        }

        function goToHomepage() {
            alert("Thanks for choosing us...!");
            window.location.href = 'index.php'
        }

        function cancel() {
            alert("Are you Sure you want to cancel the ticket?");
            window.location.href = 'Cancel.php'
        }
    </script>
</body>

</html>
