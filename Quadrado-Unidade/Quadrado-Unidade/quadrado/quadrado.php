<?php
// Inclui os arquivos das classes necessárias
require_once("../classes/Quadrado.class.php");
require_once("../classes/Database.class.php");
require_once("../classes/Unidade.class.php");

// Verifica se um ID foi passado pela URL (para edição ou exclusão)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$msg = isset($_GET['MSG']) ? $_GET['MSG'] : "";

// Se um ID válido foi fornecido, busca o quadrado correspondente no banco de dados
$quadrado = null;
if ($id > 0) {
    $quadrado = Quadrado::listar(1, $id)[0];
}

// Processa as requisições POST (envio do formulário)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $lado = isset($_POST['lado']) ? (float)$_POST['lado'] : 0;
    $cor = isset($_POST['cor']) ? trim($_POST['cor']) : "";
    $medida = isset($_POST['medida']) ? (int)$_POST['medida'] : 0;
    $acao = isset($_POST['acao']) ? $_POST['acao'] : "";

    try {
        // Busca a unidade de medida pelo ID fornecido no formulário
        $unidade = Unidade::listar(1, $medida)[0];
        // Cria um novo objeto Quadrado com os dados do formulário e a unidade de medida
        $quadrado = new Quadrado($id, $lado, $cor, $unidade);
        $res = false;

        // Verifica a ação a ser realizada
        if ($acao === 'salvar') {
            if ($id > 0) {
                $res = $quadrado->alterar();
            } else {
                $res = $quadrado->incluir();
            }
        } elseif ($acao === 'excluir') {
            if ($id > 0) {
                $res = $quadrado->excluir();
            }
        }

        // Se a ação foi bem-sucedida, redireciona para a página de lista de quadrados
        if ($res) {
            header('Location: index.php'); // ou index.php, conforme necessário
            exit;
        } else {
            echo "Erro ao inserir dados!";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Processa as requisições GET (busca de quadrados)
    $busca = isset($_GET['busca']) ? $_GET['busca'] : "";
    $tipo = isset($_GET['tipo']) ? (int)$_GET['tipo'] : 0;
    $lista = Quadrado::listar($tipo, $busca);
}
