<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minecraft Server Status</title>
    <link rel="stylesheet" href="../css/pop_up.css">
    <script src="../js/overviewservers.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>

<body>
    <div id="containerPopUpServer">
        <form action="#" method="post">
            <h2>Minecraft Server Status</h2>
            <a class="buttonClose" href="javascript:void(0)" onclick="document.getElementById('containerPopUpServer').style.display='none'">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </a>
            <p id="server-status">Server loading...</p>
            <h1>Minecraft Player List</h1>
            <h2>Players online: <p id="playersOnline"></p>
            </h2>
            <div class="Player_list_container">
                <div id="player-list">
                </div>
            </div>
        </form>
    </div>
</body>

</html>