<!DOCTYPE html>
<html lang="en">
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('Triangulo.php');
require_once("../classes/Unidade.class.php");

// Verifica se a requisição GET foi feita para buscar triângulos
$busca = isset($_GET['busca']) ? $_GET['busca'] : "";
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 0;
$lista = Triangulo::listar($tipo, $busca);

// Lida com requisição POST para salvar ou deletar triângulos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['acao'])) {
        if ($_POST['acao'] == 'salvar') {
            // Captura os dados do formulário
            $lado1 = $_POST['lado1'];
            $lado2 = $_POST['lado2'];
            $lado3 = $_POST['lado3'];
            $cor = $_POST['cor'];
            $id_unidade = $_POST['medida'];

            // Criação de um novo objeto Unidade
            $unidade = new Unidade($id_unidade);

            // Criação de um novo objeto Triangulo
            $triangulo = new Triangulo(0, $lado1, $lado2, $lado3, $unidade, $cor);

            // Tente incluir o triângulo no banco de dados
            try {
                $triangulo->incluir();
                header("Location: index2.php");
                exit();
            } catch (Exception $e) {
                echo "Erro ao salvar: " . $e->getMessage();
            }
        } elseif ($_POST['acao'] == 'deletar' && isset($_POST['id_triangulo'])) {
            // Deleta o triângulo baseado no ID
            $id_triangulo = $_POST['id_triangulo'];

            try {
                Triangulo::deletar($id_triangulo);
                header("Location: index2.php");
                exit();
            } catch (Exception $e) {
                echo "Erro ao deletar: " . $e->getMessage();
            }
        }
    }
}

// Funções para cálculo (área, perímetro, ângulos) permanecem iguais...
function calcularArea($lado1, $lado2, $lado3) {
    $s = ($lado1 + $lado2 + $lado3) / 2;
    return sqrt($s * ($s - $lado1) * ($s - $lado2) * ($s - $lado3));
}

function calcularPerimetro($lado1, $lado2, $lado3) {
    return $lado1 + $lado2 + $lado3;
}

function calcularAngulos($lado1, $lado2, $lado3) {
    $anguloA = acos(($lado2**2 + $lado3**2 - $lado1**2) / (2 * $lado2 * $lado3)) * (180 / pi());
    $anguloB = acos(($lado1**2 + $lado3**2 - $lado2**2) / (2 * $lado1 * $lado3)) * (180 / pi());
    $anguloC = 180 - $anguloA - $anguloB;
    return [$anguloA, $anguloB, $anguloC];
}
?>

<head>
    <title>Formulário de criação de triângulos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <nav class="mb-4">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="../unidade/index.php">Cadastro de Unidade</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../Triangulo-Equilatero/index3.php">Triângulos Equilatero</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../Triangulos-Isoceles/index4.php">Triângulos Isósceles</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../Triangulos-Escalenos/index5.php">Triângulos Escalenos</a>
        </li>
    </ul>
</nav>

</head>

<body>
    <div class="container mt-4">
        <nav class="mb-4">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="index2.php">Cadastro de Triângulos</a>
                </li>
                <!-- Outros links de navegação -->
            </ul>
        </nav>
        <h2>Cadastro de Triângulos</h2>
        <form action="index2.php" method="post" class="row g-3" onsubmit="return validarEntradas()">
            <!-- Campos de entrada do formulário -->
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

                if (lado1 + lado2 + lado3 > 180) {
                    alert("A soma dos lados deve ser menor ou igual a 180.");
                    return false;
                }

                return true;
            }
        </script>

        <hr>

        <h2>Triângulos Criados</h2>
        
        <div class="row">
            <?php
            foreach ($lista as $triangulo) {
                $lado1 = $triangulo->getLado1();
                $lado2 = $triangulo->getLado2();
                $lado3 = $triangulo->getLado3();
                $unidade = $triangulo->getUnidade()->getNome();
                $cor = $triangulo->getCor();

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
                echo "<p>Área: " . number_format($area, 2) . "</p>";
                echo "<p>Perímetro: " . number_format($perimetro, 2) . "</p>";
                echo "<p>Ângulos: " . number_format($anguloA, 2) . "°, " . number_format($anguloB, 2) . "°, " . number_format($anguloC, 2) . "°</p>";
                echo "<form method='post' action='index2.php'>";
                echo "<input type='hidden' name='id_triangulo' value='" . $triangulo->getId() . "'>";
                echo "<button type='submit' name='acao' value='deletar' class='btn btn-danger'>Deletar</button>";
                echo "</form>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
