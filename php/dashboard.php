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
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script>

    document.addEventListener("DOMContentLoaded", function() {
    function checkServerStatus() {
        // Make a GET request to the API endpoint
        axios.get('https://api.mcstatus.io/v2/status/java/rest-consolidated.gl.joinmc.link')
            .then(function(response) {

                if (response.data.online === true) {
                    document.getElementById("server-status").textContent = "Server is online";
                    document.getElementById("server-status").classList.add("online");
                } else {
                    document.getElementById("server-status").textContent = "Server is offline";
                    document.getElementById("server-status").classList.remove("online");
                }
                for(var i = 0; i < 10; i++) {
                    console.log(response.data.players.list[i])

                    const players = response.data.players.list[i].name_clean;
                    const image = response.data.players.list[i].uuid;
                    console.log(players);
                    displayPlayerList(players, image);
                }
            })
            .catch(function(error) {
                // Handle error
                console.error('Error fetching server status:', error);
            });
    }

    // Initial check
    checkServerStatus();

    setInterval(checkServerStatus, 10000);

    function displayPlayerList(players, image) {
        // Get the container element for the player list
        var playerListContainer = document.getElementById("player-list");

        var playerListItem = document.createElement("div");
        playerListItem.classList.add("player-list-item");

        // Create a new image element for the avatar
        var imgAvatar = document.createElement("img");
        imgAvatar.src = "https://crafatar.com/avatars/" + image; // Set the source of the image
        imgAvatar.alt = players; // Set alt attribute for accessibility

        // Create a text node for the player name
        var playerNameNode = document.createElement("p");
        playerNameNode.textContent = players;

        // Append the image and player name to the list item
        playerListItem.appendChild(imgAvatar);
        playerListItem.appendChild(playerNameNode);

        // Append the list item to the container
        playerListContainer.appendChild(playerListItem);
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
    <h1>Minecraft Player List</h1>   
    <div class="Player_list_container">   
        <div id="player-list">
        </div>
    </div>
  </div>
  </div>
</body>
</html>