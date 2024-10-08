<!DOCTYPE html>
<html lang="en">
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('TrianguloEscaleno.php');
require_once("../classes/Unidade.class.php");

// Verifica se a requisição GET foi feita para buscar triângulos escalenos
$busca = isset($_GET['busca']) ? $_GET['busca'] : "";
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 0;
$lista = TrianguloEscaleno::listar($tipo, $busca);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'salvar') {
    // Captura os dados do formulário
    $lado1 = $_POST['lado1'];
    $lado2 = $_POST['lado2'];
    $lado3 = $_POST['lado3'];
    $cor = $_POST['cor'];
    $id_unidade = $_POST['medida'];

    // Criação de um novo objeto Unidade
    $unidade = new Unidade($id_unidade);

    // Criação de um novo objeto TrianguloEscaleno
    $trianguloEscaleno = new TrianguloEscaleno(0, $lado1, $lado2, $lado3, $unidade, $cor);

    // Tente incluir o triângulo escaleno no banco de dados
    try {
        $trianguloEscaleno->incluir();
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        echo "Erro ao salvar: " . $e->getMessage();
    }
}

// Função para calcular a área de um triângulo escaleno usando a fórmula de Herão
function calcularArea($lado1, $lado2, $lado3) {
    $s = ($lado1 + $lado2 + $lado3) / 2; // semi-perímetro
    return sqrt($s * ($s - $lado1) * ($s - $lado2) * ($s - $lado3));
}

// Função para calcular o perímetro
function calcularPerimetro($lado1, $lado2, $lado3) {
    return $lado1 + $lado2 + $lado3;
}

// Função para calcular os ângulos usando a Lei dos Cossenos
function calcularAngulos($lado1, $lado2, $lado3) {
    $anguloA = acos(($lado2**2 + $lado3**2 - $lado1**2) / (2 * $lado2 * $lado3)) * (180 / pi());
    $anguloB = acos(($lado1**2 + $lado3**2 - $lado2**2) / (2 * $lado1 * $lado3)) * (180 / pi());
    $anguloC = 180 - $anguloA - $anguloB; // A soma dos ângulos de um triângulo é 180°

    return [$anguloA, $anguloB, $anguloC];
}

?>

<head>
    <title>Formulário de criação de triângulos escalenos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-4">
        <nav class="mb-4">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="../traingulo/index2.php">Cadastro de Triângulos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../unidade/index.php">Cadastro de Unidade</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Traingulo/index2.php">Triângulos Normais</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Triangulos-Isoceles/index4.php">Triângulos Isósceles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Triangulo-Equilatero/index3.php">Triângulo Equilátero</a>
                </li>
            </ul>
        </nav>
        <h2>Cadastro de Triângulos Escalenos</h2>
        <form action="TrianguloEscaleno.php" method="post" class="row g-3" onsubmit="return validarEntradas()">
            <div class="col-md-4">
                <label for="lado1" class="form-label">Lado 1:</label>
                <input type="number" name="lado1" id="lado1" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="lado2" class="form-label">Lado 2:</label>
                <input type="number" name="lado2" id="lado2" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="lado3" class="form-label">Lado 3:</label>
                <input type="number" name="lado3" id="lado3" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="cor" class="form-label">Cor:</label>
                <input type="color" name="cor" id="cor" class="form-control form-control-color" value="#000000">
            </div>
            <div class="col-md-4">
                <label for="medida" class="form-label">Unidade de medida:</label>
                <select name='medida' id='medida' class="form-select" required>
                    <option value="0">Selecione</option>
                    <?php
                    $uniLista = Unidade::listar();
                    foreach ($uniLista as $unidade) {
                        echo "<option value='{$unidade->getIdUnidade()}'>{$unidade->getNome()}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" name="acao" value="salvar" class="btn btn-primary">Salvar</button>
                <button type="reset" class="btn btn-secondary">Resetar</button>
            </div>
        </form>

        <script>
            function validarEntradas() {
                const lado1 = parseFloat(document.getElementById('lado1').value);
                const lado2 = parseFloat(document.getElementById('lado2').value);
                const lado3 = parseFloat(document.getElementById('lado3').value);

                if (lado1 <= 0 || lado2 <= 0 || lado3 <= 0) {
                    alert("Os lados devem ser maiores que zero.");
                    return false;
                }

                if (lado1 === lado2 || lado1 === lado3 || lado2 === lado3) {
                    alert("Os lados devem ser diferentes.");
                    return false;
                }

                return true; // Permite o envio do formulário
            }
        </script>
 <script>
            function validarLados() {
                const lado1 = parseFloat(document.getElementById('lado1').value);
                const lado2 = parseFloat(document.getElementById('lado2').value);
                const lado3 = parseFloat(document.getElementById('lado3').value);

                if (lado1 <= 0 || lado2 <= 0 || lado3 <= 0) {
                    alert("Os lados devem ser maiores que zero.");
                    return false;
                }

                if (lado1 + lado2 + lado3 > 180) {
                    alert("A soma dos lados deve ser menor ou igual a 180.");
                    return false;
                }

                return true;
            }
        </script>
        <hr>

        <h2>Pesquisar</h2>
        <form action="" method="get" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="busca" id="busca" class="form-control" placeholder="Procurar">
            </div>
            <div class="col-md-3">
                <select name="tipo" id="tipo" class="form-select">
                    <option value="1">ID</option>
                    <option value="2">Lado 1</option>
                    <option value="3">Lado 2</option>
                    <option value="4">Lado 3</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-success">Buscar</button>
            </div>
        </form>

        <br>

        <h2>Triângulos Escalenos Desenhados</h2>
        <div class="row">
            <?php
            foreach ($lista as $trianguloEscaleno) {
                $lado1 = $trianguloEscaleno->getLado1();
                $lado2 = $trianguloEscaleno->getLado2();
                $lado3 = $trianguloEscaleno->getLado3();
                $unidade = $trianguloEscaleno->getUnidade()->getNome();
                $cor = $trianguloEscaleno->getCor();

                // Calcula a área, perímetro e ângulos
                $area = calcularArea($lado1, $lado2, $lado3);
                $perimetro = calcularPerimetro($lado1, $lado2, $lado3);
                [$anguloA, $anguloB, $anguloC] = calcularAngulos($lado1, $lado2, $lado3);

                // Exibe os cálculos junto ao triângulo
                echo "<div class='col-md-4'>";
                echo "<svg width='100' height='100'>";
                echo "<polygon points='0,0 $lado2,0 " . ($lado1 * 0.5) . "," . sqrt(($lado1 * $lado1) - (($lado2 * 0.5) * ($lado2 * 0.5))) . "' style='fill:$cor;stroke:black;stroke-width:1' />";
                echo "</svg>";
                echo "<p>Lados: $lado1, $lado2, $lado3 $unidade</p>";
                echo "<p>Cor: $cor</p>";
                echo "<p>Área: $area {$unidade}²</p>";
                echo "<p>Perímetro: $perimetro $unidade</p>";
                echo "<p>Ângulo A: " . round($anguloA, 2) . "°</p>";
                echo "<p>Ângulo B: " . round($anguloB, 2) . "°</p>";
                echo "<p>Ângulo C: " . round($anguloC, 2) . "°</p>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
