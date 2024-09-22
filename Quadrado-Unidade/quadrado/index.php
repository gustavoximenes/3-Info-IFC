<!DOCTYPE html>
<html lang="en">
<?php
include_once('quadrado.php');
require_once("../classes/Unidade.class.php");

// Verifica se a requisição GET foi feita para buscar quadrados
$busca = isset($_GET['busca']) ? $_GET['busca'] : "";
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 0;
$lista = Quadrado::listar($tipo, $busca); // Busca os quadrados conforme a busca

?>

<head>
    <title>Formulário de criação de formas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-4">
        <nav class="mb-4">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Cadastro de Quadrados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../unidade/index.php">Cadastro de Unidade</a>
                </li>
            </ul>
        </nav>

        <h2>Cadastro de Quadrados</h2>
        <form action="quadrado.php" method="post" class="row g-3">
            <div class="col-md-4">
                <label for="lado" class="form-label">Tamanho dos lados:</label>
                <input type="number" name="lado" id="lado" class="form-control" value="<?= isset($contato) ? $contato->getLado() : "" ?>" placeholder="Digite o lado de sua forma">
            </div>
            <div class="col-md-4">
                <label for="cor" class="form-label">Cor:</label>
                <input type="color" name="cor" id="cor" class="form-control form-control-color" value="<?= isset($contato) ? $contato->getCor() : "#000000" ?>">
            </div>
            <div class="col-md-4">
                <label for="unidade" class="form-label">Unidade de medida:</label>
                <select name='medida' id='medida' class="form-select">
                    <option value="0">Selecione</option>
                    <?php
                    $uniLista = Unidade::listar();
                    foreach ($uniLista as $unidade) {
                        $str = "<option value='{$unidade->getIdUnidade()}'";
                        if (isset($contato) && $contato->getUnidade()->getIdUnidade() == $unidade->getIdUnidade())
                            $str .= " selected";
                        $str .= ">{$unidade->getNome()}</option>";
                        echo $str;
                    }
                    ?>
                </select>
            </div>

            <div class="col-12">
                <input type="hidden" name="id" id="id" value="<?= isset($contato) ? $contato->getId() : 0 ?>">
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

        <h2>Quadrados Desenhados</h2>
        <div class="row">
            <?php
            foreach ($lista as $quadrado) {
                $lado = $quadrado->getLado();
                $cor = $quadrado->getCor();
                $unidade = $quadrado->getUnidade()->getTipo();
                $perimetro = 4 * $lado;
                $area = $lado * $lado;

                echo "<div class='col-md-4'>";
                echo "<div style='";
                echo "width: {$lado}{$unidade}; ";
                echo "height: {$lado}{$unidade}; ";
                echo "background-color: {$cor}; ";
                echo "display: flex; ";
                echo "flex-direction: column; ";
                echo "justify-content: center; ";
                echo "align-items: center; ";
                echo "border: 1px solid black; ";
                echo "color: white; ";
                echo "text-align: center; ";
                echo "'>";
                echo "<strong>Informações do Quadrado:</strong><br>";
                echo "ID: " . $quadrado->getId() . "<br>";
                echo "Lado: {$lado} {$unidade}<br>";
                echo "Cor: {$cor}<br>";
                echo "Perímetro: {$perimetro} {$unidade}<br>";
                echo "Área: {$area} {$unidade}²<br>";
                echo "</div>";
                echo "<div style='text-align: left;'>";
                echo "<a href='delete.php?id=" . $quadrado->getId() . "' class='btn btn-danger btn-sm'>Excluir</a> ";
                echo "<a href='index.php?id=" . $quadrado->getId() . "' class='btn btn-warning btn-sm'>Editar</a>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>
