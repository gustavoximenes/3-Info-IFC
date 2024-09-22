<?php
require_once("../classes/Triangulo.class.php");
require_once("../classes/Database.class.php");

$id = $_GET['id']; 

try {
    $conexao = Database::getInstance();
    
    // Deletar o triângulo com o ID fornecido
    $sql = "DELETE FROM triangulos WHERE id = :id"; 

    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Erro ao excluir o registro.";
    }
} catch (PDOException $e) {
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
}
?>
