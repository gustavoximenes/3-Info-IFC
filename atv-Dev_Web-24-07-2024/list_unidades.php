<?php
require 'database.php';

$sql = "SELECT * FROM unidade_medida";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Listar Unidades de Medida</title>
</head>
<body>
    <h1>Unidades de Medida</h1>
    <a href="list_unidades.php">Gerenciar Unidades de Medida</a>

    <a href="create_unidade.php">Cadastrar Nova Unidade de Medida</a>
    <br><br>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Abreviação</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['nome']; ?></td>
            <td><?php echo $row['abreviacao']; ?></td>
            <td>
                <a href="edit_unidade.php?id=<?php echo $row['id']; ?>">Editar</a> |
                <a href="delete_unidade.php?id=<?php echo $row['id']; ?>">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
