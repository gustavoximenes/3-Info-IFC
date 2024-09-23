<?php
// triangulo.php
require_once("../classes/Database.class.php");
require_once("../classes/Unidade.class.php");
require_once("../classes/Triangulo.class.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'salvar') {
    // Captura os dados do formulário
    $lado1 = $_POST['lado1'];
    $lado2 = $_POST['lado2'];
    $lado3 = $_POST['lado3'];
    $cor = $_POST['cor'];
    $id_unidade = $_POST['medida']; // Certifique-se de que o campo 'medida' é enviado

    // Criação de um novo objeto Triangulo
    $unidade = Unidade::listar(1, $id_unidade)[0]; // Você pode precisar ajustar isso dependendo da sua implementação da classe Unidade
    $triangulo = new Triangulo(0, $lado1, $lado2, $lado3, $cor, $unidade);

    // Tente incluir o triângulo no banco de dados
    try {
        $triangulo->incluir();
        // Redirecionar ou mostrar mensagem de sucesso
        header("Location: index2.php"); // Redireciona para a mesma página ou onde preferir
        exit();
    } catch (Exception $e) {
        echo "Erro ao salvar: " . $e->getMessage();
    }
}

?>