<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require_once 'connection.php';

if (!isset($_SESSION["username"]) && !isset($_SESSION["server_id"])) {
  header("Location: ../index.html");
  exit;
} else {
  // Fetch data from database
  $user = $_SESSION['username'];

  // Fetch data from database
  $sql = "SELECT * FROM `minecraftLoginServer` 
          INNER JOIN `MC_Server` 
          ON `minecraftLoginServer`.`server_id` = `MC_Server`.`server_id` 
          WHERE `minecraftLoginServer`.`username` = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $user);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if data exists
  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      // data naar session zetten
      $_SESSION['url'] = $row['url'];
      $_SESSION['server_id'] = $row['server_id'];
      $_SESSION['server_ip'] = $row['server_ip'];
      $_SESSION['server_port'] = $row['server_port'];
  } else {
      echo "No server found for user: $user";
  }


  $stmt->close(); 
  $conn->close(); 

  $server_ip = $_SESSION['server_ip'];
  $server_port =  $_SESSION['server_port'];

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

}
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

    <?php if(isset($_SESSION['server_ip']) && isset($_SESSION['server_port']) && isset($_SESSION['url'])): ?>
        <h2>Server URL: <br> <?php echo $_SESSION['url']; ?></h2>
    <?php else: ?>
        <p>No server data available</p>
    <?php endif; ?>

    <h1>Minecraft Server Status</h1>
    <p id="server-status">Server loading...</p>
  </div>
</body>
</html>