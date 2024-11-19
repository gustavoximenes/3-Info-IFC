<?php
include 'database.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Query para deletar o livro pelo ID
    $sql = "DELETE FROM Livro WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirecionar para a página principal com uma mensagem de sucesso
        header("Location: index.php?message=Book+deleted+successfully");
        exit;
    } else {
        // Exibir mensagem de erro
        echo "Error: " . $conn->error;
    }
} else {
    // Redirecionar caso o ID não seja fornecido
    header("Location: index.php?error=Invalid+book+ID");
    exit;
}
