<?php
$host = 'localhost';
$port = 3307;  // Porta do MySQL
$dbname = 'story_telling';
$username = 'gustavo';
$password = '1120';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>
