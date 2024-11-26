<?php
include 'database.php';
include 'Livro.php';
session_start();

// Verifica se o usuário é administrador (nivelPermissao 2)
if (!isset($_SESSION['user_id']) || $_SESSION['nivelPermissao'] != 2) {
    header("Location: index.php");
    exit();
}

// Instancia a classe Livro
$livro = new Livro($conn);

$mensagem = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $anoPublicacao = $_POST['anoPublicacao'];
    $fotoCapa = $_POST['fotoCapa'];
    $idCategoria = $_POST['idCategoria'];
    $preco = $_POST['preco'];
    $idAutor = $_POST['idAutor'];

    // Adiciona o livro
    $mensagem = $livro->adicionarLivro($titulo, $anoPublicacao, $fotoCapa, $idCategoria, $preco, $idAutor);
}

// Obtém categorias e autores
$categorias = $livro->obterCategorias();
$autores = $livro->obterAutores();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Adicionar Livro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Adicionar Novo Livro</h2>

    <!-- Mensagem de sucesso ou erro -->
    <?php if ($mensagem): ?>
        <div class="alert alert-info"><?= $mensagem ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" class="form-control" name="titulo" required>
        </div>
        <div class="form-group">
            <label for="anoPublicacao">Ano de Publicação:</label>
            <input type="number" class="form-control" name="anoPublicacao">
        </div>
        <div class="form-group">
            <label for="fotoCapa">URL da Foto de Capa:</label>
            <input type="text" class="form-control" name="fotoCapa">
        </div>
        <div class="form-group">
            <label for="idCategoria">Categoria:</label>
            <select class="form-control" name="idCategoria" required>
                <option value="">Selecione uma Categoria</option>
                <?php while ($categoria = $categorias->fetch_assoc()): ?>
                    <option value="<?= $categoria['id']; ?>"><?= $categoria['titulo']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="idAutor">Autor:</label>
            <select class="form-control" name="idAutor" required>
                <option value="">Selecione um Autor</option>
                <?php while ($autor = $autores->fetch_assoc()): ?>
                    <option value="<?= $autor['id']; ?>"><?= $autor['nome']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="preco">Preço:</label>
            <input type="text" class="form-control" name="preco" required>
        </div>
        <button type="submit" class="btn btn-primary">Adicionar Livro</button>
    </form>
    <br>
    <!-- Botões para criar categorias e autores -->
    <a href="create_category.php" class="btn btn-secondary">Criar Nova Categoria</a>
    <a href="create_author.php" class="btn btn-secondary">Criar Novo Autor</a>
</div>
</body>
</html>
