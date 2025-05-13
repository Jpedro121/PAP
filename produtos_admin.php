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
    $tamanho = $_POST['tamanho'];
    $marca = $_POST['marca'];

    // Upload da imagem
    $imagem_nome = '';
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $imagem_nome = basename($_FILES['imagem']['name']);
        $target_path = "uploads/" . $imagem_nome;
        move_uploaded_file($_FILES['imagem']['tmp_name'], $target_path);
    }

    $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, preco, imagem, categoria_id, tamanho, categoria, marca) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdssiss", $nome, $descricao, $preco, $imagem_nome, $categoria_id, $tamanho, $categoria_nome, $marca);
    $stmt->execute();
    $produto_id = $conn->insert_id;
    $stmt->close();

    $categoria_lower = strtolower($categoria_nome);
    $estoque = 10;

    switch ($categoria_lower) {
        case 'decks':
            $descricao_deck = $_POST['descricao'] ?? '';
            $stmt = $conn->prepare("INSERT INTO decks (produto_id, tamanho, marca, estoque, descricao) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issis", $produto_id, $tamanho, $marca, $estoque, $descricao_deck);
            $stmt->execute();
            $stmt->close();
            break;
        case 'trucks':
            $descricao_truck = $_POST['descricao'] ?? '';
            $stmt = $conn->prepare("INSERT INTO trucks (produto_id, tamanho, marca, estoque, descricao) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issis", $produto_id, $tamanho, $marca, $estoque, $descricao_truck);
            $stmt->execute();
            $stmt->close();
            break;
        case 'rodas':
            $dureza = $_POST['dureza'] ?? '';
            $descricao_rodas = $_POST['descricao'] ?? '';
            $stmt = $conn->prepare("INSERT INTO rodas (produto_id, tamanho, marca, dureza, estoque, descricao) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssis", $produto_id, $tamanho, $marca, $dureza, $estoque, $descricao_rodas);
            $stmt->execute();
            $stmt->close();
            break;
        case 'rolamentos':
            $stmt = $conn->prepare("INSERT INTO rolamentos (produto_id, tamanho) VALUES (?, ?)");
            $stmt->bind_param("is", $produto_id, $tamanho);
            $stmt->execute();
            $stmt->close();
            break;
    }
}

$categorias_result = $conn->query("SELECT * FROM categorias");
$categorias = $categorias_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <title>Dashboard do Administrador</title>
    <?php include('head.html'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <script>
    function atualizarEspecificacoes() {
        const categoriaSelect = document.querySelector("select[name='categoria_id']");
        const categoriaTexto = categoriaSelect.options[categoriaSelect.selectedIndex].text.toLowerCase();
        const extraFields = document.getElementById("especificacoes-extra");
        const tamanhoContainer = document.getElementById("tamanho-container");

        extraFields.innerHTML = '';

        const roupas = ['t-shirts', 'sweats', 'pants', 'shorts'];

        if (roupas.includes(categoriaTexto)) {
            tamanhoContainer.innerHTML = `
                <label class="form-label">Tamanho</label>
                <select name="tamanho" class="form-select" required>
                    <option value="XS">XS</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                </select>
            `;
        } else {
            tamanhoContainer.innerHTML = `
                <label class="form-label">Tamanho</label>
                <input type="text" name="tamanho" class="form-control" required>
            `;
        }

        if (categoriaTexto === 'rodas') {
            extraFields.innerHTML = `
                <div class="mb-3">
                    <label class="form-label">Dureza</label>
                    <input type="text" name="dureza" class="form-control" required>
                </div>
            `;
        }
    }
</script>       
</head>
<body onload="atualizarEspecificacoes()">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin - SkateShop</a>
            <div class="d-flex">
            <a class="btn btn-outline-light" href="/PAP/home.php"><button>Sair</button></a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Painel de Administração</h1>

        <form method="post" class="mb-5" enctype="multipart/form-data">
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
            <label class="form-label">Imagem</label>
            <input type="file" name="imagem" class="form-control" accept="image/*" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Marca</label>
            <input type="text" name="marca" class="form-control" required>
        </div>
        <div class="mb-3" id="tamanho-container">
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