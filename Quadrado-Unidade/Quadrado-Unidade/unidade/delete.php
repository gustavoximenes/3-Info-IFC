<?php
require_once("../classes/Unidade.class.php");
require_once("../classes/Database.class.php");

if (isset($_GET['id']) && is_numeric($_GET['id'])) { // Valida se o ID foi passado corretamente
    $id = $_GET['id'];

    try {
        $conexao = Database::getInstance(); // Obtém a instância do banco de dados
        $sql = "DELETE FROM unidades WHERE id = :id"; // SQL para deletar o registro com base no ID
        $stmt = $conexao->prepare($sql); // Prepara a consulta
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Associa o valor do ID

        if ($stmt->execute()) { // Executa a exclusão
            header("Location: index.php"); // Redireciona para a página index.php após sucesso
            exit(); // Garante que o script termine após o redirecionamento
        } else {
            echo "Erro ao excluir o registro."; // Mensagem de erro se a exclusão falhar
        }
    } catch (PDOException $e) {
        echo "Erro na conexão com o banco de dados: " . $e->getMessage(); // Exibe a mensagem de erro
    }
} else {
    echo "ID inválido."; // Exibe uma mensagem se o ID for inválido ou não numérico
}
?>
