<?php
include 'database.php';
session_start();

// Check if the user is an admin (assuming level 2 is an admin)
if (!isset($_SESSION['user_id']) || $_SESSION['nivelPermissao'] != 2) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descricao = $_POST['descricao'];
    $titulo = $_POST['titulo'];

    // Insert the new category into the Categorias table
    $sql = "INSERT INTO Categorias (descricao, titulo) VALUES ('$descricao', '$titulo')";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Categoria adicionada com sucesso!</p>";
    } else {
        echo "<p>Erro: " . $sql . "<br>" . $conn->error . "</p>";
    }
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
