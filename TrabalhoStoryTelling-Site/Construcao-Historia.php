<?php
session_start();

// Configurações do banco de dados
$host = 'localhost'; // Endereço do servidor MySQL
$user = 'gustavo'; // Usuário do MySQL
$password = '1120'; // Senha do MySQL
$dbname = 'story_telling'; // Nome do banco de dados
$port = 3307; // Porta do MySQL

// Conexão com o banco de dados
$conn = new mysqli($host, $user, $password, $dbname, $port);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Obtém o índice da instância a partir do parâmetro de URL
$index = isset($_GET['index']) ? (int)$_GET['index'] : 1;

// Define a sessão inicial como "Roteiro" se não estiver definida
if (!isset($_SESSION['sessao'])) {
    $_SESSION['sessao'] = 'Roteiro';
}

// Se a sessão for mudada, atualiza a variável de sessão
if (isset($_GET['sessao'])) {
    $_SESSION['sessao'] = $_GET['sessao'];
}

// Lida com o formulário de submissão para salvar conteúdo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conteudo = $_POST['conteudo'];
    $sessaoAtual = $_SESSION['sessao'];

    // Insere ou atualiza o conteúdo no banco de dados
    $sql = "INSERT INTO historias (instancia_index, sessao, conteudo) VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE conteudo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $index, $sessaoAtual, $conteudo, $conteudo);
    $stmt->execute();
    $stmt->close();
}

// Obtém o conteúdo salvo do banco de dados para a instância atual e a sessão
$sql = "SELECT conteudo FROM historias WHERE instancia_index = ? AND sessao = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $index, $_SESSION['sessao']);
$stmt->execute();
$stmt->bind_result($conteudoAtual);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Bloco De História <?php echo $index; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .navbar {
            background-color: #d3d3d3;
            padding: 10px;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        .navbar a {
            text-decoration: none;
            color: black;
            font-weight: bold;
        }
        .content {
            margin: 20px;
            padding: 20px;
        }
        .title {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .buttons {
            margin-bottom: 20px;
        }
        .buttons button {
            background-color: #d3d3d3;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
        }
        .text-area {
            width: 80%;
            height: 150px;
            margin: 20px auto;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 10px 0;
            border-top: 1px solid #dee2e6;
        }
        .current-session {
            font-size: 18px;
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="#">Story-Telling</a>
        <a href="#">Material</a>
        <a href="#">Analise Narrativa</a>
        <a href="#">Conta</a>
    </div>

    <div class="content">
        <div class="title">Bloco De História <?php echo $index; ?></div>
        
        <!-- Mostra a sessão atual -->
        <div class="current-session">Sessão Atual: <?php echo $_SESSION['sessao']; ?></div>
        
        <div class="buttons">
            <a href="?index=<?php echo $index; ?>&sessao=Roteiro"><button>Roteiro</button></a>
            <a href="?index=<?php echo $index; ?>&sessao=Personagens"><button>Personagens</button></a>
            <a href="?index=<?php echo $index; ?>&sessao=Elementos"><button>Elementos</button></a>
            <a href="?index=<?php echo $index; ?>&sessao=Mundo"><button>Mundo</button></a>
        </div>

        <!-- Formulário para salvar o texto -->
        <form method="post">
            <textarea class="text-area" name="conteudo" placeholder="Escreva aqui..."><?php echo htmlspecialchars($conteudoAtual); ?></textarea>
            <br>
            <button type="submit">Salvar</button>
            <!-- Botão Voltar ao Diagrama -->
            <a href="Diagrama-Contrucao.php"><button type="button">Voltar ao Diagrama</button></a>
        </form>
    </div>

    <div class="footer">
        Direitos Autorais
    </div>
</body>
</html>

<?php
// Fecha a conexão com o banco de dados
$conn->close();
?>
