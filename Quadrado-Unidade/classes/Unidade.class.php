<?php
require_once("../classes/Database.class.php"); // Inclui a classe que lida com o banco de dados

class Unidade {
    private $id;
    private $tipo;
    private $nome;

    public function __construct($id = 0) {
        $this->setIdUnidade($id);
        if ($id > 0) {
            $this->carregarDados(); // Carrega os dados da unidade se um ID válido for passado
        }
    }

    private function carregarDados() {
        $sql = "SELECT * FROM unidades WHERE id = :id_unidade";
        $parametros = [':id_unidade' => $this->id];
        $conexao = Database::conectar();
        $stmt = $conexao->prepare($sql);
        $stmt->execute($parametros);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dados) {
            $this->nome = $dados['unidade'];
            $this->tipo = $dados['tipo'];
        } else {
            throw new Exception("Unidade não encontrada.");
        }
    }

    public function setIdUnidade($id) {
        if ($id < 0) {
            throw new Exception("Erro: id inválido!");
        } else {
            $this->id = $id;
        }
    }

    public function setTipo($tipo) {
        if ($tipo === null || $tipo === "") {
            $this->tipo = "indefinido"; // ou qualquer valor padrão que faça sentido
        } else {
            $this->tipo = $tipo;
        }
    }

    public function setNome($nome) {
        if ($nome === "") {
            throw new Exception("Erro: Nome indefinido");
        } else {
            $this->nome = $nome;
        }
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getIdUnidade() {
        return $this->id;
    }

    // Método para incluir uma nova unidade no banco de dados
    public function incluir() {
        $sql = 'INSERT INTO unidades (unidade, tipo) VALUES (:unidade, :tipo)';
        $parametros = array(':unidade' => $this->nome, ':tipo' => $this->tipo);
        return Database::executar($sql, $parametros); 
    }

    // Método para excluir uma unidade pelo id
    public function excluir() {
        $conexao = Database::getInstance();
        $sql = 'DELETE FROM unidades WHERE id = :id';
        $comando = $conexao->prepare($sql);
        $comando->bindValue(':id', $this->id);
        return $comando->execute();
    }

    // Método para alterar uma unidade existente
    public function alterar() {
        $sql = 'UPDATE unidades SET unidade = :unidade, tipo = :tipo WHERE id = :id';
        $parametros = array(':unidade' => $this->nome, ':tipo' => $this->tipo, ':id' => $this->id);
        return Database::executar($sql, $parametros); 
    }

    // Método para buscar a unidade por ID
    public static function buscarPorId($id_unidade) {
        try {
            $conexao = Database::getInstance();
            $sql = "SELECT * FROM unidades WHERE id = :id_unidade";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':id_unidade', $id_unidade);
            $stmt->execute();

            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($dados) {
                return new Unidade($dados['id']);
            } else {
                return null; // Retorna null se não encontrar a unidade
            }
        } catch (PDOException $e) {
            echo "Erro ao buscar unidade: " . $e->getMessage();
            return null;
        }
    }

    // Método para listar todas as unidades ou pesquisar por um tipo específico
    public static function listar($tipo = 0, $busca = "") {
        $sql = "SELECT * FROM unidades";
        $parametros = [];

        if ($tipo > 0) {
            switch ($tipo) {
                case 1:
                    $sql .= " WHERE id = :busca";
                    break;
                case 2:
                    $sql .= " WHERE tipo LIKE :busca";
                    $busca = "%{$busca}%";
                    break;
            }
        }

        if (!empty($busca)) {
            $parametros = array(':busca' => $busca);
        }

        $comando = Database::executar($sql, $parametros); 
        $unidades = [];

        while ($forma = $comando->fetch(PDO::FETCH_ASSOC)) {
            $unidade = new Unidade($forma['id']);
            array_push($unidades, $unidade);
        }

        return $unidades;
    }
}
