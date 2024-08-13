<?php
require 'database.php';

$id = $_GET['id'];

$query = "DELETE FROM quadrado WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php");
    exit();
} else {
    echo "Erro ao excluir: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
