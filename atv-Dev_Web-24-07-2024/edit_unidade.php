<?php
require 'database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM unidade_medida WHERE id=$id";
    $result = $conn->query($sql);
    $unidade = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $abreviacao = $_POST['abreviacao'];

    $sql = "UPDATE unidade_medida SET nome='$nome', abreviacao='$abreviacao' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Unidade de medida atualizada com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Unidade de Medida</title>
</head>
<body>
    <h1>Editar Unidade de Medida</h1>
    <form action="edit_unidade.php" method="post">
        <input type="hidden" name="id" value="<?php echo $unidade['id']; ?>">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" value="<?php echo $unidade['nome']; ?>" required>
        <br>
        <label for="abreviacao">Abreviação:</label>
        <input type="text" name="abreviacao" id="abreviacao" value="<?php echo $unidade['abreviacao']; ?>" required>
        <br>
        <input type="submit" value="Atualizar">
    </form>
</body>
</html>
