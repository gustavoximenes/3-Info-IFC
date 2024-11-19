<?php
include 'database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM Usuario WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($senha, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nivelPermissao'] = $user['nivelPermissao']; // Armazenando o nível de permissão
            header("Location: index.php");
        } else {
            echo "Credenciais inválidas";
        }
    } else {
        echo "Usuário não encontrado";
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
