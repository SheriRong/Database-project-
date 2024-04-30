<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
   header("Location: user_page.php");
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['login_username'];
    $password = $_POST['login_password'];

    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    $query = "SELECT * FROM P_User WHERE Username='$username' AND Password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "Login successful!";

    } else {
        echo "Invalid username or password. Please try again.";
    }
}
$conn->close();
?>
