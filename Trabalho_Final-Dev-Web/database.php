<?php
$servername = "localhost";
$username = "gustavo";
$password = "1120";
$dbname = "Livraria";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
