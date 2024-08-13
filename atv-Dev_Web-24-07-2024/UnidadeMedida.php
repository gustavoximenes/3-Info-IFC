<?php
class UnidadeMedida {
    private $conn;
    private $id;
    private $nome;
    private $abreviacao;
    private $fator_conversion;

    public function __construct($conn, $id = null) {
        $this->conn = $conn;
        if ($id) {
            $this->id = $id;
            $this->load();
        }
    }

    private function load() {
        $query = "SELECT * FROM unidade_medida WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->nome = $row['nome'];
            $this->abreviacao = $row['abreviacao'];
            $this->fator_conversion = $row['fator_conversion'];
        }
    }

    public static function obterUnidades($conn) {
        $query = "SELECT * FROM unidade_medida";
        $result = $conn->query($query);
        if (!$result) {
            die("Erro na consulta de unidades de medida: " . $conn->error);
        }
        return $result;
    }

    public function atualizar($nome, $abreviacao, $fator_conversion) {
        $query = "UPDATE unidade_medida SET nome = ?, abreviacao = ?, fator_conversion = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssdi", $nome, $abreviacao, $fator_conversion, $this->id);
        return $stmt->execute();
    }

    public static function criar($conn, $nome, $abreviacao, $fator_conversion) {
        $query = "INSERT INTO unidade_medida (nome, abreviacao, fator_conversion) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssd", $nome, $abreviacao, $fator_conversion);
        return $stmt->execute();
    }

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getAbreviacao() {
        return $this->abreviacao;
    }

    public function getFatorConversao() {
        return $this->fator_conversion;
    }
}
?>
