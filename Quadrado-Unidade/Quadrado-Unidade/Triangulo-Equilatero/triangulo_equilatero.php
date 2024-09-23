<?php
require_once("../classes/Database.class.php");
require_once("../classes/Unidade.class.php");
require_once("../classes/Triangulo.class.php");
require_once("../classes/TrianguloEquilatero.class.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'salvar') {
    // Captura os dados do formulário
    $lado = $_POST['lado']; // Apenas um lado para equiláteros
    $cor = $_POST['cor'];
    $id_unidade = $_POST['medida']; // Certifique-se de que o campo 'medida' é enviado

    // Criação de um novo objeto Triangulo
    $unidade = new Unidade($id_unidade);
    $triangulo = new Triangulo(0, $lado, $lado, $lado, $cor, $unidade); // Use apenas um lado para equiláteros

    // Tente incluir o triângulo no banco de dados
    try {
        $triangulo->incluir();
        $id_triangulo = Database::$lastId; // Obtenha o ID do triângulo que foi inserido

        // Agora crie o triângulo equilátero
        $trianguloEquilatero = new TrianguloEquilatero(0, $lado, $cor, $unidade);
        $trianguloEquilatero->incluir($id_triangulo); // Passa o ID do triângulo

        // Redirecionar ou mostrar mensagem de sucesso
        header("Location: index3.php");
        exit();
    } catch (Exception $e) {
        echo "Erro ao salvar: " . $e->getMessage();
    }
}
?>
