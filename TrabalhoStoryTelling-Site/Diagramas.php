<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Diagramas</title>
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
    .actions {
      text-align: center;
      margin: 20px 0;
    }
    .btn-custom {
      background-color: lightgray;
      border: 1px solid black;
      color: black;
      font-weight: bold;
      display: inline-flex;
      justify-content: center;
      align-items: center;
      margin: 10px;
      padding: 10px 20px;
      cursor: pointer;
    }
    .btn-custom i {
      margin-right: 10px;
      font-size: 1.5em;
    }
    .diagrams-container {
      margin-top: 20px;
    }
    .diagram-box {
      width: 100%;
      height: 150px;
      background-color: lightgray;
      border: 1px solid black;
      margin-bottom: 20px;
      position: relative;
    }
    .footer {
      text-align: center;
      padding: 10px 0;
      background-color: #f8f9fa;
      border-top: 1px solid #dee2e6;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Story-Telling</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="Material-Escrito.php">Material</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Analise Narrativa</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">Conta</a></li>
      </ul>
    </div>
  </nav>

  <div class="container">
    <div class="actions">
      <button class="btn-custom">
        <i class="fas fa-trash-alt"></i> Excluir
      </button>
      <button class="btn-custom" onclick="window.location.href='Diagrama-Contrucao.php'">
        <i class="fas fa-plus-circle"></i> Adicionar
      </button>
    </div>
    <div class="diagrams-container row">
      <div class="col-md-4">
        <div class="diagram-box"></div>
      </div>
      <div class="col-md-4">
        <div class="diagram-box"></div>
      </div>
      <div class="col-md-4">
        <div class="diagram-box"></div>
      </div>
      <div class="col-md-4">
        <div class="diagram-box"></div>
      </div>
      <div class="col-md-4">
        <div class="diagram-box"></div>
      </div>
      <div class="col-md-4">
        <div class="diagram-box"></div>
      </div>
    </div>
  </div>

  <footer class="footer">
    <p>Direitos Autorais</p>
  </footer>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
