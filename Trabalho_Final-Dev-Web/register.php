<?php
include 'database.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $nivelPermissao = $_POST['nivelPermissao']; // Pega o nível de permissão do formulário
    
    $sql = "INSERT INTO Usuario (nome, email, senha, nivelPermissao) VALUES ('$nome', '$email', '$senha', '$nivelPermissao')";
    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Register</h2>
    <form method="post">
        <div class="form-group">
            <label for="nome">Name:</label>
            <input type="text" class="form-control" name="nome" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="form-group">
            <label for="senha">Password:</label>
            <input type="password" class="form-control" name="senha" required>
        </div>
        <div class="form-group">
            <label for="nivelPermissao">Permission Level:</label>
            <select class="form-control" name="nivelPermissao" required>
                <option value="1">1 - Normal User</option>
                <option value="2">2 - Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>
</body>
</html>
