<?php
session_start();

// Inicializar a lista de pontos se não estiver definida
if (!isset($_SESSION['points'])) {
    $_SESSION['points'] = [
        ['x' => 50, 'y' => 350] // Ponto inicial fixo
    ];
}

// Processar a ação dos botões
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['clear'])) {
        // Limpar os pontos
        $_SESSION['points'] = [['x' => 50, 'y' => 350]]; // Reseta para o ponto inicial fixo
    } elseif (isset($_POST['delete_last'])) {
        // Deletar o último ponto, mantendo sempre o ponto inicial fixo
        if (count($_SESSION['points']) > 1) {
            array_pop($_SESSION['points']);
        }
    } else {
        $lastPoint = end($_SESSION['points']); // Último ponto da lista
        $newPoint = ['x' => $lastPoint['x'], 'y' => $lastPoint['y']]; // Novo ponto inicializado no mesmo local

        $direction = $_POST['direction'];

        // Ajustar as coordenadas do novo ponto com base na direção
        switch ($direction) {
            case 'up':
                $newPoint['x'] += 50; // Movimento diagonal para cima e à direita
                $newPoint['y'] -= 50;
                break;
            case 'down':
                $newPoint['x'] += 50; // Movimento diagonal para baixo e à direita
                $newPoint['y'] += 50;
                break;
            case 'right':
                $newPoint['x'] += 50; // Movimento horizontal para a direita
                break;
        }

        // Adicionar o novo ponto à lista
        $_SESSION['points'][] = $newPoint;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Story-Telling - Controle</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
    body {
      padding-top: 20px;
    }
    .navbar-nav .nav-link {
      color: black;
      font-weight: bold;
    }
    .control-panel {
      margin-top: 50px;
    }
    .btn-custom {
      width: 150px;
      height: 50px;
      background-color: lightgray;
      border: 1px solid black;
      color: black;
      font-weight: bold;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 20px;
    }
    .btn-custom i {
      margin-left: 10px;
    }
    .canvas-container {
      border: 1px solid black;
      background-color: white;
      position: relative;
      width: 800px;
      height: 400px;
    }
    .segment-link {
      position: absolute;
      pointer-events: auto; /* Certificar-se de que o link possa ser clicado */
    }
    .footer {
      text-align: center;
      padding: 10px 0;
      background-color: #f8f9fa;
      border-top: 1px solid #dee2e6;
      position: absolute;
      width: 100%;
      bottom: 0;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Story-Telling</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="Material-Escrito.php">Material</a></li>
        <li class="nav-item"><a class="nav-link" href="Diagramas.php">Diagramas</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Analise Narrativa</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">Conta</a></li>
      </ul>
    </div>
  </nav>

  <div class="container">
    <div class="row control-panel">
      <div class="col-md-2">
        <form method="post">
          <button class="btn btn-custom" name="direction" value="up">
            Subir
            <i class="fas fa-arrow-up"></i>
          </button>
          <button class="btn btn-custom" name="direction" value="down">
            Descer
            <i class="fas fa-arrow-down"></i>
          </button>
          <button class="btn btn-custom" name="direction" value="right">
            Ambiguidade
            <i class="fas fa-arrow-right"></i>
          </button>
          <button class="btn btn-custom" name="clear" value="1">
            Limpar
            <i class="fas fa-trash"></i>
          </button>
          <button class="btn btn-custom" name="delete_last" value="1">
            Deletar Último
            <i class="fas fa-undo"></i>
          </button>
        </form>
      </div>
      <div class="col-md-10">
        <div class="canvas-container">
          <canvas id="storyCanvas" width="800" height="400"></canvas>
          <!-- Links serão adicionados dinamicamente aqui -->
        </div>
      </div>
    </div>
  </div>

  <footer class="footer">
    <p>Direitos Autorais</p>
  </footer>

  <script>
    const canvas = document.getElementById('storyCanvas');
    const ctx = canvas.getContext('2d');
    const canvasContainer = document.querySelector('.canvas-container');
    
    let points = <?php echo json_encode($_SESSION['points']); ?>;

    // Limpa o canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Desenhar as linhas conectando os pontos
    ctx.beginPath();
    ctx.moveTo(points[0].x, points[0].y);

    // Adicionar links e contornos para cada segmento
    points.forEach((point, index) => {
      if (index > 0) {
        const prevPoint = points[index - 1];
        const x1 = prevPoint.x;
        const y1 = prevPoint.y;
        const x2 = point.x;
        const y2 = point.y;
        const width = Math.abs(x2 - x1);
        const height = Math.abs(y2 - y1);

        // Desenhar a linha
        ctx.lineTo(point.x, point.y);

        // Desenhar o contorno da linha
        ctx.lineWidth = 2; // Largura da linha
        ctx.strokeStyle = 'black'; // Cor da linha principal
        ctx.stroke();

        // Criar um elemento link sobre o segmento
        const link = document.createElement('a');
        link.href = `Construcao-Historia.php?index=${index}`; // Usar parâmetro de URL para a página dinâmica
        link.className = 'segment-link';
        link.style.left = `${Math.min(x1, x2)}px`;
        link.style.top = `${Math.min(y1, y2)}px`;
        link.style.width = `${width}px`;
        link.style.height = `${height}px`;
        link.style.border = '2px solid red'; // Adiciona um contorno visível ao link
        canvasContainer.appendChild(link);

        // Reposicionar o começo da linha para o próximo segmento
        ctx.beginPath();
        ctx.moveTo(point.x, point.y);
      }
    });

    // Finalizar a linha
    ctx.strokeStyle = 'black'; // Cor da linha principal
    ctx.lineWidth = 2; // Largura da linha principal
    ctx.stroke();
  </script>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
