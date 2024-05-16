<?php
session_start();

require_once '../minecraft_server/php/connection.php';

$errormessageuser = "";
$errormessagepass = "";

if(isset($_POST['login-btn'])) {
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
            header("location: ../minecraft_server/php/overviewservers.php");
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
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="Status_container">
        <h2>Login</h2>
        <form action="index.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            <!-- Error Message for Username -->
            <?php if(!empty($errormessageuser)): ?>
                <p style="color: red;"><?php echo $errormessageuser; ?></p>
            <?php endif; ?>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <!-- Error Message for Passwords -->
            <?php if(!empty($errormessagepass)): ?>
                <p style="color: red;"><?php echo $errormessagepass; ?></p>
            <?php endif; ?>
            <input type="submit" value="Login" name="login-btn">
        </form>
        <p>Don't have an account? <a href="/minecraft_server/php/registration.php" style="text-decoration: none; color: #007BFF;"> Sign up</a></p>
    </div>
</body>

</html>