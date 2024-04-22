<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'connection.php';

$user = $_SESSION['username'];

$sql = "SELECT * FROM `minecraftloginserver` 
INNER JOIN `mc_server` 
ON `minecraftloginserver`.`server_id` = `mc_server`.`server_id` 
WHERE `minecraftloginserver`.`username` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

// Check if data exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['user_id'];
    $id = $_SESSION['user_id'];
} else {
    echo "No servers found for user: $user";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $server_name = $_POST['server_name'];
    $server_ip = $_POST['server_ip'];
    $server_port = $_POST['server_port'];
    $server_url = $_POST['server_url'];

    // Prepare and bind SQL statement to prevent SQL injection
    $sql = "INSERT INTO mc_server (server_name, server_ip, server_port, url, user_id) VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $server_name, $server_ip, $server_port, $server_url, $id);

    // Execute the statement
    if ($stmt->execute()) {
        // Success popup message function. Redirect to overview page
        
        header("location: overviewservers.php");
    } else {
        // Failed popup message function. Redirect to overview page

        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<body>
    <div id="containerPopUp">
        <form action="#" method="post">
            <h2>Add server</h2>
            <a class="buttonClose" href="javascript:void(0)" onclick="document.getElementById('containerPopUp').style.display='none'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>  
            </a>

            <label for="server_name">Server Name:</label><br>
            <input type="text" id="server_name" name="server_name"><br><br>
            
            <label for="server_ip">Server IP:</label><br>
            <input type="text" id="server_ip" name="server_ip"><br><br>
            
            <label for="server_port">Server Port:</label><br>
            <input type="text" id="server_port" name="server_port"><br><br>
            
            <label for="server_url">Server URL:</label><br>
            <input type="text" id="server_url" name="server_url"><br><br>
            
            <input type="submit" value="Add server">
        </form>
    </div>
</body>