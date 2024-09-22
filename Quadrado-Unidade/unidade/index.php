<!DOCTYPE html>
<html lang="en">
<?php require_once("unidade.php"); ?>

<head>
    <title>Nova Unidade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container mt-4">
        <nav class="mb-4">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="../quadrado/index.php">Cadastro de Quadrados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Cadastro de Unidade</a>
                </li>
            </ul>
        </nav>

        <h2>Cadastro de Unidade</h2>
        <form action="unidade.php" method="post">
            <input type="number" name="id" id="id" value="<?= $id ?>" hidden>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nome_un" class="form-label">Nome da unidade:</label>
                    <input type="text" class="form-control" name="nome_un" id="nome_un" placeholder="Nome da unidade" value="<?= $id ? $contato->getNome() : "" ?>">
                </div>
                <div class="col-md-4">
                    <label for="un" class="form-label">Unidade:</label>
                    <input type="text" class="form-control" name="un" id="un" placeholder="Unidade" value="<?= $id ? $contato->getTipo() : "" ?>">
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" name="acao" id="acao" value="Salvar" class="btn btn-primary me-2">Salvar</button>
                <button type="reset" name="resetar" id="resetar" class="btn btn-secondary">Resetar</button>
            </div>
        </form>

        <hr>

        <h3>Pesquisa</h3>
        <form action="" method="get" class="mb-3">
            <div class="row">
                <div class="col-md-8">
                    <input type="text" class="form-control" name="busca" id="busca" placeholder="Pesquisar">
                </div>
                <div class="col-md-2">
                    <select name="tipo" id="tipo" class="form-select">
                        <option value="1">ID</option>
                        <option value="2">Nome</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" name="acao" id="acao" value="Buscar" class="btn btn-success w-100">Buscar</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>Unidade</th>
                    <th>Açoes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lista as $unidade): ?>
                    <tr>
                        <td><a href="index.php?id=<?= $unidade->getIdUnidade() ?>"><?= $unidade->getIdUnidade() ?></a></td>
                        <td><?= $unidade->getNome() ?></td>
                        <td><?= $unidade->getTipo() ?></td>
                        <td><a href="delete.php?id=<?= $unidade->getIdUnidade() ?>">Excluir</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
