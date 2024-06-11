<?php 

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) { 
    echo "Error: Unable to connect to MySQL: " . $conn->connect_error;
    die("Connection failed: " . $conn->connect_error);
}
?>