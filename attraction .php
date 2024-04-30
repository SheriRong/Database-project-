<?php
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ./login.php");
    exit;
}

$hostname = 'mysql.eecs.ku.edu';
$username = '447s24_m401c456';
$password = 'ohzie7Pu';
$database = '447s24_m401c456';

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve all attractions with their ready time
$query = "SELECT AttractionID, AttractionName, ReadyTime 
          FROM P_Attraction";

$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Attractions</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles */
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
    <h1>Explore Attractions</h1>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div>';
            echo '<h3>' . $row["AttractionName"] . '</h3>';
            echo '<p>Ready Time: ' . $row["ReadyTime"] . '</p>';
            echo '<form method="post">';
            echo '<input type="hidden" name="attractionID" value="' . $row["AttractionID"] . '">';
            echo '<button type="submit" name="join_line" class="btn btn-primary">Join in Line</button>';
            echo '</form>';
            echo '</div>';
        }
    } else {
        echo '<p>No attractions found.</p>';
    }
    ?>

    <a href="./index.php" class="btn btn-primary">Back</a>
</div>

<?php
// Handle joining the line for an attraction
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["join_line"])) {
    $attractionID = $_POST["attractionID"];
    $userID = $_SESSION["userID"];

    // Check if the attraction is at capacity
    $checkCapacityQuery = "SELECT COUNT(*) AS currentCapacity FROM P_Membership WHERE PartyID IN 
                           (SELECT PartyID FROM P_InLine WHERE AttractionID = $attractionID)";

    $capacityResult = $conn->query($checkCapacityQuery);
    $currentCapacity = $capacityResult->fetch_assoc()["currentCapacity"];

    // Get attraction capacity and update ready time if needed
    $getCapacityQuery = "SELECT Capacity FROM P_Attraction WHERE AttractionID = $attractionID";
    $capacityResult = $conn->query($getCapacityQuery);
    $attractionCapacity = $capacityResult->fetch_assoc()["Capacity"];

    if ($currentCapacity >= $attractionCapacity) {
        // Update ready time by 10 minutes if at capacity
        $updateReadyTimeQuery = "UPDATE P_InLine SET ReadyTime = DATE_ADD(ReadyTime, INTERVAL 10 MINUTE) 
                                 WHERE AttractionID = $attractionID";
        $conn->query($updateReadyTimeQuery);
    } else {
        // Insert new line entry for the user
        $insertLineQuery = "INSERT INTO P_InLine (PartyID, AttractionID, ReadyTime) 
                            VALUES ((SELECT PartyID FROM P_Membership WHERE UserID = $userID), $attractionID, NOW())";
        $conn->query($insertLineQuery);
    }

    echo '<script>window.location.href = "./attraction.php";</script>';
    exit;
}

$conn->close();
?>

</body>
</html>
