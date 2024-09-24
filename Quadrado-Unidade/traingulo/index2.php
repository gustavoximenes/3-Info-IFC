<!DOCTYPE html>
<html lang="en">
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('triangulo.php');
require_once("../classes/Unidade.class.php");

// Verifica se a requisição GET foi feita para buscar triângulos
$busca = isset($_GET['busca']) ? $_GET['busca'] : "";
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 0;
$lista = Triangulo::listar($tipo, $busca); // Busca os triângulos conforme a busca

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'salvar') {
    // Captura os dados do formulário
    $lado1 = $_POST['lado1'];
    $lado2 = $_POST['lado2'];
    $lado3 = $_POST['lado3'];
    $cor = $_POST['cor'];
    $id_unidade = $_POST['medida']; // Certifique-se de que o campo 'medida' é enviado

    // Criação de um novo objeto Triangulo
    $unidade = new Unidade($id_unidade);
    $triangulo = new Triangulo(0, $lado1, $lado2, $lado3, $cor, $unidade);

    // Tente incluir o triângulo no banco de dados
    try {
        $triangulo->incluir();
        // Redirecionar ou mostrar mensagem de sucesso
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        echo "Erro ao salvar: " . $e->getMessage();
    }
}
?>

<head>
    <title>Formulário de criação de formas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<nav class="mb-4">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="index.php">Cadastro de Triângulos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../unidade/index.php">Cadastro de Unidade</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../Triangulo-Equilatero/index3.php">Triângulos Equiláteros</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../Triangulos-Isoceles/index4.php">Triângulos Isósceles</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../Triangulos-Escalenos/index5.php">Triângulos Escalenos</a>
        </li>
    </ul>
</nav>

<body>
    <div class="container mt-4">
        <h2>Triângulos Desenhados</h2>
        <div class="row">
            <?php
            $lista = Triangulo::listar(0, ""); // Passa valores padrão
            foreach ($lista as $triangulo) {
                $lado1 = $triangulo->getLado1();
                $lado2 = $triangulo->getLado2();
                $lado3 = $triangulo->getLado3();
                $cor = $triangulo->getCor();
                $unidade = $triangulo->getUnidade()->getNome();

                // Cálculo da área usando a fórmula de Herão
                $s = ($lado1 + $lado2 + $lado3) / 2;
                $area = sqrt($s * ($s - $lado1) * ($s - $lado2) * ($s - $lado3));

                // Cálculo do perímetro
                $perimetro = $lado1 + $lado2 + $lado3;

                // Cálculo dos ângulos usando a lei dos cossenos
                $anguloA = rad2deg(acos(($lado2**2 + $lado3**2 - $lado1**2) / (2 * $lado2 * $lado3)));
                $anguloB = rad2deg(acos(($lado1**2 + $lado3**2 - $lado2**2) / (2 * $lado1 * $lado3)));
                $anguloC = rad2deg(acos(($lado1**2 + $lado2**2 - $lado3**2) / (2 * $lado1 * $lado2)));

                // Calcular os pontos do triângulo para desenhá-lo
                $points = "0,0 $lado1,0 " . ($lado2 * 0.5) . "," . sqrt(($lado3 * $lado3) - (($lado2 * 0.5) * ($lado2 * 0.5)));

                echo "<div class='col-md-4'>";
                echo "<svg width='100' height='100'>";
                echo "<polygon points='$points' style='fill:$cor;stroke:black;stroke-width:1' />";
                echo "</svg>";
                echo "<p>Lado 1: $lado1 $unidade</p>";
                echo "<p>Lado 2: $lado2 $unidade</p>";
                echo "<p>Lado 3: $lado3 $unidade</p>";
                echo "<p>Cor: $cor</p>";
                echo "<p>Área: " . number_format($area, 2) . " {$unidade}<sup>2</sup></p>";
                echo "<p>Perímetro: " . number_format($perimetro, 2) . " $unidade</p>";  // Exibe o perímetro
                echo "<p>Ângulo A: " . number_format($anguloA, 2) . "°</p>";
                echo "<p>Ângulo B: " . number_format($anguloB, 2) . "°</p>";
                echo "<p>Ângulo C: " . number_format($anguloC, 2) . "°</p>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</body>

</html>
