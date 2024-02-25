<?php
session_start();
include("connection.php");

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin_login.php");
    exit();
}

// Check if a search query is present
$searchQuery = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';'';

// Modify the SQL query to include search functionality
$sql = "SELECT * FROM bus WHERE 
        bus_number LIKE '%$searchQuery%' OR 
        fare LIKE '%$searchQuery%' OR 
        departure_time LIKE '%$searchQuery%' OR 
        available_seats LIKE '%$searchQuery%' OR 
        from_location LIKE '%$searchQuery%' OR 
        to_location LIKE '%$searchQuery%'";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Management</title>
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
        .button{
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            background: none; 
            cursor: pointer; 
            border: none;
            font-size: 16px;
        }
        .button:hover{
            background-color: #ddd;
            color: black;
        }


        .search-container {
            float: right;
            margin-right: 10px;
        }

        .search-container input[type="text"] {
            padding: 6px;
            margin-top: 8px;
            margin-right: 16px;
            border: none;
        }

        .search-container button {
            padding: 6px;
            margin-top: 8px;
            margin-right: 10px;
            background: #f44336;
            border: none;
            cursor: pointer;
            color: white;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .container {
            max-width: 2500px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            margin-top: 0px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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

        .edit-button, .delete-button {
            padding: 8px 16px;
            margin: 5px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }

        .edit-button {
            background-color: #4caf50;
            color: #fff;
        }

        .delete-button {
            background-color: #f44336;
            color: #fff;
        }
        h2{
            margin-top: 2px;
        }
        #addBusFormOverlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 100;
        }

        #addBusForm {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #f5f5f5;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }

        #addBusForm label {
            display: block;
            margin-bottom: 8px;
        }

        #addBusForm input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        #addBusForm button {
            background-color: #333;
            color: #fff;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        #addBusForm button:hover {
            background-color: #555;
        }

        #addBusForm button,
        #addBusForm [type="submit"],
        #addBusForm [type="reset"] {
            margin-right: 10px;
        }

        #addBusForm button:last-child {
            margin-right: 0;
        }
        .delete-button{
            border: none;
            background-color: red;
            color: white;
        }
    </style>
</head>

<body>
<div class="navbar">
   
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="bookings.php">Bookings</a>
        <button class="button" onclick="toggleAddBusForm()">Add Bus</button>
        <div class="search-container">
            <form action="Buses.php" method="GET">
                <input type="text" placeholder="Search..." name="search">
                <button type="submit">Search</button>
            </form>
        </div>
    </div>

    <div class="container">
        <h2><a href="Buses.php" style="text-decoration: none;color:#333">All Buses</a></h2>
        <?php
        // Check if there are buses in the database
        if ($result->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>Bus Number</th><th>Fare</th><th>Departure Time</th><th>Available Seats</th><th>From Location</th><th>To Location</th><th>Action</th></tr>";

            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                $busId = $row['bus_id'];
                $busNumber = $row['bus_number'];
                $fare = $row['fare'];
                $departureTime = $row['departure_time'];
                $availableSeats = $row['available_seats'];
                $fromLocation = $row['from_location'];
                $toLocation = $row['to_location'];
            
                echo "<tr id='busRow_$busId'>";
                echo "<td class='editable' data-field='bus_number'>$busNumber</td>";
                echo "<td class='editable' data-field='fare'>$fare</td>";
                echo "<td class='editable' data-field='departure_time'>$departureTime</td>";
                echo "<td class='editable' data-field='available_seats'>$availableSeats</td>";
                echo "<td class='editable' data-field='from_location'>$fromLocation</td>";
                echo "<td class='editable' data-field='to_location'>$toLocation</td>";
                echo "<td>
                      <button class='edit-button' onclick='toggleEdit($busId)'>Edit</button> | 
                      <button class='delete-button' onclick='deleteBus($busId)'>Delete</button> 
                      </td>";
                echo "</tr>";
            }
            
            
            
            echo "</table>";
        } else {
            echo "No buses available.";
        }

        ?>
    </div>

    <!-- Floating Add Bus Form -->
    <div id="addBusFormOverlay">
    <div id="addBusForm">
        <h2>Add Bus</h2>
        <form action="add_bus_process.php" method="POST" id="addBusForm">
            <!-- Include form fields for bus details -->
            <label for="bus_number">Bus Number:</label>
            <input type="text" name="bus_number" required>

            <label for="fare">Fare:</label>
            <input type="text" name="fare" required>

            <label for="departure_time">Departure Time:</label>
            <input type="text" name="departure_time" required>

            <label for="available_seats">Available Seats:</label>
            <input type="text" name="available_seats" required>

            <label for="from_location">From Location:</label>
            <select name="from_location" required>
                <option value="Eldoret">Eldoret</option>
                <option value="Kakamega">Kakamega</option>
                <option value="Kisumu">Kisumu</option>
                <option value="Kisii">Kisii</option>
                <option value="Malindi">Malindi</option>
                <option value="Migori">Migori</option>
                <option value="Mombasa">Mombasa</option>
                <option value="Nairobi">Nairobi</option>
                <option value="Nakuru">Nakuru</option>
                <option value="Narok">Narok</option>
            </select>

            <label for="to_location">To Location:</label>
            <select name="to_location" required>
                <option value="Eldoret">Eldoret</option>
                <option value="Kakamega">Kakamega</option>
                <option value="Kisumu">Kisumu</option>
                <option value="Kisii">Kisii</option>
                <option value="Malindi">Malindi</option>
                <option value="Migori">Migori</option>
                <option value="Mombasa">Mombasa</option>
                <option value="Nairobi">Nairobi</option>
                <option value="Nakuru">Nakuru</option>
                <option value="Narok">Narok</option>
            </select>


                <!-- Add any additional fields as needed -->

                <button type="button" onclick="addBus()">Add Bus</button>
                <button type="button" onclick="toggleAddBusForm()">Close</button>
            </form>
        </div>
    </div>
        

    <script>
       function deleteBus(busId) {
    // Ask for confirmation before deleting
    var confirmDelete = confirm("Are you sure you want to delete this bus?");
    
    if (confirmDelete) {
        // Perform AJAX request to delete_bus.php
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_bus.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    // Handle the response from delete_bus.php
                    alert(xhr.responseText);
                    if (xhr.responseText.includes("successfully")) {
                        // Redirect to Buses.php after successful deletion
                        window.location.href = "Buses.php";
                    }
                } else {
                    // Handle the case when the request fails
                    alert("Failed to delete the bus. Please try again.");
                }
            }
        };
 
        // Send the request with the bus ID
        xhr.send("bus_id=" + encodeURIComponent(busId));
    }
}
function toggleAddBusForm() {
            var addBusFormOverlay = document.getElementById("addBusFormOverlay");
            addBusFormOverlay.style.display = (addBusFormOverlay.style.display === "none") ? "block" : "none";
        }
        function addBus() {
    // Retrieve form data
    var busData = {
        bus_number: document.querySelector("#addBusForm [name='bus_number']").value,
        fare: document.querySelector("#addBusForm [name='fare']").value,
        departure_time: document.querySelector("#addBusForm [name='departure_time']").value,
        available_seats: document.querySelector("#addBusForm [name='available_seats']").value,
        from_location: document.querySelector("#addBusForm [name='from_location']").value,
        to_location: document.querySelector("#addBusForm [name='to_location']").value
    };

    // Perform AJAX request
    fetch('add_bus_process.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(busData),
    })
    .then(response => response.text())
    .then(result => {
        // Handle the result, you can display a message or perform other action
        alert('Bus added successfully.');
        // Optionally, you can refresh the page or update the bus list
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to add the bus. Please try again.');
    });
}
function toggleEdit(busId) {
    var busRow = document.getElementById("busRow_" + busId);
    var editableCells = busRow.getElementsByClassName("editable");

    // Toggle contentEditable
    for (var i = 0; i < editableCells.length; i++) {
        editableCells[i].contentEditable = (editableCells[i].contentEditable === "true") ? "false" : "true";
    }

    var editButton = busRow.querySelector(".edit-button");

    // Change button text based on edit mode
    if (editButton.innerHTML === "Edit") {
        editButton.innerHTML = "Save";
    } else {
        // Retrieve edited values
        var editedData = {};

        for (var i = 0; i < editableCells.length; i++) {
            var fieldName = editableCells[i].getAttribute("data-field");
            editedData[fieldName] = editableCells[i].textContent;
        }

        // Perform AJAX request to update_bus.php
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_bus.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    // Handle the response from update_bus.php
                    alert(xhr.responseText);
                    if (xhr.responseText.includes("successfully")) {
                        // Toggle back to "Edit" mode
                        editButton.innerHTML = "Edit";
                    }
                } else {
                    // Handle the case when the request fails
                    alert("Failed to update the bus. Please try again.");
                }
            }
        };

        // Send the request with the bus ID and edited values
        var params = "bus_id=" + encodeURIComponent(busId);

        for (var fieldName in editedData) {
            params += "&" + encodeURIComponent(fieldName) + "=" + encodeURIComponent(editedData[fieldName]);
        }

        xhr.send(params);
    }
}


    </script>
</div>

</body>

</html>
