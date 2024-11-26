<?php
class Autor {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function adicionarAutor($nome, $sobrenome) {
        $sql = "INSERT INTO Autor (nome, sobrenome) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $nome, $sobrenome);

        if ($stmt->execute()) {
            return "Autor adicionado com sucesso.";
        } else {
            return "Erro ao adicionar autor: " . $this->conn->error;
        }
    }
}
?>
