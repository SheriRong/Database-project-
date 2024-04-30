<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Parties</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('https://ilimoww.com/wp-content/uploads/2022/12/GetPaidStock.com_-6399998ecee15.webp');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            width: 300px;
            max-width: 80%;
        }

        h1 {
            color: #ff4500;
            font-size: 28px;
            margin-bottom: 30px;
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .nav-link {
            font-size: 18px;
            color: #007bff;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>My Parties</h1>
    <?php
    session_start();

   
    $hostname = 'mysql.eecs.ku.edu';
    $username = '447s24_m401c456';
    $password = 'ohzie7Pu';
    $database = '447s24_m401c456';

    $conn = new mysqli($hostname, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("Location: login.php");
        exit;
    }

    $userID = $_SESSION["userID"];
    $partyQuery = "SELECT P_Party.PartyID, P_Party.PartyName FROM P_Party INNER JOIN P_Membership ON P_Party.PartyID = P_Membership.PartyID WHERE P_Membership.UserID = '$userID'";
    $partyResult = $conn->query($partyQuery);

    if ($partyResult->num_rows > 0) {
        while ($partyRow = $partyResult->fetch_assoc()) {
            $partyID = $partyRow['PartyID'];
            $partyName = $partyRow['PartyName'];
            echo "<p>Party ID: $partyID - Party Name: $partyName</p>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='leave_party_id' value='$partyID'>";
            echo "<button type='submit' class='btn btn-danger'>Leave Party</button>";
            echo "</form>";
        }
    } else {
        echo "<p>You are not currently in any parties.</p>";
    }

 
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["leave_party_id"])) {
        $leavePartyID = $_POST["leave_party_id"];
        $leaveQuery = "DELETE FROM P_Membership WHERE UserID = '$userID' AND PartyID = '$leavePartyID'";
        if ($conn->query($leaveQuery) === TRUE) {
            echo "<p>Successfully left the party.</p>";
          
            echo "<meta http-equiv='refresh' content='0'>";
        } else {
            echo "<p>Error leaving the party.</p>";
        }
    }

    $conn->close();
    ?>

    <a href="joinparty.php" class="nav-link">Join a Party</a>
    <a href="index.php" class="btn btn-primary">Back</a>
</div>
</body>
</html>

