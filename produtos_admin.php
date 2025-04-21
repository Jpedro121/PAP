<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login/login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "skateshop");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $categoria_id = $_POST['categoria_id'];
    $categoria_nome = $_POST['categoria'];
    $preco = $_POST['preco'];
    $imagem = $_POST['imagem'];
    $tamanho = $_POST['tamanho'];

    $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, preco, imagem, categoria_id, tamanho, categoria) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdssis", $nome, $descricao, $preco, $imagem, $categoria_id, $tamanho, $categoria_nome);
    $stmt->execute();
    $stmt->close();

    $produto_id = $conn->insert_id;
    $categoria_lower = strtolower($categoria_nome);

    switch ($categoria_lower) {
        case 'decks':
            $marca = $_POST['marca'] ?? '';
            $descricao_deck = $_POST['descricao'] ?? '';
            $estoque = 10;

            $stmt_deck = $conn->prepare("INSERT INTO decks (produto_id, tamanho, marca, estoque, descricao) VALUES (?, ?, ?, ?, ?)");
            $stmt_deck->bind_param("issis", $produto_id, $tamanho, $marca, $estoque, $descricao_deck);
            $stmt_deck->execute();
            $stmt_deck->close();
            break;

        case 'trucks':
            $marca = $_POST['marca'] ?? '';
            $estoque = 10;
            $descricao_truck = $_POST['descricao'] ?? '';

            $stmt_truck = $conn->prepare("INSERT INTO trucks (produto_id, tamanho, marca, estoque, descricao) VALUES (?, ?, ?, ?, ?)");
            $stmt_truck->bind_param("issis", $produto_id, $tamanho, $marca, $estoque, $descricao_truck);
            $stmt_truck->execute();
            $stmt_truck->close();
            break;

        case 'rodas':
            $marca = $_POST['marca'] ?? '';
            $dureza = $_POST['dureza'] ?? '';
            $estoque = 10;
            $descricao_rodas = $_POST['descricao'] ?? '';

            $stmt_rodas = $conn->prepare("INSERT INTO rodas (produto_id, tamanho, marca, dureza, estoque, descricao) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_rodas->bind_param("isssis", $produto_id, $tamanho, $marca, $dureza, $estoque, $descricao_rodas);
            $stmt_rodas->execute();
            $stmt_rodas->close();
            break;

        case 'rolamentos':
            $marca = $_POST['marca'] ?? '';
            $descricao_rol = $_POST['descricao'] ?? '';
            $estoque = 10;

            $stmt_rol = $conn->prepare("INSERT INTO rolamentos (produto_id, tamanho) VALUES (?, ?)");
            $stmt_rol->bind_param("is", $produto_id, $tamanho);
            $stmt_rol->execute();
            $stmt_rol->close();
            break;
    }
}

$categorias_result = $conn->query("SELECT * FROM categorias");
$categorias = $categorias_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Administrador</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script>
        function atualizarEspecificacoes() {
            const categoriaSelect = document.querySelector("select[name='categoria_id']");
            const extraFields = document.getElementById("especificacoes-extra");
            const categoria = categoriaSelect.options[categoriaSelect.selectedIndex].text.toLowerCase();

            extraFields.innerHTML = '';

            if (categoria === 'rodas') {
                extraFields.innerHTML = `
                    <div class="mb-3">
                        <label class="form-label">Dureza</label>
                        <input type="text" name="dureza" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Marca</label>
                        <input type="text" name="marca" class="form-control">
                    </div>`;
            } else if (categoria === 'decks' || categoria === 'trucks') {
                extraFields.innerHTML = `
                    <div class="mb-3">
                        <label class="form-label">Marca</label>
                        <input type="text" name="marca" class="form-control">
                    </div>`;
            }
        }
    </script>
</head>
<body onload="atualizarEspecificacoes()">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin - SkateShop</a>
            <div class="d-flex">
                <a class="btn btn-outline-light" href="../login/logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Painel de Administração</h1>

        <form method="post" class="mb-5">
            <div class="mb-3">
                <label class="form-label">Nome do Produto</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descrição</label>
                <textarea name="descricao" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Categoria</label>
                <select name="categoria_id" class="form-select" required onchange="atualizarEspecificacoes()">
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= $cat['nome'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Categoria (nome)</label>
                <input type="text" name="categoria" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Preço</label>
                <input type="number" step="0.01" name="preco" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Imagem (URL ou nome do ficheiro)</label>
                <input type="text" name="imagem" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tamanho</label>
                <input type="text" name="tamanho" class="form-control" required>
            </div>
            <div id="especificacoes-extra"></div>
            <button type="submit" class="btn btn-primary">Adicionar Produto</button>
        </form>

        <div class="row g-4">
            <div class="col-md-4">
                <a href="login/admin_users.php" class="btn btn-primary w-100">Gerir Utilizadores</a>
            </div>
            <div class="col-md-4">
                <a href="produtos_admin.php" class="btn btn-success w-100">Gerir Produtos</a>
            </div>
            <div class="col-md-4">
                <a href="encomendas_admin.php" class="btn btn-warning w-100">Ver Encomendas</a>
            </div>
        </div>
    </div>
</body>
</html>
