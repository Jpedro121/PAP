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
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $arquivoTmp = $_FILES['imagem']['tmp_name'];
        $nomeOriginal = basename($_FILES['imagem']['name']);
        $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($extensao, $permitidas)) {
            die("Erro: Tipo de arquivo não permitido. Só JPG, PNG, GIF.");
        }

        // Gerar nome único para evitar conflitos
        $novoNome = uniqid('img_') . '.' . $extensao;
        $destino = __DIR__ . '/static/images/' . $novoNome;

        if (!move_uploaded_file($arquivoTmp, $destino)) {
            die("Erro ao mover o ficheiro enviado.");
        }
    } else {
        die("Erro: imagem não enviada ou com erro.");
    }

    $imagem_nome = $novoNome;

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
            $stmt_categoria = $conn->prepare("INSERT INTO decks (produto_id, tamanho, marca, estoque, descricao) VALUES (?, ?, ?, ?, ?)");
            $stmt_categoria->bind_param("issis", $produto_id, $tamanho, $marca, $estoque, $descricao_deck);
            break;

        case 'trucks':
            $descricao_truck = $_POST['descricao'] ?? '';
            $stmt_categoria = $conn->prepare("INSERT INTO trucks (produto_id, tamanho, marca, estoque, descricao) VALUES (?, ?, ?, ?, ?)");
            $stmt_categoria->bind_param("issis", $produto_id, $tamanho, $marca, $estoque, $descricao_truck);
            break;

        case 'rodas':
            $dureza = $_POST['dureza'] ?? '';
            $descricao_rodas = $_POST['descricao'] ?? '';
            $stmt_categoria = $conn->prepare("INSERT INTO rodas (produto_id, tamanho, marca, dureza, estoque, descricao) VALUES (?, ?, ?, ?, ?, ?)");
            if (!$stmt_categoria) {
                die("Erro ao preparar statement (rodas): " . $conn->error);
            }
            $stmt_categoria->bind_param("isssis", $produto_id, $tamanho, $marca, $dureza, $estoque, $descricao_rodas);
            break;

        case 'rolamentos':
            $stmt_categoria = $conn->prepare("INSERT INTO rolamentos (produto_id, tamanho) VALUES (?, ?)");
            $stmt_categoria->bind_param("is", $produto_id, $tamanho);
            break;

        case 't-shirts':
        case 'tshirts':
            $stmt_categoria = $conn->prepare("INSERT INTO tshirts (produto_id, tamanho, marca, estoque) VALUES (?, ?, ?, ?)");
            $stmt_categoria->bind_param("issi", $produto_id, $tamanho, $marca, $estoque);
            break;

        case 'sweats':
            $stmt_categoria = $conn->prepare("INSERT INTO sweats (produto_id, tamanho, marca, estoque) VALUES (?, ?, ?, ?)");
            $stmt_categoria->bind_param("issi", $produto_id, $tamanho, $marca, $estoque);
            break;

        case 'pants':
            $stmt_categoria = $conn->prepare("INSERT INTO pants (produto_id, tamanho, marca, estoque) VALUES (?, ?, ?, ?)");
            $stmt_categoria->bind_param("issi", $produto_id, $tamanho, $marca, $estoque);
            break;

        case 'shorts':
            $stmt_categoria = $conn->prepare("INSERT INTO shorts (produto_id, tamanho, marca, estoque) VALUES (?, ?, ?, ?)");
            $stmt_categoria->bind_param("issi", $produto_id, $tamanho, $marca, $estoque);
            break;

        default:
            $stmt_categoria = null;
            break;
    }

    if ($stmt_categoria) {
        if (!$stmt_categoria->execute()) {
            die("Erro ao inserir na tabela específica: " . $stmt_categoria->error);
        }
        $stmt_categoria->close();
    }

    echo "<script>alert('Produto adicionado com sucesso!');</script>";
    echo "<script>window.location.href = 'produtos_admin.php';</script>";
}

$categorias_result = $conn->query("SELECT * FROM categorias");
$categorias = $categorias_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <title>Dashboard do Administrador</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.tiny.cloud/1/2o7tsvbgi9ftrzw41lg5rsoedppf1acxxck0n85yjle7ller/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>    
    <script>
        tinymce.init({
            selector: 'textarea#descricao',
            plugins: 'lists',
            toolbar: 'undo redo | formatselect | bold italic underline | bullist numlist | h2 h3',
            menubar: false,
            height: 300,
            branding: false,
            setup: function (editor) {
                editor.ui.registry.addMenuButton('h2', {
                    text: 'Título',
                    fetch: function (callback) {
                        callback([
                            {
                                type: 'menuitem',
                                text: 'Título 2 (H2)',
                                onAction: function () {
                                    editor.execCommand('FormatBlock', false, 'h2');
                                }
                            },
                            {
                                type: 'menuitem',
                                text: 'Título 3 (H3)',
                                onAction: function () {
                                    editor.execCommand('FormatBlock', false, 'h3');
                                }
                            }
                        ]);
                    }
                });
            }
        });
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin - SkateShop</a>
            <div class="d-flex">
                <a href="/PAP/home.php" class="btn btn-outline-light">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Painel de Administração</h1>

        <form method="post" enctype="multipart/form-data" class="mb-5">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nome do Produto*</label>
                    <input type="text" name="nome" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Categoria*</label>
                    <select name="categoria_id" class="form-select" required onchange="atualizarEspecificacoes()">
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Descrição*</label>
                <textarea id="descricao" name="descricao" class="form-control"></textarea>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Preço*</label>
                    <input type="number" step="0.01" name="preco" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Marca*</label>
                    <input type="text" name="marca" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Categoria (nome)*</label>
                    <input type="text" name="categoria" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Imagem do Produto*</label>
                <input type="file" name="imagem" class="form-control" accept=".jpg,.jpeg,.png,.gif" required>
            </div>

            <div class="mb-3" id="tamanho-container">
                <label class="form-label">Tamanho*</label>
                <input type="text" name="tamanho" class="form-control" required>
            </div>

            <div id="especificacoes-extra"></div>

            <button type="submit" class="btn btn-primary">Adicionar Produto</button>
        </form>

        <div class="row g-4">
            <div class="col-md-4"><a href="login/admin_users.php" class="btn btn-primary w-100">Gerir Utilizadores</a></div>
            <div class="col-md-4"><a href="produtos_admin.php" class="btn btn-success w-100">Gerir Produtos</a></div>
            <div class="col-md-4"><a href="encomendas_admin.php" class="btn btn-warning w-100">Ver Encomendas</a></div>
        </div>
    </div>

    <script>
        function atualizarEspecificacoes() {
            const categoriaSelect = document.querySelector("select[name='categoria_id']");
            const categoriaTexto = categoriaSelect.options[categoriaSelect.selectedIndex].text.toLowerCase();
            const extraFields = document.getElementById("especificacoes-extra");
            const tamanhoContainer = document.getElementById("tamanho-container");
            const categoriaNomeInput = document.querySelector("input[name='categoria']");

            categoriaNomeInput.value = categoriaSelect.options[categoriaSelect.selectedIndex].text;
            extraFields.innerHTML = '';

            const roupas = ['t-shirts', 'sweats', 'pants', 'shorts'];

            if (roupas.includes(categoriaTexto)) {
                tamanhoContainer.innerHTML = `
                    <label class="form-label">Tamanho</label>
                    <select name="tamanho" class="form-select" required>
                        <option value="XS">XS</option>
                        <option value="S">S</option>
                        <option value="M" selected>M</option>
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

        document.addEventListener('DOMContentLoaded', atualizarEspecificacoes);
    </script>
</body>
</html>
