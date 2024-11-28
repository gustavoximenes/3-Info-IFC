<?php
include 'Usuario.php';

class Cliente extends Usuario {
    public function registrarSessao($user) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nivelPermissao'] = $user['nivelPermissao'];
    }
}
?>
