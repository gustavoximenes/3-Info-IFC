<?php
require_once("../classes/Database.class.php");
require_once("../classes/Unidade.class.php");
require_once("../classes/Triangulo.class.php");
require_once("../classes/TrianguloIsosceles.class.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'salvar') {
    // Captura os dados do formulário
    $lado1 = $_POST['lado'];
    $lado2 = $_POST['base'];
    $cor = $_POST['cor'];
    $id_unidade = $_POST['medida']; // Certifique-se de que o campo 'medida' é enviado

    // Criação de um novo objeto Unidade
    $unidade = new Unidade($id_unidade);

    // Criação de um novo objeto TrianguloIsosceles
    $trianguloIsosceles = new TrianguloIsosceles(0, $lado1, $lado2, $cor, $unidade); // Corrigido para incluir a cor

    // Tente incluir o triângulo isósceles no banco de dados
    try {
        $trianguloIsosceles->incluir();
        // Redirecionar ou mostrar mensagem de sucesso
        header("Location: index4.php");
        exit();
    } catch (Exception $e) {
        echo "Erro ao salvar: " . $e->getMessage();
    }
}

?>
