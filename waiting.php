<?php
session_start();


if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ./login.php");
    exit;
}

$hostname = 'mysql.eecs.ku.edu';
$username = '447s24_m401c456';
$password = 'ohzie7Pu';
$database = '447s24_m401c456';


$conn = new mysqli($hostname, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userID = $_SESSION["userID"];


$query = "SELECT A.AttractionID, A.AttractionName, I.ReadyTime 
          FROM P_InLine AS I 
          JOIN P_Attraction AS A ON I.AttractionID = A.AttractionID 
          WHERE I.PartyID IN (SELECT PartyID FROM P_Membership WHERE UserID = $userID)";

$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attractions in Line</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
       
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('https://ilimoww.com/wp-content/uploads/2022/12/GetPaidStock.com_-6399998ecee15.webp');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 30px;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1, h3 {
            color: #ff4500;
            text-align: center;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
            text-align: center;
        }

        .btn-primary {
            background-color: #ff4500;
            border-color: #ff4500;
            padding: 12px 24px;
            font-size: 16px;
            transition: background-color 0.3s ease, border-color 0.3s ease;
            display: block;
            margin: 20px auto;
        }

        .btn-primary:hover {
            background-color: #e04107;
            border-color: #e04107;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Attractions in Line</h1>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div>';
            echo '<h3>' . $row["AttractionName"] . '</h3>';
            echo '<p>Ready Time: ' . $row["ReadyTime"] . '</p>';
            echo '<form method="post">';
            echo '<input type="hidden" name="attractionID" value="' . $row["AttractionID"] . '">';
            echo '<button type="submit" name="leave_line" class="btn btn-danger">Leave the Line</button>';
            echo '</form>';
            echo '</div>';
        }
    } else {
        echo '<p>No attractions found in line.</p>';
    }
    ?>

    <a href="./index.php" class="btn btn-primary">Back</a>
</div>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["leave_line"])) {
    $attractionID = $_POST["attractionID"];


    $deleteQuery = "DELETE FROM P_InLine 
                    WHERE AttractionID = $attractionID 
                    AND PartyID IN (SELECT PartyID FROM P_Membership WHERE UserID = $userID)";

    if ($conn->query($deleteQuery) === TRUE) {
        echo '<script>alert("You have left the line for the attraction.");</script>';
        
        echo '<script>window.location.href = "./waiting.php";</script>';
        exit;
    } else {
        echo '<script>alert("Failed to leave the line. Please try again.");</script>';
    }
}

$conn->close();
?>

</body>
</html>
