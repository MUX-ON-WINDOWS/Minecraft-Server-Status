<?php 
// Database connection
// $host = "rdbms.strato.de";
// $username = "dbu508350";
// $password = "Maxieboy16!";
// $database = "dbs12796139";

$host = "localhost";
$username = "root";
$password = "";
$database = "dbs12796139";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) { 
    echo "Error: Unable to connect to MySQL: " . $conn->connect_error;
    die("Connection failed: " . $conn->connect_error);
}
?>