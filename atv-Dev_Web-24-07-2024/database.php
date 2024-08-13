<?php
$servername = "localhost";
$username = "gustavo";
$password = "1120";
$dbname = "formas";

// Corrigi a remoção do erro de sintaxe
$conn = new mysqli('localhost', 'gustavo', '1120', 'formas', 3307);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
