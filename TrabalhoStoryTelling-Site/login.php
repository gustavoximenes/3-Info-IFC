<?php
require 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM users WHERE nome = :nome";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha, $user['senha'])) {
        // Login bem-sucedido, redirecionar para index.php
        header("Location: index.php");
        exit();
    } else {
        echo "Nome ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - Story-Telling</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-nav .nav-link {
            color: black;
            font-weight: bold;
        }
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 100px);
        }
        .login-box {
            background-color: white;
            padding: 30px;
            border: 1px solid #ccc;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        .login-box input {
            background-color: lightgray;
            border: none;
            height: 40px;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            padding: 10px 0;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">Story-Telling</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="Material-Escrito.php">Material</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Analise Narrativa</a></li>
                <li class="nav-item"><a class="nav-link" href="Diagramas.php">Diagramas</a></li>
            </ul>
        </div>
    </nav>

    <div class="container login-container">
        <div class="login-box">
            <h2 class="text-center">Login</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" class="form-control" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Logar</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <!-- Botão para a tela de cadastro -->
                <a href="cadastro.php" class="btn btn-secondary">Cadastrar-se</a>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2024 Story-Telling. Todos os direitos reservados.</p>
    </div>
</body>
</html>
