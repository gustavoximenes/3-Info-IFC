<?php
require_once("../classes/Database.class.php");
require_once("../classes/Unidade.class.php");

class Quadrado
{
    private $id;        // ID do quadrado
    private $lado;      // Lado do quadrado
    private $cor;       // Cor do quadrado
    private $unidade;   // Unidade de medida associada à forma (ligada à tabela formas)

    public function __construct($id = 0, $lado = 1, $cor = "black", Unidade $unidade = null)
    {
        $this->setId($id);
        $this->setLado($lado);
        $this->setCor($cor);
        $this->setUnidade($unidade);
    }

    public function setId($id)
    {
        if ($id < 0) {
            throw new Exception("Erro: id inválido!");
        } else {
            $this->id = $id;
        }
    }

    public function setLado($lado)
    {
        if ($lado < 1) {
            throw new Exception("Erro, lado indefinido");
        } else {
            $this->lado = $lado;
        }
    }

    public function setCor($cor)
    {
        if ($cor == "") {
            throw new Exception("Erro, cor indefinida");
        } else {
            $this->cor = $cor;
        }
    }

    public function setUnidade(Unidade $unidade)
    {
        $this->unidade = $unidade;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLado()
    {
        return $this->lado;
    }

    public function getCor()
    {
        return $this->cor;
    }

    public function getUnidade()
    {
        return $this->unidade;
    }

    // Inserção de quadrado no banco de dados, agora vinculando à tabela 'formas'
    public function incluir()
    {
        // Insere na tabela 'formas' e recupera o ID gerado
        $sql_forma = 'INSERT INTO formas (cor, id_unidade) VALUES (:cor, :id_unidade)';
        $parametros_forma = array(
            ':cor' => $this->cor,
            ':id_unidade' => $this->unidade->getIdUnidade()
        );

        // Executa a consulta e armazena a conexão
        $conexao = Database::getInstance();
        $comando = $conexao->prepare($sql_forma);
        $comando->execute($parametros_forma);

        // Recupera o ID gerado
        $forma_id = $conexao->lastInsertId();

        // Insere na tabela 'quadrados'
        $sql_quadrado = 'INSERT INTO quadrados (lado, id_forma) VALUES (:lado, :id_forma)';
        $parametros_quadrado = array(
            ':lado' => $this->lado,
            ':id_forma' => $forma_id
        );

        return $conexao->prepare($sql_quadrado)->execute($parametros_quadrado);
    }

    public function excluir()
    {
        $conexao = Database::getInstance();
        $sql = 'DELETE FROM quadrados WHERE id = :id';
        $comando = $conexao->prepare($sql);
        $comando->bindValue(':id', $this->id);

        return $comando->execute();
    }

    public function alterar()
    {
        $sql_forma = 'UPDATE formas SET cor = :cor, id_unidade = :id_unidade WHERE id = (SELECT id_forma FROM quadrados WHERE id = :id)';
        $parametros_forma = array(
            ':cor' => $this->cor,
            ':id_unidade' => $this->unidade->getIdUnidade(),
            ':id' => $this->id
        );
        Database::executar($sql_forma, $parametros_forma);

        $sql_quadrado = 'UPDATE quadrados SET lado = :lado WHERE id = :id';
        $parametros_quadrado = array(
            ':lado' => $this->lado,
            ':id' => $this->id
        );

        return Database::executar($sql_quadrado, $parametros_quadrado);
    }

    public function desenharQuadrado()
    {
        return "<div class='container' style='background-color:" . $this->getCor() .
            "; width:" . $this->getLado() . $this->getUnidade()->getTipo() .
            "; height:" . $this->getLado() . $this->getUnidade()->getTipo() .
            "'></div><br> ";
    }

    public static function listar($tipo = 0, $busca = "")
    {
        $sql = "SELECT quadrados.*, formas.cor, formas.id_unidade FROM quadrados 
                JOIN formas ON quadrados.id_forma = formas.id";

        if ($tipo > 0) {
            switch ($tipo) {
                case 1:
                    $sql .= " WHERE quadrados.id = :busca";
                    break;
                case 2:
                    $sql .= " WHERE quadrados.lado LIKE :busca";
                    $busca = "%{$busca}%";
                    break;
                case 3:
                    $sql .= " WHERE formas.cor LIKE :busca";
                    $busca = "%{$busca}%";
                    break;
                case 4:
                    $sql .= " JOIN unidades ON formas.id_unidade = unidades.id WHERE unidades.tipo LIKE :busca";
                    $busca = "%{$busca}%";
                    break;
            }
        }

        $parametros = [];
        if ($tipo > 0) {
            $parametros = array(':busca' => $busca);
        }

        $comando = Database::executar($sql, $parametros);
        $quadrados = array();

        while ($forma = $comando->fetch(PDO::FETCH_ASSOC)) {
            $unidade = Unidade::listar(1, $forma['id_unidade'])[0];
            $quadrado = new Quadrado($forma['id'], $forma['lado'], $forma['cor'], $unidade);
            array_push($quadrados, $quadrado);
        }

        return $quadrados;
    }
}
