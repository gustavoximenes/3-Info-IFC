<?php
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lado = $_POST['lado'];
    $cor = $_POST['cor'];

    // Verifica se o valor do lado é um número válido
    if (is_numeric($lado) && $lado > 0 && preg_match('/^#[0-9A-Fa-f]{6}$/', $cor)) {
        $query = "INSERT INTO quadrado (lado, cor) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ds", $lado, $cor);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Erro ao cadastrar: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Por favor, insira valores válidos para o lado e a cor.";
    }
}

$conn->close();
?>
