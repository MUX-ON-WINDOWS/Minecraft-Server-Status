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
            ON `minecraftloginserver`.`server_id` = `mc_server`.`user_id` 
            WHERE `minecraftloginserver`.`username` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if data exists
    if ($result->num_rows > 0) {
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $rowCount = count($rows);
    } else {
        echo "No servers found for user: $user";
        $rowCount = 0;
    }

    $stmt->close(); 
    $conn->close(); 
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <link rel="stylesheet" type="text/css" href="../css/overviewservers.css">
    <title>Overview Servers</title>
    <script>
document.addEventListener("DOMContentLoaded", function() {
    function checkServerStatus(serverName, serverIp, serverPort) {
        let url;
        if (serverIp && serverPort) {
            url = `https://api.mcstatus.io/v2/status/${serverIp}/${serverPort}`;
        } else {
            url = `https://api.mcstatus.io/v2/status/java/${serverName}`;
        }
        // Make a GET request to the API endpoint
        axios.get(url)
            .then(function(response) {
                const playerOnline = response.data.players.online;
                const serverStatus = response.data.online ? "Online" : "Offline";
                const serverStatusElement = document.querySelector("#server-status-dashboard");
                const totalSeverDisplay = document.querySelector("#totalServersDisplay");
                const totalPlayersDisplay = document.querySelector("#totalPlayersDisplay");

                let playerCount = 0;
                let serverCount = 1;

                // Set online players count
                document.querySelectorAll("#playersOnline").forEach(function(element) {
                    element.textContent = playerOnline + "/" + response.data.players.max;
                    playerCount += playerOnline;
                });
                if (totalPlayersDisplay) {
                    totalPlayersDisplay.textContent = playerCount.toString();
                } else {
                    console.error("Element with id 'totalPlayersDisplay' not found.");
                }

                // Set total servers count
                if (totalSeverDisplay) {
                    totalSeverDisplay.textContent = serverCount.toString();
                } else {
                    console.error("Element with id 'totalSeverDisplay' not found.");
                }


                document.querySelectorAll("#server-status-dashboard").forEach(function(element) {

                    if (response.data.online === true) {
                        element.textContent = "Online";
                        serverCount ++;
                        element.classList.add("onlinedashboardservers");
                        element.classList.remove("offlinedashboardservers");
                    } else if (response.data.online === false) {
                        element.textContent = "Offline";
                        element.classList.add("offlinedashboardservers");
                        element.classList.remove("onlinedashboardservers");
                    }

                });
            })
            .catch(function(error) {
                // Handle error
                console.error('Error fetching server status:', error);
                const serverStatusElement = document.querySelector("#server-status-dashboard");
                if (serverStatusElement) {
                    serverStatusElement.textContent = "Error";
                    serverStatusElement.classList.remove("onlinedashboardservers");
                    serverStatusElement.classList.remove("offlinedashboardservers");
                } else {
                    console.error("Element with id 'server-status-dashboard' not found.");
                }
            });
    }

    // Call checkServerStatus for each server
    <?php foreach ($rows as $row) : ?>
        checkServerStatus(<?php echo json_encode($row['url']); ?>, <?php echo json_encode($row['server_ip']); ?>, <?php echo json_encode($row['server_port']); ?>);
    <?php endforeach; ?>
});

    </script>
    <style> 
    .overview_button {
    padding: 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.overview_button:hover {
    background-color: #0056b3;
}
</style>
</head>
<body>
    <div class="dashboardContainer">
        <div class="containerTitleSection">
            <h1>Server Overview</h1>
            <div class="container_buttons">
                <button class="addserverButton" type="button" onclick="">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <p>Add server</p>
                </button>
                <button class="logoutButton" type="button" onclick="window.location.href='../php/logout.php'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg> 
                    <p class="textLogout">Logout</p>
                </button>
            </div>
        </div>
        <div class="containerOverview">
            <div class="containerItemOverview"> 
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>                  
                <div>
                    <p class="HighlighterText"><span id="totalPlayersDisplay">0</span></p><p class="discriptionText">Players Online</p>
                </div>
            </div>
            <div class="containerItemOverview"> 
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 17.25v-.228a4.5 4.5 0 0 0-.12-1.03l-2.268-9.64a3.375 3.375 0 0 0-3.285-2.602H7.923a3.375 3.375 0 0 0-3.285 2.602l-2.268 9.64a4.5 4.5 0 0 0-.12 1.03v.228m19.5 0a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3m19.5 0a3 3 0 0 0-3-3H5.25a3 3 0 0 0-3 3m16.5 0h.008v.008h-.008v-.008Zm-3 0h.008v.008h-.008v-.008Z" />
                </svg>                                 
                <div>
                    <p class="HighlighterText"><span id="totalServersDisplay">0</span><p class="discriptionText">Severs Online</p>
                </div>
            </div>
            <div class="containerItemOverview"> 
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                </svg>                                  
                <div>
                    <p class="HighlighterText">0</p><p class="discriptionText">?</p>
                </div>
            </div>
            <div class="containerItemOverview"> 
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                </svg>                                  
                <div>
                    <p class="HighlighterText">0</p><p class="discriptionText">?</p>
                </div>
            </div>
        </div>
        <div style="overflow-x: auto;">
        <table>
            <tr class="tableHeaderContainer">
                <th>Server Name</th>
                <th>Server IP</th>
                <th>Server Port</th>
                <th>Server URL</th>
                <th>Server Status</th>
                <th>Players</th>
                <th>Overview</th>
            </tr>
            <?php
            if (isset($rows)) {
                foreach ($rows as $row) {
                    echo '<tr class="tableDataContainer">';
                    echo '<td>' . $row['server_name'] . '</td>';
                    echo '<td>' . ($row['server_ip'] ?? 'No server IP') . '</td>';
                    echo '<td>' . ($row['server_port'] ?? 'No server port') . '</td>';
                    echo '<td>' . $row['url'] . '</td>';
                    echo '<td id="server-status-dashboard">Server loading...</td>';
                    echo '<td id="playersOnline"></td>';
                    echo '<td><button class="overview_button" type="submit" onclick="window.location.href=\'../php/dashboard.php\'">Overview</button></td>';
                    echo '</tr>';
                }
            } else {
                echo "<tr><td colspan='6'>No servers found for user: $user</td></tr>";
            }
            ?>
        </table>
        </div>
    </div>
</body>
</html>
