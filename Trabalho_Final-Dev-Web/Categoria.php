<?php
class Categoria {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function adicionarCategoria($descricao, $titulo) {
        $sql = "INSERT INTO Categorias (descricao, titulo) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $descricao, $titulo);

        if ($stmt->execute()) {
            return "Categoria adicionada com sucesso!";
        } else {
            return "Erro ao adicionar categoria: " . $this->conn->error;
        }
    }
}
?>
