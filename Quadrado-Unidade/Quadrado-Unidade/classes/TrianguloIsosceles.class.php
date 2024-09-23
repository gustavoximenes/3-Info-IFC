<?php
require_once("../classes/Database.class.php");
require_once("../classes/Unidade.class.php");

class TrianguloIsosceles {
    private $id;
    private $lado1;
    private $lado2;
    private $cor; // Adicionando a propriedade cor
    private $unidade;

    public function __construct($id, $lado1, $lado2, $cor, $unidade) {
        $this->id = $id;
        $this->lado1 = $lado1;
        $this->lado2 = $lado2;
        $this->cor = $cor; // Inicializando a propriedade cor
        $this->unidade = $unidade; // Assume que unidade é um objeto da classe Unidade
    }

    public function getId() { return $this->id; }
    public function getLado1() { return $this->lado1; }
    public function getLado2() { return $this->lado2; }
    public function getCor() { return $this->cor; } // Método para acessar a cor
    public function getUnidade() { return $this->unidade; }

    public static function listar($tipo, $busca) {
        // Método para listar triângulos isósceles conforme busca no banco
        $sql = "SELECT ti.*, t.cor, u.tipo 
                FROM triangulos_isosceles ti 
                JOIN triangulos t ON ti.id_triangulo = t.id 
                JOIN unidades u ON t.id_unidade = u.id";

        if ($tipo == 1) {
            $sql .= " WHERE ti.id = :busca";
        } elseif ($tipo == 2) {
            $sql .= " WHERE ti.lado1 = :busca";
        } elseif ($tipo == 3) {
            $sql .= " WHERE t.cor = :busca";
        } elseif ($tipo == 4) {
            $sql .= " WHERE u.tipo = :busca";
        }

        $conexao = Database::conectar();
        $stmt = $conexao->prepare($sql);
        
        if ($tipo == 1 || $tipo == 2 || $tipo == 3 || $tipo == 4) {
            $stmt->bindParam(':busca', $busca);
        }

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $lista = [];
        foreach ($result as $row) {
            $id_unidade = $row['id_unidade'];
            $unidade = new Unidade($id_unidade, $row['tipo']);
            $lista[] = new TrianguloIsosceles($row['id'], $row['lado1'], $row['lado2'], $row['cor'], $unidade);
        }
        return $lista;
    }

    public function incluir() {
        // Incluir novo triângulo isósceles no banco de dados
        try {
            // Incluir forma na tabela triangulos
            $sql_forma = "INSERT INTO triangulos (id_unidade, cor) VALUES (:id_unidade, :cor)";
            $parametros_forma = [
                ':id_unidade' => $this->getUnidade()->getIdUnidade(), // Certifique-se de que a unidade existe
                ':cor' => $this->cor // Agora a propriedade cor é usada
            ];
            Database::executar($sql_forma, $parametros_forma);

            // Obter o último ID da tabela triangulos
            $id_forma = Database::$lastId;

            // Incluir triângulo isósceles usando o ID da forma
            $sql_triangulo = "INSERT INTO triangulos_isosceles (id_triangulo, lado1, lado2, cor) VALUES (:id_triangulo, :lado1, :lado2, :cor)";
            $parametros_triangulo = [
                ':id_triangulo' => $id_forma,
                ':lado1' => $this->lado1,
                ':lado2' => $this->lado2,
                ':cor' => $this->cor // Adicione a cor aqui
            ];

            return Database::executar($sql_triangulo, $parametros_triangulo);
        } catch (Exception $e) {
            throw new Exception("Erro ao incluir triângulo isósceles: " . $e->getMessage());
        }
    }

    // Outros métodos como `alterar` e `excluir` podem ser adicionados conforme necessário
}
?>
