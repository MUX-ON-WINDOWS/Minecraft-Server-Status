<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once 'connection.php';

// Assuming Server_ID is set to 1
$server_id = 1;

// Fetch data from database
$sql = "SELECT * FROM `MC_Server` WHERE Server_ID = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $server_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if data exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Store server IP and port in session
    $_SESSION['server_ip'] = $row['server_ip'];
    $_SESSION['server_port'] = $row['server_port'];
} else {
    echo "No server found with Server_ID 1";
}

$stmt->close(); // Close statement
$conn->close(); // Close database connection

// IP-adres van je Minecraft-server aanpassen
$server_ip = "147.185.221.19";

// Poort van je Minecraft-server aanpassen
$server_port = "28500";

// Controleer de serverstatus
$socket = @fsockopen($server_ip, $server_port, $errno, $errstr, 0.5);

if ($socket) {
  fclose($socket);
  $server_status = "Online";
} else {
  $server_status = "Offline";
}

// JSON-object met serverstatus
$data = array(
  "status" => $server_status
);

?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Minecraft Server Status</title>
  <link rel="stylesheet" href="../css/main.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      var serverStatus = <?php echo json_encode($data); ?>;
      if (serverStatus.status === "Online") {
            $("#server-status").text(serverStatus.status);
            $("#server-status").addClass("online");
          } else {
            $("#server-status").text(serverStatus.status);
            $("#server-status").removeClass("online");
          }
    });
  </script>
</head>
<body>
  <div class="Status_container">
    <form action="../php/logout.php" method="post">
      <input type="submit" value="Logout">
    </form>
    <h1>Welkom terug <?php echo $_SESSION['username'] ?>!</h1>

    <?php if(isset($_SESSION['server_ip']) && isset($_SESSION['server_port'])): ?>
        <h2>Server IP: <?php echo $_SESSION['server_ip']; ?></h2>
        <h2>Server Port: <?php echo $_SESSION['server_port']; ?></h2>
    <?php else: ?>
        <p>No server data available</p>
    <?php endif; ?>

    <h1>Minecraft Server Status</h1>
    <p id="server-status">Server loading...</p>
  </div>
</body>
</html>