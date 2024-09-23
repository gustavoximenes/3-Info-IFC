<?php
require_once("../classes/Database.class.php");
require_once("../classes/Unidade.class.php");

class TrianguloEquilatero {
    private $id;
    private $lado; // Apenas um lado para triângulos equiláteros
    private $cor;
    private $unidade;

    public function __construct($id, $lado, $cor, $unidade) {
        $this->id = $id;
        $this->lado = $lado; // Apenas um lado
        $this->cor = $cor;
        $this->unidade = $unidade; // Assume que unidade é um objeto da classe Unidade
    }

    public function getId() { return $this->id; }
    public function getLado() { return $this->lado; }
    public function getCor() { return $this->cor; }
    public function getUnidade() { return $this->unidade; }
    
    public function incluir($id_triangulo) {
        // Incluir novo triângulo equilátero no banco de dados
        try {
            // Incluir triângulo equilátero usando o ID do triângulo existente
            $sql_triangulo_equilatero = "INSERT INTO triangulos_equilateros (id_triangulo, altura) VALUES (:id_triangulo, :altura)";
            $parametros_triangulo_equilatero = [
                ':id_triangulo' => $id_triangulo,
                ':altura' => ($this->lado * sqrt(3)) / 2 // Calcula a altura do triângulo equilátero
            ];
    
            return Database::executar($sql_triangulo_equilatero, $parametros_triangulo_equilatero);
        } catch (Exception $e) {
            throw new Exception("Erro ao incluir triângulo: " . $e->getMessage());
        }
    }
    
}