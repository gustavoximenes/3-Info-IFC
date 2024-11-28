<?php

class Usuario {
    protected $conn;

    public function __construct($conn) {
        $this->conn = $conn; // Recebe a conexão e a armazena
    }

    /**
     * Método para autenticar o usuário no sistema.
     *
     * @param string $email
     * @param string $senha
     * @return bool|array Retorna os dados do usuário autenticado ou false em caso de erro.
     */
    public function autenticar($email, $senha) {
        $sql = "SELECT * FROM Usuario WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($senha, $user['senha'])) {
                return $user;
            }
        }
        return false;
    }
}
?>
