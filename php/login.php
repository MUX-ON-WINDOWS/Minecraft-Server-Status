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
$sql = "SELECT * FROM minecraftloginserver WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    $trimmedPassword = trim($password); // Trim any whitespace

    // Check if the provided password matches the hashed password
    $passwordVerifyResult = password_verify($trimmedPassword, $row['password']);
    
    if ($passwordVerifyResult) {
        // Password is correct, redirect to dashboard or wherever you need
        $_SESSION['username'] = $username;
        header("location: dashboard.php");
        exit;
    } else {
        // Password is incorrect, redirect back to login page
        echo "Invalid username or password.";
    }
} else {
    // User not found, redirect back to login page
    echo "Invalid username or password.";
}

$conn->close();
?>
