<?php
include 'database.php';
include 'Administrador.php';

session_start();

// Verifica se o usuário está autenticado e se é administrador
if (!isset($_SESSION['user_id']) || $_SESSION['nivelPermissao'] != 2) {
    header("Location: login.php");
    exit();
}

// Instancia a classe Administrador
$admin = new Administrador($conn);
$mensagem = "";

// Adiciona um novo usuário, se solicitado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adicionar'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $nivelPermissao = $_POST['nivelPermissao'];

    $resultado = $admin->adicionarUsuario($email, $senha, $nivelPermissao);
    $mensagem = $resultado === true ? "Usuário adicionado com sucesso!" : $resultado;
}

// Lista todos os usuários
$usuarios = $admin->listarUsuarios();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Administração</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Painel do Administrador</h2>

    <!-- Mensagem de sucesso ou erro -->
    <?php if ($mensagem): ?>
        <div class="alert alert-info"><?= $mensagem ?></div>
    <?php endif; ?>

    <!-- Formulário para adicionar novo usuário -->
    <h3>Adicionar Novo Usuário</h3>
    <form method="post">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" class="form-control" name="senha" required>
        </div>
        <div class="form-group">
            <label for="nivelPermissao">Nível de Permissão:</label>
            <select class="form-control" name="nivelPermissao" required>
                <option value="1">Cliente</option>
                <option value="2">Administrador</option>
            </select>
        </div>
        <button type="submit" name="adicionar" class="btn btn-primary">Adicionar</button>
    </form>

    <!-- Tabela de usuários cadastrados -->
    <h3>Usuários Cadastrados</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Nível de Permissão</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($usuarios): ?>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= $usuario['id'] ?></td>
                        <td><?= $usuario['email'] ?></td>
                        <td><?= $usuario['nivelPermissao'] == 2 ? 'Administrador' : 'Cliente' ?></td>
                        <td>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="idUsuario" value="<?= $usuario['id'] ?>">
                                <button type="submit" name="remover" class="btn btn-danger btn-sm">Remover</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Nenhum usuário cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
