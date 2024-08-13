<?php
class Quadrado {
    private $lado;
    private $id;
    private $pontos;  // Coleção de objetos Ponto

    public function __construct($lado, $id) {
        $this->lado = $lado;
        $this->id = $id;
        $this->pontos = [];  // Inicializa como um array vazio
    }

    public function getLado() {
        return $this->lado;
    }

    public function getId() {
        return $this->id;
    }

    public function calcularArea() {
        return $this->lado * $this->lado;
    }

    public function calcularPerimetro() {
        return 4 * $this->lado;
    }

    // Método para adicionar um ponto ao quadrado
    public function adicionarPonto(Ponto $ponto) {
        $this->pontos[] = $ponto;
    }

    // Método para obter todos os pontos associados ao quadrado
    public function getPontos() {
        return $this->pontos;
    }
}
?>
