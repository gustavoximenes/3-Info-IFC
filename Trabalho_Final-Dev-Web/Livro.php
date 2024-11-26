<?php
class Livro {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function adicionarLivro($titulo, $anoPublicacao, $fotoCapa, $idCategoria, $preco, $idAutor) {
        $sql = "INSERT INTO Livro (titulo, anoPublicacao, fotoCapa, idCategoria, preco, idAutor) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssids", $titulo, $anoPublicacao, $fotoCapa, $idCategoria, $preco, $idAutor);

        if ($stmt->execute()) {
            return "Livro adicionado com sucesso.";
        } else {
            return "Erro ao adicionar livro: " . $this->conn->error;
        }
    }

    public function obterCategorias() {
        $sql = "SELECT id, titulo FROM categorias";
        return $this->conn->query($sql);
    }

    public function obterAutores() {
        $sql = "SELECT id, nome FROM Autor";
        return $this->conn->query($sql);
    }
}
?>
