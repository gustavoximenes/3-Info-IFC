<?php
require_once("../classes/Database.class.php");
require_once("../classes/Unidade.class.php");

class TrianguloIsosceles {
    private $id;
    private $lado1;
    private $lado2;
    private $cor;
    private $unidade;

    public function __construct($id, $lado1, $lado2, $cor, Unidade $unidade) { // Incluindo cor no construtor
        $this->id = $id;
        $this->lado1 = $lado1;
        $this->lado2 = $lado2;
        $this->cor = $cor;
        $this->unidade = $unidade; // Assume que unidade é um objeto da classe Unidade
    }

    public function getId() { return $this->id; }
    public function getLado1() { return $this->lado1; }
    public function getLado2() { return $this->lado2; }
    public function getCor() { return $this->cor; }
    public function getUnidade() { return $this->unidade; }

    public static function listar($tipo, $busca) {
        // Método para listar triângulos isósceles conforme busca no banco
        $sql = "SELECT ti.*, u.id as id_unidade, u.tipo 
                FROM triangulos_isosceles ti 
                JOIN unidades u ON ti.id_triangulo = u.id";

        if ($tipo == 1) {
            $sql .= " WHERE ti.id = :busca";
        } elseif ($tipo == 2) {
            $sql .= " WHERE ti.lado1 = :busca";
        } elseif ($tipo == 3) {
            $sql .= " WHERE u.tipo = :busca"; 
        }

        $conexao = Database::conectar();
        $stmt = $conexao->prepare($sql);
        
        if ($tipo == 1 || $tipo == 2 || $tipo == 3) {
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
            // Incluir triângulo isósceles
            $sql_triangulo = "INSERT INTO triangulos_isosceles (id_triangulo, lado1, lado2, cor) VALUES (:id_triangulo, :lado1, :lado2, :cor)";
            $parametros_triangulo = [
                ':id_triangulo' => $this->unidade->getIdUnidade(), // Agora é um objeto
                ':lado1' => $this->lado1,
                ':lado2' => $this->lado2,
                ':cor' => $this->cor // Incluindo cor na inserção
            ];

            return Database::executar($sql_triangulo, $parametros_triangulo);
        } catch (Exception $e) {
            throw new Exception("Erro ao incluir triângulo isósceles: " . $e->getMessage());
        }
    }
    public static function deletar($id_triangulo) {
        // Conectar ao banco de dados
        $pdo = new PDO("mysql:host=127.0.0.1;port=3307;dbname=geometria;charset=UTF8", "gustavo", "1120");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // Iniciar uma transação
        $pdo->beginTransaction();
        try {
            // Preparar a query para deletar os registros filhos
            $sqlDeleteFilhos = "DELETE FROM triangulos_escalenos WHERE id_triangulo = :id_triangulo";
            $stmtFilhos = $pdo->prepare($sqlDeleteFilhos);
            $stmtFilhos->bindParam(':id_triangulo', $id_triangulo, PDO::PARAM_INT);
            $stmtFilhos->execute();
    
            // Preparar a query para deletar o registro pai
            $sql = "DELETE FROM triangulos WHERE id = :id_triangulo";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id_triangulo', $id_triangulo, PDO::PARAM_INT);
            $stmt->execute();
    
            // Comitar a transação
            $pdo->commit();
        } catch (Exception $e) {
            // Se algo der errado, reverter a transação
            $pdo->rollBack();
            echo "Erro ao deletar: " . $e->getMessage();
        }
    }
}
