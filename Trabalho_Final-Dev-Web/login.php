<?php
include 'database.php';
include 'Cliente.php';

session_start();
$mensagemErro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Instancia a classe Cliente
    $cliente = new Cliente($conn);

    // Tenta autenticar o usuário
    $user = $cliente->autenticar($email, $senha);

    if ($user) {
        // Registra a sessão para o usuário autenticado
        $cliente->registrarSessao($user);

        // Redireciona para a página inicial
        header("Location: index.php");
        exit();
    } else {
        $mensagemErro = "Credenciais inválidas ou usuário não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Login</h2>

    <!-- Exibe mensagem de erro, se houver -->
    <?php if ($mensagemErro): ?>
        <div class="alert alert-danger"><?= $mensagemErro ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" class="form-control" name="senha" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <br>
    <!-- Botão de registro para redirecionar para register.php -->
    <a href="register.php" class="btn btn-secondary">Registrar</a>
</div>
</body>
</html>
