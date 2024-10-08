<?php
require_once("../classes/Database.class.php");
require_once("../classes/Unidade.class.php");

class Circulo {
    private $id;
    private $raio;
    private $cor;
    private $unidade;

    public function __construct($id, $raio, $cor, $unidade) {
        $this->id = $id;
        $this->raio = $raio;
        $this->cor = $cor;
        $this->unidade = $unidade; // Assume que unidade é um objeto da classe Unidade
    }

    public function getId() { return $this->id; }
    public function getRaio() { return $this->raio; }
    public function getCor() { return $this->cor; }
    public function getUnidade() { return $this->unidade; }

    public function incluir() {
        // Incluir novo círculo no banco de dados
        try {
            // Incluir forma
            $sql_forma = "INSERT INTO formas (cor, id_unidade) VALUES (:cor, :id_unidade)";
            $parametros_forma = [
                ':cor' => $this->cor,
                ':id_unidade' => $this->getUnidade()->getIdUnidade() // Certifique-se de que a unidade existe
            ];
            Database::executar($sql_forma, $parametros_forma);

            // Obter o último ID da tabela formas
            $id_forma = Database::$lastId;

            // Incluir círculo usando o ID da forma
            $sql_circulo = "INSERT INTO circulos (id_forma, raio) VALUES (:id_forma, :raio)";
            $parametros_circulo = [
                ':id_forma' => $id_forma,
                ':raio' => $this->raio
            ];

            return Database::executar($sql_circulo, $parametros_circulo);
        } catch (Exception $e) {
            throw new Exception("Erro ao incluir círculo: " . $e->getMessage());
        }
    }

    public static function listar() {
        // Método para listar círculos conforme busca no banco
        $sql = "SELECT c.*, f.cor, f.id_unidade 
                FROM circulos c 
                JOIN formas f ON f.id = c.id_forma";
        
        $conexao = Database::conectar();
        $stmt = $conexao->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $lista = [];
        foreach ($result as $row) {
            $id_unidade = isset($row['id_unidade']) ? $row['id_unidade'] : null;
            $unidade = new Unidade($id_unidade);
            $lista[] = new Circulo($row['id'], $row['raio'], $row['cor'], $unidade);
        }
        return $lista;
    }

    public static function deletar($id_circulo) {
        // Deletar círculo baseado no ID
        try {
            $sql = "DELETE FROM circulos WHERE id = :id_circulo";
            $parametros = [':id_circulo' => $id_circulo];
            Database::executar($sql, $parametros);
        } catch (Exception $e) {
            throw new Exception("Erro ao deletar círculo: " . $e->getMessage());
        }
    }

    // Outros métodos como `alterar` e `excluir` podem ser adicionados conforme necessário
}
