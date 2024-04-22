function addServer() {
    const showPopUp = document.getElementById("containerPopUp");
    showPopUp.style.display = "block";
    console.log("addServer");
}

function overviewServer(serverUrl, serverIp, serverPort) {
    const showPopUp = document.getElementById("containerPopUpServer");
    showPopUp.style.display = "block";
    checkServerStatus(serverUrl, serverIp, serverPort);
}

function checkServerStatus(serverUrl, serverIp, serverPort) {
    // Make a GET request to the API endpoint
    let url;
    if (serverIp && serverPort) {
        url = `https://api.mcstatus.io/v2/status/${serverIp}/${serverPort}`;
    } else {
        url = `https://api.mcstatus.io/v2/status/java/${serverUrl}`;
    }

    axios.get(url)
    .then(function(response) {
        const serverStatus = document.getElementById("server-status");
        if (response.data.online === true) {
            serverStatus.textContent = "Server is online";
            serverStatus.classList.add("online");
        } else {
            serverStatus.textContent = "Server is offline";
            serverStatus.classList.remove("online");
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
        const serverStatus = document.getElementById("server-status");

        if(error.response.status === 400) { 
            serverStatus.textContent = "Server is offline";
            serverStatus.classList.remove("online");
        }
    });
}

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

function setServerStatusOffline(serverName) {
    const serverStatusElements = document.querySelectorAll(`.server-status-dashboard[data-server-name="${serverName}"]`);
    serverStatusElements.forEach(function(element) {
        element.textContent = "Offline";
        element.classList.add("offlinedashboardservers");
        element.classList.remove("onlinedashboardservers");
    });
}
