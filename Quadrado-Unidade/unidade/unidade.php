<?php
// Inclui as classes necessárias para o funcionamento do script
require_once("../classes/Unidade.class.php");
require_once("../classes/Database.class.php");

// Verifica se um ID foi passado via GET (geralmente usado para edição)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$msg = isset($_GET['MSG']) ? $_GET['MSG'] : ""; // Captura mensagens de erro ou sucesso, se houver

// Se um ID válido foi fornecido, busca a unidade correspondente no banco de dados
if ($id > 0) {
    $unidade = Unidade::listar(1, $id);
    if (!empty($unidade)) {
        $contato = $unidade[0];
    } else {
        $msg = "Unidade não encontrada.";
    }
}

// Processa as requisições POST (envio do formulário)
// Processa as requisições POST (envio do formulário)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $nome_un = isset($_POST['nome_un']) ? trim($_POST['nome_un']) : "";
    $un = isset($_POST['un']) ? trim($_POST['un']) : "";
    $acao = isset($_POST['acao']) ? $_POST['acao'] : "";

    try {
        // Cria um novo objeto Unidade com os dados do formulário
        $unidade = new Unidade($id, $nome_un, $un);
        $res = false;

        // Verifica a ação a ser realizada
        if ($acao === 'Salvar') {
            if ($id > 0) {
                $res = $unidade->alterar();
            } else {
                $res = $unidade->incluir();
            }
        } elseif ($acao === 'Excluir') {
            if ($id > 0) {
                $unidade->setIdUnidade($id);
                $res = $unidade->excluir();
            }
        }

        // Se a ação foi bem-sucedida, redireciona para a página de lista de unidades
        if ($res) {
            header('Location: index.php'); // ou index.php, conforme necessário
            exit;
        } else {
            echo "Erro ao inserir dados!";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') { // Processa as requisições GET (busca de unidades)
    $busca = isset($_GET['busca']) ? trim($_GET['busca']) : "";
    $tipo = isset($_GET['tipo']) ? (int)$_GET['tipo'] : 0;
    $lista = Unidade::listar($tipo, $busca);
}
