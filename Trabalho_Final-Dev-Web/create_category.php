<?php
include 'database.php';
include 'Categoria.php';
session_start();

// Verifica se o usuário é administrador (nível de permissão 2)
if (!isset($_SESSION['user_id']) || $_SESSION['nivelPermissao'] != 2) {
    header("Location: index.php");
    exit();
}

$mensagem = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descricao = $_POST['descricao'];
    $titulo = $_POST['titulo'];

    // Instancia a classe Categoria e adiciona a categoria
    $categoria = new Categoria($conn);
    $mensagem = $categoria->adicionarCategoria($descricao, $titulo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Adicionar Categoria</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Adicionar Nova Categoria</h2>
    <!-- Exibe mensagens de sucesso ou erro -->
    <?php if ($mensagem): ?>
        <div class="alert alert-info"><?= $mensagem ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <input type="text" class="form-control" name="descricao" required>
        </div>
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" class="form-control" name="titulo" required>
        </div>
        <button type="submit" class="btn btn-primary">Adicionar Categoria</button>
    </form>
</div>
</body>
</html>
