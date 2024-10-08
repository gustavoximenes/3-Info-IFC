<!DOCTYPE html>
<html lang="en">
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('triangulo_equilatero.php'); // Corrigido o caminho do arquivo Triangulo
require_once("../classes/Unidade.class.php");

// Verifica se a requisição GET foi feita para buscar triângulos
$busca = isset($_GET['busca']) ? $_GET['busca'] : "";
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 0;
$lista = Triangulo::listar($tipo, $busca); // Busca os triângulos conforme a busca

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao'])) {
    if ($_POST['acao'] == 'salvar') {
        // Captura o lado e a cor do formulário
        $lado = floatval($_POST['lado']); // Converte para float
        $cor = $_POST['cor'];
        $id_unidade = $_POST['medida']; // Certifique-se de que o campo 'medida' é enviado

        // Verificação se o lado é maior que 180
       
        

        // Nova verificação: se lado * 3 é maior que 180, impede a criação
        if ($lado * 3 > 180) {
            echo "<script>alert('O lado multiplicado por 3 não pode ser maior que 180.'); window.history.back();</script>";
            exit();
        }

        // Criação de um novo objeto Triangulo
        $unidade = new Unidade($id_unidade);
        $triangulo = new Triangulo(0, $lado, $lado, $lado, $cor, $unidade); // Todos os lados iguais

        // Tente incluir o triângulo no banco de dados
        try {
            $triangulo->incluir();
            // Redirecionar ou mostrar mensagem de sucesso
            header("Location: index3.php");
            exit();
        } catch (Exception $e) {
            echo "Erro ao salvar: " . $e->getMessage();
        }
    } elseif ($_POST['acao'] == 'deletar' && isset($_POST['id_triangulo'])) {
        // Deleta o triângulo baseado no ID
        $id_triangulo = $_POST['id_triangulo'];

        try {
            Triangulo::deletar($id_triangulo);
            header("Location: index3.php");
            exit();
        } catch (Exception $e) {
            echo "Erro ao deletar: " . $e->getMessage();
        }
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
            <a class="nav-link" href="../unidade/index.php">Cadastro de Unidade</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../Traingulo/index2.php">Triângulos Normais</a>
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
        <h2>Cadastro de Triângulos Equiláteros</h2>
        <form action="index3.php" method="post" class="row g-3">
            <div class="col-md-4">
                <label for="lado" class="form-label">Lado:</label>
                <input type="number" name="lado" id="lado" class="form-control" placeholder="Digite o lado" required>
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
                <input type="hidden" name="id" id="id" value="0">
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

        <h2>Triângulos Desenhados</h2>
        <div class="row">
            <?php
            $lista = Triangulo::listar(0, ""); // Passa valores padrão
            foreach ($lista as $triangulo) {
                $lado = $triangulo->getLado1(); // Todos os lados são iguais
                $cor = $triangulo->getCor();
                $unidade = $triangulo->getUnidade()->getNome();

                // Cálculo da área para um triângulo equilátero
                $area = (sqrt(3) / 4) * pow($lado, 2);

                // Cálculo do perímetro para um triângulo equilátero
                $perimetro = $lado * 3;

                // Ângulos de um triângulo equilátero são sempre 60
                $anguloA = 60;
                $anguloB = 60;
                $anguloC = 60;

                // Calcular os pontos do triângulo equilátero
                $altura = ($lado * sqrt(3)) / 2; // Altura de um triângulo equilátero
                $points = "0,0 $lado,0 " . ($lado / 2) . ",$altura";

                echo "<div class='col-md-4'>";
                echo "<svg width='100' height='100'>";
                echo "<polygon points='$points' style='fill:$cor;stroke:black;stroke-width:1' />";
                echo "</svg>";
                echo "<p>Lado: $lado $unidade</p>";
                echo "<p>Cor: $cor</p>";
                echo "<p>Área: " . number_format($area, 2) . " {$unidade}<sup>2</sup></p>";  // Exibe a área
                echo "<p>Perímetro: " . number_format($perimetro, 2) . " $unidade</p>";  // Exibe o perímetro
                echo "<p>Ângulo A: $anguloA graus</p>";  // Substitui o símbolo de grau por "graus"
                echo "<p>Ângulo B: $anguloB graus</p>";  // Substitui o símbolo de grau por "graus"
                echo "<p>Ângulo C: $anguloC graus</p>";  // Substitui o símbolo de grau por "graus"

                // Formulário para deletar o triângulo
                echo "<form action='index3.php' method='post' style='display:inline;'>";
                echo "<input type='hidden' name='id_triangulo' value='" . $triangulo->getId() . "' />";
                echo "<button type='submit' name='acao' value='deletar' class='btn btn-danger btn-sm'>Deletar</button>";
                echo "</form>";

                echo "</div>";
            }
            ?>
        </div>
    </div>
</body>

</html>
