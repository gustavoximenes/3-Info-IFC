<?php
include 'Usuario.php';

class Administrador extends Usuario {
    // O construtor agora recebe a conexão
    public function __construct($conn) {
        parent::__construct($conn); // Chama o construtor da classe pai (Usuario)
    }

    /**
     * Adiciona um novo usuário ao sistema.
     *
     * @param string $email
     * @param string $senha
     * @param int $nivelPermissao
     * @return bool|string Retorna true em caso de sucesso ou uma mensagem de erro em caso de falha.
     */
    public function adicionarUsuario($email, $senha, $nivelPermissao) {
        // Gera o hash da senha
        $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

        // Usando o método de conexão herdado de Usuario
        $sql = "INSERT INTO Usuario (email, senha, nivelPermissao) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $email, $senhaHash, $nivelPermissao);

        if ($stmt->execute()) {
            return true; // Sucesso
        } else {
            return "Erro ao adicionar usuário: " . $this->conn->error;
        }
    }

    /**
     * Remove um usuário do sistema pelo ID.
     *
     * @param int $idUsuario
     * @return bool|string Retorna true em caso de sucesso ou uma mensagem de erro em caso de falha.
     */
    public function removerUsuario($idUsuario) {
        // Usando a conexão herdada de Usuario
        $sql = "DELETE FROM Usuario WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idUsuario);

        if ($stmt->execute()) {
            return true; // Sucesso
        } else {
            return "Erro ao remover usuário: " . $this->conn->error;
        }
    }

    /**
     * Lista todos os usuários cadastrados.
     *
     * @return array|bool Retorna um array com os dados dos usuários ou false em caso de erro.
     */
    public function listarUsuarios() {
        // Usando a conexão herdada de Usuario
        $sql = "SELECT id, email, nivelPermissao FROM Usuario";
        $result = $this->conn->query($sql);

        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return false; // Erro ao obter a lista
        }
    }
}
?>
