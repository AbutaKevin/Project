<?php
session_start();
include("connection.php");
include("function.php");

$user_data = check_login($conn);
if (!$user_data) {
    header("Location: login.php");
    exit();
}

// Check if the form was submitted
if (isset($_POST['cancel'])) {
    $bookingId = $_POST['id'];



    if ($stmt->execute()) {
        $_SESSION['cancellation_successful'] = true;
    } else {
        $_SESSION['cancellation_successful'] = false;
        $_SESSION['cancellation_message'] = 'Error deleting booking.';
    }

   

    // Redirect back to this page to avoid resubmission on page refresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch the user's bookings for display
$email = $user_data['Email'];
$fetchBookingsQuery = "SELECT * FROM bookings WHERE Email = ?";
$stmt = $conn->prepare($fetchBookingsQuery);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Cancellation</title>
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<div class="tags">   
     <a href="index.php">HomePage</a>
        <a href="MyAccount.php">My Account</a>
        <a href="Location.php">Book Again</a></div>


    <script>
   function cancelTicket(bookingId) {
    var userConfirmed = confirm('Are you sure you want to cancel the ticket? PLEASE NOTE REFUND WILL BE PROCESSED WITHIN 24 HOURS!!');

    if (userConfirmed) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'cancel_ticket.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Show success message
                        alert('The ticket has been successfully cancelled. Wait for the refund in less than 24 hours!!!');
                        // Reload the page after successful cancellation
                        location.reload();
                    } else {
                        // Show error message
                        alert(response.message);
                    }
                } else {
                    // Handle the case when the request fails
                    alert('Failed to cancel the ticket. Please try again.');
                }
            }
        };
        xhr.send('cancel=true&id=' + bookingId);
    }
}

</script>


    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
        }

        h2 {
            color: #333;
            text-align: center;
            text-transform: uppercase;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .cancel-button {
            background-color: #db044c;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .cancel-button:hover {
            background-color: #ff0066;
        }

        .alert {
            display: none;
            background-color: #4CAF50;
            color: #fff;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }
        a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            text-decoration: none;
            color: #333;
            background-color: #fff;
            border-radius: 5px;
            font-weight: bold;
        }

        a:hover {
            background-color: #ddd;
        }
        .tags{
            margin-left: 450px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Ticket Cancellation</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Booking ID</th><th>From</th><th>To</th><th>Travel Date</th><th>Bus Number</th><th>Departure Time</th><th>Fare</th><th>Number of Seats</th><th>Action</th></tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['travel_from']}</td>";
                echo "<td>{$row['travel_to']}</td>";
                echo "<td>{$row['travel_date']}</td>";
                echo "<td>{$row['bus_number']}</td>";
                echo "<td>{$row['departure_time']}</td>";
                echo "<td>{$row['fare']}</td>";
                echo "<td>{$row['number_of_seats']}</td>";

                // Check if the travel date has passed
                $currentDate = date('Y-m-d');
                if ($row['travel_date'] < $currentDate) {
                    echo "<td>Expired</td>";
                } else {
                    echo "<td>";

                    echo "<button type='button' class='cancel-button' onclick='cancelTicket({$row['id']})'>Cancel</button>";

                    echo "</td>";
                }

                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No bookings found for cancellation.</p>";
        }
        ?>
<?php if (isset($_SESSION['cancellation_successful']) && $_SESSION['cancellation_successful']) : ?>
    <div class="alert">
        The ticket has been successfully cancelled. Wait for the refund in less than 24 hours!!!
    </div>
    <?php unset($_SESSION['cancellation_successful']); ?>
<?php endif; ?>

        <?php if (isset($_SESSION['cancellation_message'])) : ?>
            <div class="alert">
                <?php echo $_SESSION['cancellation_message']; ?>
            </div>
            <?php unset($_SESSION['cancellation_message']); ?>
        <?php endif; ?>
        
    </div>



    <script>
        setTimeout(function () {
            document.querySelectorAll('.alert').forEach(function (alert) {
                alert.style.display = 'none';
            });
        }, 5000);
    </script>
</body>

</html>
