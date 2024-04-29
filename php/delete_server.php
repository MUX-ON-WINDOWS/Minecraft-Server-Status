<?php
// delete_server.php
if(isset($_GET['serverName']) && isset($_GET['serverUrl']) && isset($_GET['serverIp']) && isset($_GET['serverPort'])) {
    $serverName = $_GET['serverName'];
    $serverUrl = $_GET['serverUrl'];
    $serverIp = $_GET['serverIp'];
    $serverPort = $_GET['serverPort'];

    deleteServerPHP($serverName, $serverIp, $serverPort, $serverUrl);
}

function deleteServerPHP($serverName, $serverIp, $serverPort, $serverUrl)
{
    require 'connection.php';

    $sql = "DELETE FROM `mc_server` WHERE `server_name` = ? AND `server_ip` = ? AND `server_port` = ? AND `url` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $serverName, $serverIp, $serverPort, $serverUrl);
    $stmt->execute();

    $stmt->close();
    $conn->close();
    header("Refresh:0");
}
?>
