<?php
require_once("../classes/Database.class.php");
require_once("../classes/Unidade.class.php");

class Triangulo {
    private $id;
    private $lado1;
    private $lado2;
    private $lado3;
    private $cor;
    private $unidade;

    public function __construct($id, $lado1, $lado2, $lado3, $cor, $unidade) {
        $this->id = $id;
        $this->lado1 = $lado1;
        $this->lado2 = $lado2;
        $this->lado3 = $lado3;
        $this->cor = $cor;
        $this->unidade = $unidade; // Assume que unidade é um objeto da classe Unidade
    }

    public function getId() { return $this->id; }
    public function getLado1() { return $this->lado1; }
    public function getLado2() { return $this->lado2; }
    public function getLado3() { return $this->lado3; }
    public function getCor() { return $this->cor; }
    public function getUnidade() { return $this->unidade; }

    public static function listar($tipo, $busca) {
        // Método para listar triângulos conforme busca no banco
        $sql = "SELECT t.*, f.cor, f.id_unidade, u.tipo 
                FROM triangulos t 
                JOIN formas f ON f.id = t.id_forma 
                JOIN unidades u ON f.id_unidade = u.id";

        if ($tipo == 1) {
            $sql .= " WHERE t.id = :busca";
        }
        
        $conexao = Database::conectar();
        $stmt = $conexao->prepare($sql);
        
        if ($tipo == 1) {
            $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
        }

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $lista = [];
        foreach ($result as $row) {
            // Verifique se a chave 'id_unidade' existe
            $id_unidade = isset($row['id_unidade']) ? $row['id_unidade'] : null;
            $unidade = new Unidade($id_unidade, $row['tipo']);
            $lista[] = new Triangulo($row['id'], $row['lado1'], $row['lado2'], $row['lado3'], $row['cor'], $unidade);
        }
        return $lista;
    }

    public function incluir() {
        // Incluir novo triângulo no banco de dados
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

            // Incluir triângulo usando o ID da forma
            $sql_triangulo = "INSERT INTO triangulos (id_forma, lado1, lado2, lado3) VALUES (:id_forma, :lado1, :lado2, :lado3)";
            $parametros_triangulo = [
                ':id_forma' => $id_forma,
                ':lado1' => $this->lado1,
                ':lado2' => $this->lado2,
                ':lado3' => $this->lado3
            ];

            return Database::executar($sql_triangulo, $parametros_triangulo);
        } catch (Exception $e) {
            throw new Exception("Erro ao incluir triângulo: " . $e->getMessage());
        }
    }

    // Outros métodos como `alterar` e `excluir` podem ser adicionados conforme necessário
}
