<?php
require_once("../classes/Database.class.php");
require_once("../classes/Unidade.class.php");

class TrianguloEscaleno {
    private $id;
    private $lado1;
    private $lado2;
    private $lado3;
    private $cor;
    private $unidade;

    public function __construct($id, $lado1, $lado2, $lado3, $cor, Unidade $unidade) {
        $this->id = $id;
        $this->lado1 = $lado1;
        $this->lado2 = $lado2;
        $this->lado3 = $lado3;
        $this->cor = $cor; // Adicionando cor
        $this->unidade = $unidade;
    }

    public function getId() { return $this->id; }
    public function getLado1() { return $this->lado1; }
    public function getLado2() { return $this->lado2; }
    public function getLado3() { return $this->lado3; }
    public function getCor() { return $this->cor; } // Método para obter a cor
    public function getUnidade() { return $this->unidade; }

    public static function listar($tipo, $busca) {
        $sql = "SELECT te.*, u.id as id_unidade, u.tipo 
                FROM triangulos_escalenos te 
                JOIN unidades u ON te.id_triangulo = u.id";

        if ($tipo == 1) {
            $sql .= " WHERE te.id = :busca";
        } elseif ($tipo == 2) {
            $sql .= " WHERE te.lado1 = :busca";
        } elseif ($tipo == 3) {
            $sql .= " WHERE te.lado2 = :busca";
        } elseif ($tipo == 4) {
            $sql .= " WHERE te.lado3 = :busca";
        }

        $conexao = Database::conectar();
        $stmt = $conexao->prepare($sql);
        
        if ($tipo >= 1 && $tipo <= 4) {
            $stmt->bindParam(':busca', $busca);
        }

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $lista = [];
        foreach ($result as $row) {
            $id_unidade = $row['id_unidade'];
            $unidade = new Unidade($id_unidade, $row['tipo']);
            $lista[] = new TrianguloEscaleno($row['id'], $row['lado1'], $row['lado2'], $row['lado3'], $row['cor'], $unidade);
        }
        return $lista;
    }

    public function incluir() {
        try {
            $sql_triangulo = "INSERT INTO triangulos_escalenos (id_triangulo, lado1, lado2, lado3, cor) VALUES (:id_triangulo, :lado1, :lado2, :lado3, :cor)";
            $parametros_triangulo = [
                ':id_triangulo' => $this->unidade->getIdUnidade(),
                ':lado1' => $this->lado1,
                ':lado2' => $this->lado2,
                ':lado3' => $this->lado3,
                ':cor' => $this->cor // Incluindo a cor nos parâmetros
            ];

            return Database::executar($sql_triangulo, $parametros_triangulo);
        } catch (Exception $e) {
            throw new Exception("Erro ao incluir triângulo escaleno: " . $e->getMessage());
        }
    }
}
?>
