<?php
require 'database.php';
require 'Quadrado.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lado = $_POST['lado'];
    $cor = $_POST['cor'];

    // Verifica se o valor do lado é um número válido
    if (is_numeric($lado) && $lado > 0 && preg_match('/^#[0-9A-Fa-f]{6}$/', $cor)) {
        $query = "UPDATE quadrado SET lado = ?, cor = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("dsi", $lado, $cor, $id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Erro ao atualizar: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Por favor, insira valores válidos para o lado e a cor.";
    }
} else {
    $query = "SELECT * FROM quadrado WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $quadrado = $result->fetch_assoc();

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Quadrado</title>
</head>
<body>
    <h1>Editar Quadrado</h1>
    <form action="edit.php?id=<?php echo $id; ?>" method="post">
        <label for="lado">Lado:</label>
        <input type="text" name="lado" id="lado" value="<?php echo $quadrado['lado']; ?>" required>
        <label for="cor">Cor:</label>
        <input type="color" name="cor" id="cor" value="<?php echo $quadrado['cor']; ?>" required>
        <input type="submit" value="Atualizar">
    </form>
</body>
</html>
