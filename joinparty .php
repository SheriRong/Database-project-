 <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Join a Party</title>
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

         input[type="text"] {
             width: 100%;
             padding: 12px;
             margin-bottom: 20px;
             box-sizing: border-box;
             border: 1px solid #ddd;
             border-radius: 6px;
             font-size: 16px;
         }

         button[type="submit"] {
             background-color: #007bff;
             color: #fff;
             border: none;
             border-radius: 6px;
             padding: 12px 20px;
             font-size: 16px;
             cursor: pointer;
             width: 100%;
         }

         button[type="submit"]:hover {
             background-color: #0056b3;
         }

         .btn-primary {
             background-color: #ff4500;
             border-color: #ff4500;
             padding: 12px 24px;
             font-size: 16px;
             transition: background-color 0.3s ease, border-color 0.3s ease;
             display: block;
             margin-top: 20px;
         }

         .btn-primary:hover {
             background-color: #e04107;
             border-color: #e04107;
         }
     </style>
 </head>
 <body>
 <div class="container">
     <h1>Join a Party</h1>
     <form method="post">
         <label for="party_id">Enter Party ID:</label>
         <input type="text" id="party_id" name="party_id">
         <button type="submit" class="btn btn-primary">Join Party</button>
     </form>

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

     if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["party_id"])) {
         $partyID = $_POST["party_id"];
         $userID = $_SESSION["userID"];

         
         $checkQuery = "SELECT * FROM P_Party WHERE PartyID = '$partyID'";
         $checkResult = $conn->query($checkQuery);

         if ($checkResult->num_rows > 0) {
             
             $joinQuery = "INSERT INTO P_Membership (PartyID, UserID) VALUES ('$partyID', '$userID')";
             if ($conn->query($joinQuery) === TRUE) {
                 echo "<p>Joined party successfully!</p>";
             } else {
                 echo "<p>Error joining the party. Please try again.</p>";
             }
         } else {
             echo "<p>Party ID does not exist. Please enter a valid Party ID.</p>";
         }
     }

     $conn->close();
     ?>

     <a href="party.php" class="btn btn-primary">Back to My Parties</a>
 </div>
 </body>
 </html>
