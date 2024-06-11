<?php
session_start();

require_once 'connection.php';

$errormessageuser = "";
$errormessagepass = "";

if (isset($_POST['sign-btn'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $repeatpassword = trim($_POST['repeatpassword']);

    // Passwords matching check
    if ($password != $repeatpassword) {
        $errormessagepass = "Passwords do not match.";
    } else {
        // Prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM minecraftloginserver WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errormessageuser = "Username already exists.";
        } else {
            // Hash password
            $hashedpass = password_hash($password, PASSWORD_DEFAULT);
            // Insert new user using prepared statement
            $stmt = $conn->prepare("INSERT INTO minecraftloginserver (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashedpass);
            if ($stmt->execute()) {
                // User registered successfully, redirect to login page
                header("location: ../index.php");
                exit;
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <div class="Status_container">
        <h2>Sign up</h2>
        <form action="../php/registration.php" method="post">
            <!-- Username Field -->
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            <!-- Error Message for Username -->
            <?php if (!empty($errormessageuser)) : ?>
                <p style="color: red;"><?php echo $errormessageuser; ?></p>
            <?php endif; ?>

            <!-- Password Fields -->
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <label for="password">Repeat Password:</label>
            <input type="password" id="repeatpassword" name="repeatpassword" required><br><br>
            <!-- Error Message for Passwords -->
            <?php if (!empty($errormessagepass)) : ?>
                <p style="color: red;"><?php echo $errormessagepass; ?></p>
            <?php endif; ?>

            <!-- Submit Button -->
            <input type="submit" value="Sign up" name="sign-btn">
        </form>
        <!-- Sign-in Link -->
        <p>All ready have an account? <a href="../index.php" style="text-decoration: none; color: #007BFF;"> Sign in</a></p>
    </div>
</body>

</html>