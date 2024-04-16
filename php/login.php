<?php
session_start();

require_once 'connection.php';

// Retrieve username and password from form
$username = $_POST['username'];
$password = $_POST['password'];

// SQL injection prevention
$username = stripslashes($username);
$password = stripslashes($password);
$username = mysqli_real_escape_string($conn, $username);
$password = mysqli_real_escape_string($conn, $password);

// Fetch user from database
$sql = "SELECT * FROM minecraftLoginServer WHERE username='$username' AND password='$password' AND Server_ID LIMIT 1";
$result = $conn->query($sql);

// Check if user exists
if ($result->num_rows == 1) {
    // User authenticated, redirect to welcome page or do whatever you want
    $_SESSION['username'] = $username;
    header("location: dashboard.php");
} else {
    // Authentication failed, redirect back to login page
    echo "Invalid username or password.";
}
$conn->close();
?>
