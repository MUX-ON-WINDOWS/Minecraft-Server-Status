<?php
session_start();

require_once 'connection.php';

// Retrieve username and password from form
$username = trim($_POST['username']);
$password = trim($_POST['password']);

// SQL injection prevention
$username = stripslashes($username);
$password = stripslashes($password);
$username = mysqli_real_escape_string($conn, $username);
$password = mysqli_real_escape_string($conn, $password);

// Fetch user from database
$sql = "SELECT * FROM minecraftloginserver WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    // User authenticated, redirect to welcome page or do whatever you want
    $_SESSION['username'] = $username;
    header("location: overviewservers.php");
    exit;
} else {
    // Authentication failed, redirect back to login page
    echo "Invalid username or password.";
}

$conn->close();
?>
