<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management</title>
    <link rel="icon" type="image/png" href="logo.png">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 15px;
        }

        button {
            background-color: #db044c;
            color: #fff;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background-color: #ff0066;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Admin Management</h2>
        <ul>
            <?php
            include("connection.php");

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['removeAdmin'])) {
                $adminToRemove = $conn->real_escape_string($_POST['removeAdmin']);

                // Perform the DELETE operation
                $query = "DELETE FROM admin_users WHERE username = '$adminToRemove'";
                $result = $conn->query($query);

                if ($result) {
                    echo '<script> alert("Admin removed successfully.") </script> ';
                } else {
                    echo "<p>Error removing admin: " . $conn->error . "</p>";
                }
            }

            // Fetch admin usernames from the database
            $query = "SELECT username FROM admin_users";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $username = $row['username'];
                    echo "<li>$username 
                            <form method='post' style='display:inline;'>
                                <input type='hidden' name='removeAdmin' value='$username'>
                                <button type='submit'>Remove Admin</button>
                            </form>
                          </li>";
                }
            } else {
                echo "<li>No admins found</li>";
            }

            $conn->close();
            ?>
        </ul>
    </div>
</body>

</html>
