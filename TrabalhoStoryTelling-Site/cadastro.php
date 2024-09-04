<?php
require 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (nome, email, cpf, senha) VALUES (:nome, :email, :cpf, :senha)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':senha', $senha);

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Erro ao realizar o cadastro!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cadastro - Story-Telling</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-nav .nav-link {
            color: black;
            font-weight: bold;
        }
        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 100px);
        }
        .register-box {
            background-color: white;
            padding: 30px;
            border: 1px solid #ccc;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        .register-box input {
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

    <div class="container register-container">
        <div class="register-box">
            <h2 class="text-center">Cadastro</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF:</label>
                    <input type="text" id="cpf" name="cpf" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" class="form-control" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-secondary">Cadastrar</button>
                </div>
            </form>
            <div class="text-center mt-3">
                <a href="login.php" class="btn btn-secondary">Ir para Login</a>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>Direitos Autorais</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
