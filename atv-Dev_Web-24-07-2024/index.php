<?php
// Inclui o arquivo de conexão com o banco de dados, onde são definidos os detalhes de conexão com o banco.
require 'database.php';

// Inclui as definições das classes necessárias para o funcionamento do sistema
// Estas classes são Quadrado, Ponto e UnidadeMedida, que definem o comportamento e as operações desses objetos.
require 'Quadrado.php';
require 'Ponto.php';
require 'UnidadeMedida.php';

// Obtém todas as unidades de medida disponíveis no banco de dados
// A função estática `obterUnidades` da classe UnidadeMedida é utilizada para buscar essas informações.
$result_unidades = UnidadeMedida::obterUnidades($conn);

// Define uma consulta SQL para obter todos os quadrados e suas respectivas unidades de medida
// A consulta usa um LEFT JOIN para incluir informações da unidade de medida associada a cada quadrado.
$query_quadrados = "SELECT q.*, u.nome as unidade_nome, u.fator_conversion FROM quadrado q
                    LEFT JOIN unidade_medida u ON q.unidade_medida_id = u.id";
$result_quadrados = $conn->query($query_quadrados);

// Verifica se houve um erro na execução da consulta
// Se houve erro, exibe uma mensagem e encerra o script.
if (!$result_quadrados) {
    die("Erro na consulta de quadrados: " . $conn->error);
}

// Função para buscar todos os pontos associados a um quadrado específico
// Utiliza uma consulta preparada para evitar SQL Injection e retornar os pontos com base no ID do quadrado.
function buscarPontosPorQuadrado($conn, $quadrado_id) {
    $query_pontos = "SELECT * FROM ponto WHERE quadrado_id = ?";
    $stmt = $conn->prepare($query_pontos);

    // Verifica se a preparação da consulta foi bem-sucedida
    if (!$stmt) {
        die("Erro na preparação da consulta de pontos: " . $conn->error);
    }

    // Associa o ID do quadrado ao parâmetro da consulta e executa
    $stmt->bind_param("i", $quadrado_id);
    $stmt->execute();
    $result_pontos = $stmt->get_result();

    // Cria uma lista de objetos Ponto a partir dos resultados da consulta
    $pontos = [];
    while ($row = $result_pontos->fetch_assoc()) {
        $pontos[] = new Ponto($row['x'], $row['y'], $row['quadrado_id'], $row['id']);
    }
    return $pontos;
}

// Processa o formulário de criação de quadrado ou unidade de medida
// Verifica se a requisição é POST e qual ação deve ser realizada com base nos campos enviados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Se o formulário de criação de unidade de medida foi enviado
    if (isset($_POST['criar_unidade'])) {
        $nome = $_POST['nome'];
        $abreviacao = $_POST['abreviacao'];
        $fator_conversion = $_POST['fator_conversion'];

        // Tenta criar uma nova unidade de medida e redireciona para a página principal em caso de sucesso
        if (UnidadeMedida::criar($conn, $nome, $abreviacao, $fator_conversion)) {
            header("Location: index.php");
            exit();
        } else {
            die("Erro na inserção de unidade de medida: " . $conn->error);
        }
    } 
    // Se o formulário de criação de quadrado foi enviado
    elseif (isset($_POST['lado']) && isset($_POST['cor']) && isset($_POST['unidade_medida_id'])) {
        $lado = $_POST['lado'];
        $cor = $_POST['cor'];
        $unidade_medida_id = $_POST['unidade_medida_id'];

        // Define a consulta para inserir um novo quadrado no banco de dados
        $query_inserir_quadrado = "INSERT INTO quadrado (lado, cor, unidade_medida_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query_inserir_quadrado);

        // Verifica se a preparação da consulta foi bem-sucedida
        if (!$stmt) {
            die("Erro na preparação da consulta de inserção de quadrado: " . $conn->error);
        }

        // Associa os parâmetros à consulta e executa
        $stmt->bind_param("ssi", $lado, $cor, $unidade_medida_id);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            die("Erro na inserção de quadrado: " . $conn->error);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Quadrados</title>
    <style>
        /* Estilos para a exibição dos quadrados e informações */
        .quadrado-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .quadrado {
            border: 2px solid red;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            position: relative;
            box-sizing: border-box;
            background-color: #FFFFE0;
        }

        .quadrado-info {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .acoes {
            position: absolute;
            bottom: 5px;
            right: 5px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <h1>Cadastro de Quadrado</h1>
    <!-- Formulário para adicionar um novo quadrado -->
    <form action="index.php" method="post">
        <label for="lado">Lado:</label>
        <input type="text" name="lado" id="lado" required>
        <br>
        <label for="cor">Cor:</label>
        <input type="color" name="cor" id="cor" value="#FFFFE0" required>
        <br>
        <label for="unidade_medida_id">Unidade de Medida:</label>
        <select name="unidade_medida_id" id="unidade_medida_id" required>
            <?php 
            // Cria opções no select para todas as unidades de medida disponíveis
            while ($unidade = $result_unidades->fetch_assoc()): ?>
                <option value="<?php echo $unidade['id']; ?>">
                    <?php echo $unidade['nome']; ?> (<?php echo $unidade['abreviacao']; ?>)
                </option>
            <?php endwhile; ?>
        </select>
        <br>
        <input type="submit" value="Cadastrar">
    </form>

    <h2>Adicionar Nova Unidade de Medida</h2>
    <!-- Formulário para adicionar uma nova unidade de medida -->
    <form action="index.php" method="post">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required>
        <br>
        <label for="abreviacao">Abreviação:</label>
        <input type="text" name="abreviacao" id="abreviacao" required>
        <br>
        <label for="fator_conversion">Fator de Conversão:</label>
        <input type="number" name="fator_conversion" id="fator_conversion" step="0.01" required>
        <br>
        <input type="submit" name="criar_unidade" value="Adicionar Unidade de Medida">
    </form>

    <h2>Listagem de Quadrados</h2>
    <div class="quadrado-container">
        <?php 
        // Exibe todos os quadrados listados no banco de dados
        while ($row = $result_quadrados->fetch_assoc()): ?>
            <?php
            // Cria um objeto Quadrado com base nas informações do banco de dados
            $quadrado = new Quadrado($row['lado'], $row['id']);
            // Obtém os pontos associados ao quadrado
            $pontos = buscarPontosPorQuadrado($conn, $quadrado->getId());
            // Obtém o fator de conversão da unidade de medida e ajusta o tamanho do quadrado
            $fator_conversion = $row['fator_conversion'] ?? 1;
            $lado = $quadrado->getLado() * $fator_conversion;
            $cor = $row['cor'];
            $unidade_medida_nome = $row['unidade_nome'];
            ?>
            <!-- Exibe o quadrado com as informações de tamanho, cor e unidade de medida -->
            <div class='quadrado'>
                <div class='quadrado-info'>
                    <div style='width: <?php echo $lado; ?>px; height: <?php echo $lado; ?>px; background-color: <?php echo $cor; ?>;'>
                        ID: <?php echo $quadrado->getId(); ?><br>
                        Lado: <?php echo $lado; ?><br>
                        Área: <?php echo $quadrado->calcularArea(); ?><br>
                        Perímetro: <?php echo $quadrado->calcularPerimetro(); ?><br>
                    </div>
                    <div>
                        Unidade de Medida: <?php echo $unidade_medida_nome; ?><br>
                    </div>
                </div>
                <div class='acoes'>
                    <a href='edit.php?id=<?php echo $quadrado->getId(); ?>'>Editar</a> | 
                    <a href='delete.php?id=<?php echo $quadrado->getId(); ?>'>Excluir</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
