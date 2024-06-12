<script>
    function deleteServer(serverName, serverUrl, serverIp, serverPort) {
        console.log(serverName, serverUrl, serverIp, serverPort);
        document.getElementById('containerPopUpDelete').style.display = 'block';

        // Set the server details as data attributes in the form
        document.getElementById('deleteServerForm').setAttribute('data-server-name', serverName);
        document.getElementById('deleteServerForm').setAttribute('data-server-ip', serverIp);
        document.getElementById('deleteServerForm').setAttribute('data-server-port', serverPort);
        document.getElementById('deleteServerForm').setAttribute('data-server-url', serverUrl);
    }

    function confirmDelete() {
        var serverName = document.getElementById('deleteServerForm').getAttribute('data-server-name');
        var serverIp = document.getElementById('deleteServerForm').getAttribute('data-server-ip');
        var serverPort = document.getElementById('deleteServerForm').getAttribute('data-server-port');
        var serverUrl = document.getElementById('deleteServerForm').getAttribute('data-server-url');

        // Send an AJAX request to delete the server
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                // Optionally, you can handle the response here
            }
        };
        xhttp.open("POST", "deleteServerdata.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("server_name=" + serverName + "&server_ip=" + serverIp + "&server_port=" + serverPort + "&server_url=" + serverUrl);

        document.getElementById('containerPopUpDelete').style.display = 'flex';
        location.reload();
    }
</script>

<body>
    <div id="containerPopUpDelete">
        <form id="deleteServerForm" action="#" method="post">
            <h2>Delete server</h2>
            <p>Are you sure you want to delete this server?</p>
            <div class="containerButtonsDelete">
                <input class="cancelButton" type="button" value="Cancel" onclick="document.getElementById('containerPopUpDelete').style.display='none'">
                <input class="confirmDelete" type="button" value="Delete server" onclick="confirmDelete()">
            </div>
        </form>
    </div>
</body>