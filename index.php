<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo "<script>window.location.href='./login.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Amusement Park Tracker</title>
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

      .alert {
          background-color: #e9ecef;
          color: #495057;
          padding: 15px;
          border-radius: 8px;
          margin-bottom: 20px;
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

      .nav-link {
          font-size: 18px;
          color: #007bff;
          transition: color 0.3s ease;
      }

      .nav-link:hover {
          color: #0056b3;
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
      <div class="alert">
          Welcome! You are now signed in to your amusement park tracker account.
      </div>
      <h1>Hello, <?= $_SESSION["username"]; ?>!</h1>
      <p>Tell us what you want to explore today:</p>
      <ul class="nav flex-column">
          <li class="nav-item">
              <a class="nav-link active" href="party.php">My Party</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="waiting.php">My Waiting</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="attraction.php">Explore Attractions</a>
          </li>
      </ul>
      <a href="./logout.php" class="btn btn-primary">Log Out</a>
  </div>
  </body>
  </html>