<!DOCTYPE html>
<html lang="en">
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('Triangulo_isosceles.php');
require_once("../classes/Unidade.class.php");

// Verifica se a requisição GET foi feita para buscar triângulos isósceles
$busca = isset($_GET['busca']) ? $_GET['busca'] : "";
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 0;
$lista = TrianguloIsosceles::listar($tipo, $busca); // Busca os triângulos isósceles conforme a busca

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'salvar') {
    // Captura os dados do formulário
    $lado1 = $_POST['lado'];
    $lado2 = $_POST['base'];
    $cor = $_POST['cor'];
    $id_unidade = $_POST['medida']; // Certifique-se de que o campo 'medida' é enviado

    // Validação do triângulo
    if ($lado1 * 2 + $lado2 == 180) {
        echo "<script>alert('A soma do lado multiplicado por 2 e a base não pode ser igual a 180.'); window.history.back();</script>";
        exit();
    }

    // Criação de um novo objeto Unidade
    $unidade = new Unidade($id_unidade);

    // Criação de um novo objeto TrianguloIsosceles
    $trianguloIsosceles = new TrianguloIsosceles(0, $lado1, $lado2, $cor, $unidade);

    // Tente incluir o triângulo isósceles no banco de dados
    try {
        $trianguloIsosceles->incluir();
        // Redirecionar ou mostrar mensagem de sucesso
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        echo "Erro ao salvar: " . $e->getMessage();
    }
}
?>

<head>
    <title>Formulário de criação de triângulos isósceles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<nav class="mb-4">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="../Triangulo-Equilatero/index3.php">Triângulos Equiláteros</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../traingulo/index2.php">Cadastro de Triângulos Normais</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../unidade/index.php">Cadastro de Unidade</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../Triangulos-Escalenos/index5.php">Triângulos Escalenos</a>
        </li>
    </ul>
</nav>

<body>
    <div class="container mt-4">
        <h2>Cadastro de Triângulos Isósceles</h2>
        <form action="triangulo_isosceles.php" method="post" class="row g-3" onsubmit="return validarEntradas();">
            <div class="col-md-4">
                <label for="lado" class="form-label">Lado:</label>
                <input type="number" name="lado" id="lado" class="form-control" placeholder="Digite o lado" required>
            </div>
            <div class="col-md-4">
                <label for="base" class="form-label">Base:</label>
                <input type="number" name="base" id="base" class="form-control" placeholder="Digite a base" required>
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

        <hr>

        <h2>Pesquisar</h2>
        <form action="" method="get" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="busca" id="busca" class="form-control" placeholder="Procurar">
            </div>
            <div class="col-md-3">
                <select name="tipo" id="tipo" class="form-select">
                    <option value="1">ID</option>
                    <option value="2">Lado</option>
                    <option value="3">Cor</option>
                    <option value="4">Unidade</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-success">Buscar</button>
            </div>
        </form>

        <br>
        <script>
            function validarEntradas() {
                const lado1 = parseFloat(document.getElementById('lado').value);
                const lado2 = parseFloat(document.getElementById('base').value);

                if (lado1 <= 0 || lado2 <= 0) {
                    alert("Os lados devem ser maiores que zero.");
                    return false;
                }

                if (lado1 * 2 + lado2 > 180) {
                    alert("A soma dos lados deve ser menor ou igual a 180.");
                    return false;
                }

                return true;
            }
        </script>
        <h2>Triângulos Isósceles Desenhados</h2>
        <div class="row">
            <?php
            foreach ($lista as $trianguloIsosceles) {
                $lado = $trianguloIsosceles->getLado1();
                $base = $trianguloIsosceles->getLado2();
                $unidade = $trianguloIsosceles->getUnidade()->getNome();
                $cor = $trianguloIsosceles->getCor(); // Supondo que você tenha um método getCor() na classe TrianguloIsosceles

                // Calcular os pontos do triângulo isósceles
                $altura = sqrt(($lado * $lado) - (($base * 0.5) * ($base * 0.5)));
                $points = "0,0 $base,0 " . ($base / 2) . ",$altura";

                // Cálculo da área
                $area = ($base * $altura) / 2;

                // Cálculo do perímetro
                $perimetro = 2 * $lado + $base;

                // Cálculo dos ângulos (A, B e C)
                $anguloA = rad2deg(acos($base / (2 * $lado))); // Ângulo no vértice
                $anguloB = 180 - (2 * $anguloA); // Ângulo da base

                echo "<div class='col-md-4'>";
                echo "<svg width='100' height='100'>";
                echo "<polygon points='$points' style='fill:$cor;stroke:black;stroke-width:1' />";
                echo "</svg>";
                echo "<p>Lado: $lado $unidade</p>";
                echo "<p>Base: $base $unidade</p>";
                echo "<p>Cor: $cor</p>";
                echo "<p>Área: " . number_format($area, 2) . " {$unidade}<sup>2</sup></p>";  // Exibe a área
                echo "<p>Perímetro: " . number_format($perimetro, 2) . " $unidade</p>";  // Exibe o perímetro
                echo "<p>Ângulo A: " . number_format($anguloA, 2) . "°</p>";  // Adiciona o símbolo de grau corretamente
                echo "<p>Ângulo B: " . number_format($anguloB, 2) . "°</p>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</body>

</html>
