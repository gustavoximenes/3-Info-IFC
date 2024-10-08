<!DOCTYPE html>
<html lang="en">
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('circulos.php'); // Alterado de Triangulo.php para Circulo.php
require_once("../classes/Unidade.class.php");

// Verifica se a requisição GET foi feita para buscar círculos
$busca = isset($_GET['busca']) ? $_GET['busca'] : "";
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 0;
$lista = Circulo::listar($tipo, $busca); // Alterado para listar círculos

// Lida com requisição POST para salvar ou deletar círculos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['acao'])) {
        if ($_POST['acao'] == 'salvar') {
            // Captura os dados do formulário
            $raio = $_POST['raio']; // Alterado de lados para raio
            $cor = $_POST['cor'];
            $id_unidade = $_POST['medida'];

            // Criação de um novo objeto Unidade
            $unidade = new Unidade($id_unidade);

            // Criação de um novo objeto Circulo
            $circulo = new Circulo(0, $raio, $unidade, $cor); // Alterado para Circulo

            // Tente incluir o círculo no banco de dados
            try {
                $circulo->incluir();
                header("Location: index2.php");
                exit();
            } catch (Exception $e) {
                echo "Erro ao salvar: " . $e->getMessage();
            }
        } elseif ($_POST['acao'] == 'deletar' && isset($_POST['id_circulo'])) {
            // Deleta o círculo baseado no ID
            $id_circulo = $_POST['id_circulo']; // Alterado de id_triangulo para id_circulo

            try {
                Circulo::deletar($id_circulo); // Alterado para deletar círculos
                header("Location: index2.php");
                exit();
            } catch (Exception $e) {
                echo "Erro ao deletar: " . $e->getMessage();
            }
        }
    }
}
?>

<head>
    <title>Formulário de criação de círculos</title>
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
                    <a class="nav-link" href="index2.php">Cadastro de Círculos</a> <!-- Alterado para Círculos -->
                </li>
            </ul>
        </nav>
        <h2>Cadastro de Círculos</h2>
        <form action="index2.php" method="post" class="row g-3" onsubmit="return validarEntradas()">
            <!-- Campos de entrada do formulário -->
            <div class="col-md-4">
                <label for="raio" class="form-label">Raio:</label>
                <input type="number" name="raio" id="raio" class="form-control" required>
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
                const raio = parseFloat(document.getElementById('raio').value);
                if (raio <= 0) {
                    alert("O raio deve ser maior que zero.");
                    return false;
                }
                return true;
            }
        </script>

        <hr>

        <h2>Círculos Criados</h2>
        <div class="row">
            <?php
            foreach ($lista as $circulo) {
                $raio = $circulo->getRaio(); // Alterado para obter o raio
                $unidade = $circulo->getUnidade()->getNome();
                $cor = $circulo->getCor();

                // Calcula a área e perímetro
                $area = pi() * pow($raio, 2); // Área do círculo
                $perimetro = 2 * pi() * $raio; // Perímetro do círculo

                // Exibe os cálculos junto ao círculo
                echo "<div class='col-md-4'>";
                echo "<svg width='100' height='100'>";
                echo "<circle cx='50' cy='50' r='$raio' style='fill:$cor;stroke:black;stroke-width:1' />"; // Desenha o círculo
                echo "</svg>";
                echo "<p>Raio: $raio $unidade</p>";
                echo "<p>Cor: $cor</p>";
                echo "<p>Área: " . number_format($area, 2) . " {$unidade}<sup>2</sup></p>";
                echo "<p>Perímetro: " . number_format($perimetro, 2) . " $unidade</p>";

                // Formulário para deletar o círculo
                echo "<form action='index2.php' method='post' style='display:inline;'>";
                echo "<input type='hidden' name='id_circulo' value='" . $circulo->getId() . "' />"; // Alterado para id_circulo
                echo "<button type='submit' name='acao' value='deletar' class='btn btn-danger btn-sm'>Deletar</button>";
                echo "</form>";

                echo "</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
