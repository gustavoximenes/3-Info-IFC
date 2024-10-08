<?php
require_once("../classes/Database.class.php");
require_once("../classes/Unidade.class.php");
require_once("../classes/Circulo.class.php");

// Verifica se a requisição POST foi feita para salvar um círculo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] == 'salvar') {
    // Captura os dados do formulário
    $raio = $_POST['raio'];
    $cor = $_POST['cor'];
    $id_unidade = $_POST['medida']; // Certifique-se de que o campo 'medida' é enviado

    // Criação de um novo objeto Circulo
    $unidade = Unidade::listar(1, $id_unidade)[0]; // Você pode precisar ajustar isso dependendo da sua implementação da classe Unidade
    $circulo = new Circulo(0, $raio, $cor, $unidade);

    // Tente incluir o círculo no banco de dados
    try {
        $circulo->incluir();
        // Redirecionar ou mostrar mensagem de sucesso
        header("Location: circulos.php"); // Redireciona para a mesma página ou onde preferir
        exit();
    } catch (Exception $e) {
        echo "Erro ao salvar: " . $e->getMessage();
    }
}

// Listar círculos existentes
$lista = Circulo::listar();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cadastro de Círculos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Cadastro de Círculos</h2>
        <form action="circulos.php" method="post" class="row g-3">
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

        <h2>Círculos Criados</h2>
        <div class="row">
            <?php
            foreach ($lista as $circulo) {
                $raio = $circulo->getRaio();
                $cor = $circulo->getCor();
                $unidade = $circulo->getUnidade()->getNome();
                $area = pi() * $raio * $raio; // Cálculo da área
                $perimetro = 2 * pi() * $raio; // Cálculo do perímetro

                // Exibe os dados do círculo
                echo "<div class='col-md-4'>";
                echo "<svg width='100' height='100'>";
                echo "<circle cx='50' cy='50' r='$raio' style='fill:$cor;stroke:black;stroke-width:1' />";
                echo "</svg>";
                echo "<p>Raio: $raio $unidade</p>";
                echo "<p>Cor: $cor</p>";
                echo "<p>Área: " . number_format($area, 2) . " {$unidade}<sup>2</sup></p>";
                echo "<p>Perímetro: " . number_format($perimetro, 2) . " $unidade</p>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
