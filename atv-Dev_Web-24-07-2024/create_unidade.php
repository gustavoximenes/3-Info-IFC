<?php
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $abreviacao = $_POST['abreviacao'];

    $sql = "INSERT INTO unidade_medida (nome, abreviacao) VALUES ('$nome', '$abreviacao')";

    if ($conn->query($sql) === TRUE) {
        echo "Nova unidade de medida cadastrada com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Unidade de Medida</title>
</head>
<body>
    <h1>Cadastrar Unidade de Medida</h1>
    <form action="create_unidade.php" method="post">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required>
        <br>
        <label for="abreviacao">Abreviação:</label>
        <input type="text" name="abreviacao" id="abreviacao" required>
        <br>
        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>
