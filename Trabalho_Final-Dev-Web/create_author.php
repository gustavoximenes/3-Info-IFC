<?php
include 'database.php';
include 'Autor.php';
session_start();

// Verifica se o usuário é administrador (nivelPermissao 2)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['nivelPermissao']) || $_SESSION['nivelPermissao'] != 2) {
    header("Location: index.php");
    exit();
}

$mensagem = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];

    // Instancia a classe Autor e adiciona o autor
    $autor = new Autor($conn);
    $mensagem = $autor->adicionarAutor($nome, $sobrenome);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Criar Novo Autor</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Adicionar Novo Autor</h2>
    <!-- Exibe mensagens de sucesso ou erro -->
    <?php if ($mensagem): ?>
        <div class="alert alert-info"><?= $mensagem ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" class="form-control" name="nome" required>
        </div>
        <div class="form-group">
            <label for="sobrenome">Sobrenome:</label>
            <input type="text" class="form-control" name="sobrenome" required>
        </div>
        <button type="submit" class="btn btn-primary">Adicionar Autor</button>
    </form>
</div>
</body>
</html>
