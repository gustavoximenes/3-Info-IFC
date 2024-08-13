<?php
require 'database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM unidade_medida WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Unidade de medida excluída com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}
?>
