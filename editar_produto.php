<?php
include 'db.php'; 

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Atualizar produto via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id'])) {
        echo "<script>alert('ID do produto não enviado.'); window.location.href='listar_produtos.php';</script>";
        exit;
    }

    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao']; // HTML vindo do TinyMCE

    // Busca imagem atual
    $sqlSelect = "SELECT imagem FROM produtos WHERE id = ?";
    $stmtSelect = $conn->prepare($sqlSelect);
    $stmtSelect->bind_param("i", $id);
    $stmtSelect->execute();
    $result = $stmtSelect->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Produto não encontrado.'); window.location.href='listar_produtos.php';</script>";
        exit;
    }

    $produto = $result->fetch_assoc();
    $imagemPath = $produto['imagem'];

    // Upload de nova imagem (opcional)
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'static/images/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $nomeTemp = $_FILES['imagem']['tmp_name'];
        $nomeImagem = basename($_FILES['imagem']['name']);
        $imagemUnica = $nomeImagem;
        $nomeFinal = $uploadDir . $imagemUnica;


        if (move_uploaded_file($nomeTemp, $nomeFinal)) {
            $imagemPath = $imagemUnica;
        } else {
            echo "<script>alert('Erro ao fazer upload da imagem.');</script>";
        }
    }

    // Atualizar produto
    $sqlUpdate = "UPDATE produtos SET nome = ?, preco = ?, descricao = ?, imagem = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("sdssi", $nome, $preco, $descricao, $imagemPath, $id);

    if ($stmtUpdate->execute()) {
        echo "<script>alert('Produto atualizado com sucesso!'); window.location.href='listar_produtos.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar: " . $stmtUpdate->error . "');</script>";
    }

    exit;
}

// Carrega produto para edição via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM produtos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $produto = $result->fetch_assoc();
    } else {
        echo "<script>alert('Produto não encontrado.'); window.location.href='listar_produtos.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID do produto não fornecido.'); window.location.href='listar_produtos.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <script src="https://cdn.tiny.cloud/1/2o7tsvbgi9ftrzw41lg5rsoedppf1acxxck0n85yjle7ller/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
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
<section class="section">
    <div class="container">
        <h1 class="title has-text-centered">Editar Produto</h1>

        <form method="post" enctype="multipart/form-data" class="box">
            <input type="hidden" name="id" value="<?= htmlspecialchars($produto['id']) ?>">

            <div class="field">
                <label class="label">Nome</label>
                <div class="control">
                    <input class="input" type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Preço</label>
                <div class="control">
                    <input class="input" type="number" step="0.01" name="preco" value="<?= htmlspecialchars($produto['preco']) ?>" required>
                </div>
            </div>

            <div class="field">
                <label class="label">Descrição</label>
                <div class="control">
                    <textarea id="descricao" class="textarea" name="descricao"><?= htmlspecialchars($produto['descricao']) ?></textarea>
                </div>
            </div>

            <div class="field">
                <label class="label">Imagem Atual</label>
                <figure class="image is-128x128">
                    <img src="static/images/<?= htmlspecialchars($produto['imagem']) ?>" alt="Imagem do Produto">
                </figure>
            </div>

            <div class="field">
                <label class="label">Nova Imagem (opcional)</label>
                <div class="file has-name is-fullwidth">
                    <label class="file-label">
                        <input class="file-input" type="file" name="imagem">
                        <span class="file-cta">
                            <span class="file-icon">📁</span>
                            <span class="file-label">Escolher arquivo</span>
                        </span>
                        <span class="file-name">Nenhum arquivo escolhido</span>
                    </label>
                </div>
            </div>

            <div class="field is-grouped is-justify-content-center">
                <div class="control">
                    <button type="submit" class="button is-primary">Atualizar Produto</button>
                </div>
                <div class="control">
                    <a href="listar_produtos.php" class="button is-light">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
    // Atualiza nome do ficheiro selecionado
    const fileInput = document.querySelector('.file-input');
    const fileName = document.querySelector('.file-name');

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            fileName.textContent = fileInput.files[0].name;
        }
    });
</script>
</body>
</html>
