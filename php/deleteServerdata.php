<?php
// delete_server.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['server_name']) && isset($_POST['server_ip']) && isset($_POST['server_port']) && isset($_POST['server_url'])) {
        $serverName = $_POST['server_name'];
        $serverIp = $_POST['server_ip'];
        $serverPort = $_POST['server_port'];
        $serverUrl = $_POST['server_url'];

        // Function to delete server from the database
        function deleteServer($serverName, $serverIp, $serverPort, $serverUrl)
        {
            require 'connection.php'; // Assuming this file contains your database connection

            $sql = "DELETE FROM `mc_server` WHERE `server_name` = ? AND `server_ip` = ? AND `server_port` = ? AND `url` = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $serverName, $serverIp, $serverPort, $serverUrl);

            if ($stmt->execute()) {
                echo "Server deleted successfully";
                header("Location: index.php");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $stmt->close();
            $conn->close();
        }

        // Call the function to delete the server
        deleteServer($serverName, $serverIp, $serverPort, $serverUrl);
    } else {
        echo "Missing parameters for deleting the server.";
    }
} else {
    echo "Invalid request method.";
}
