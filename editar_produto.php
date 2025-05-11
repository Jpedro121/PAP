<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login/login.php");
    exit();
}

include 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID do produto não fornecido.";
    exit();
}

// Obter produto
$sql = "SELECT * FROM produtos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$produto = $result->fetch_assoc();

if (!$produto) {
    echo "Produto não encontrado.";
    exit();
}

// Obter descrições por categoria
$categorias = ['skates', 'roupa', 'tenis', 'acessorios'];
$descricoes = [];

foreach ($categorias as $categoria) {
    $sql = "SELECT descricao FROM descricao_categoria WHERE produto_id = ? AND categoria = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $id, $categoria);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $descricoes[$categoria] = $row['descricao'] ?? '';
}

// Atualização
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $tamanho = $_POST['tamanho'];
    $marca = $_POST['marca'];
    $categoria = $_POST['categoria'];

    // Upload de imagem
    $imagemPath = $produto['imagem']; // manter a imagem atual
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'static/images/Docks/';
        
        // Criar diretório se não existir
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $nomeTemp = $_FILES['imagem']['tmp_name'];
        $nomeFinal = $uploadDir . basename($_FILES['imagem']['name']);
        
        if (move_uploaded_file($nomeTemp, $nomeFinal)) {
            $imagemPath = $nomeFinal;
        } else {
            echo "<script>alert('Erro ao fazer upload da imagem.');</script>";
        }
    }

    // Atualizar produto com imagem
    $sql = "UPDATE produtos SET nome=?, descricao=?, preco=?, tamanho=?, marca=?, categoria=?, imagem=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssssi", $nome, $descricao, $preco, $tamanho, $marca, $categoria, $imagemPath, $id);
    $stmt->execute();

    // Atualizar descrições por categoria
    foreach ($categorias as $cat) {
        $desc = $_POST['descricao_' . $cat] ?? '';
        $sql_check = "SELECT produto_id FROM descricao_categoria WHERE produto_id=? AND categoria=?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("is", $id, $cat);
        $stmt_check->execute();
        $res = $stmt_check->get_result();

        if ($res->num_rows > 0) {
            $sql_update = "UPDATE descricao_categoria SET descricao=? WHERE produto_id=? AND categoria=?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("sis", $desc, $id, $cat);
            $stmt_update->execute();
        } else {
            $sql_insert = "INSERT INTO descricao_categoria (produto_id, categoria, descricao) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("iss", $id, $cat, $desc);
            $stmt_insert->execute();
        }
    }

    header("Location: listar_produtos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Editar Produto</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
  <section class="section">
    <div class="container">
      <div class="box">
        <h1 class="title is-4">Editar Produto: <?= htmlspecialchars($produto['nome']) ?></h1>

        <form method="post" enctype="multipart/form-data">
          <div class="field">
            <label class="label">Nome</label>
            <div class="control">
              <input class="input" type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
            </div>
          </div>

          <div class="field">
            <label class="label">Descrição Geral</label>
            <div class="control">
              <textarea class="textarea" name="descricao"><?= htmlspecialchars($produto['descricao']) ?></textarea>
            </div>
          </div>

          <div class="field">
            <label class="label">Preço</label>
            <div class="control">
              <input class="input" type="number" step="0.01" name="preco" value="<?= htmlspecialchars($produto['preco']) ?>" required>
            </div>
          </div>

          <div class="field">
            <label class="label">Tamanho</label>
            <div class="control">
              <input class="input" type="text" name="tamanho" value="<?= htmlspecialchars($produto['tamanho']) ?>">
            </div>
          </div>

          <div class="field">
            <label class="label">Marca</label>
            <div class="control">
              <input class="input" type="text" name="marca" value="<?= htmlspecialchars($produto['marca']) ?>">
            </div>
          </div>

          <div class="field">
            <label class="label">Categoria</label>
            <div class="control">
              <input class="input" type="text" name="categoria" value="<?= htmlspecialchars($produto['categoria']) ?>">
            </div>
          </div>

          <div class="field">
            <label class="label">Imagem</label>
            <?php if (!empty($produto['imagem'])): ?>
              <figure class="image is-128x128 mb-2">
                <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="Imagem atual">
              </figure>
            <?php endif; ?>
            <div class="control">
              <input class="input" type="file" name="imagem">
            </div>
          </div>
          <hr>
          <div class="field is-grouped mt-4">
            <div class="control">
              <button class="button is-success" type="submit">Atualizar Produto</button>
            </div>
            <div class="control">
              <a href="listar_produtos.php" class="button is-light">Cancelar</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</body>
</html>