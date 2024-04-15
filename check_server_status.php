<?php

// IP-adres van je Minecraft-server aanpassen
$server_ip = "77.169.226.112";

// Poort van je Minecraft-server aanpassen
$server_port = 25565;

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

// JSON-string maken
$json_data = json_encode($data);

// JSON-data naar de browser sturen
header('Content-Type: application/json');
echo $json_data;
