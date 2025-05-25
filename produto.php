<?php
$conn = new mysqli("localhost", "root", "", "skateshop");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $produto_id = $_GET['id'];

    // Buscar produto
    $sql = "SELECT * FROM produtos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erro na consulta SQL: " . $conn->error);
    }
    $stmt->bind_param("i", $produto_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $produto = $result->fetch_assoc();
    } else {
        echo "Produto não encontrado!";
        exit;
    }

    // Buscar tamanhos disponíveis
    $tamanhos = [];
    $sqlTamanhos = "SELECT tamanho, stock FROM tamanhos_produto WHERE produto_id = ? AND disponivel = 1 AND stock > 0";
    $stmtTamanhos = $conn->prepare($sqlTamanhos);
    $stmtTamanhos->bind_param("i", $produto_id);
    $stmtTamanhos->execute();
    $resultTamanhos = $stmtTamanhos->get_result();

    while ($row = $resultTamanhos->fetch_assoc()) {
        $tamanhos[] = ['tamanho' => $row['tamanho'], 'stock' => $row['stock']];
    }

    $tamanhoUnico = false;
    if (count($tamanhos) === 1) {
        $tamanhoUnico = true;
        $tamanhoUnicoValor = $tamanhos[0]['tamanho'];
        $stockUnico = $tamanhos[0]['stock'];
    }

} else {
    echo "ID do produto não especificado!";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<?php include('head.html'); ?>
<link rel="stylesheet" href="static/styles.css">
<style>
.tamanhos-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 15px;
}
.tamanho-btn {
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background: #f4f4f4;
    cursor: pointer;
}
.tamanho-btn.selected {
    background: #333;
    color: #fff;
    border-color: #000;
}
input[name="tamanho"] {
    display: none;
}
</style>
<body>
<?php include('header.php'); ?>
<title><?php echo $produto['nome']; ?></title>

<main class="produto-pagina">
    <div class="produto-detalhes">
        <div class="produto-imagem">
            <img src="static/images/<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" class="produto-img">
        </div>
        <div class="produto-informacoes">
            <h1 class="produto-nome"><?php echo $produto['nome']; ?></h1>
            <div class="informacoes">
                <p class="produto-descricao"><?php echo $produto['descricao']; ?></p>
                <p class="produto-preco"><strong>Preço</strong>: €<?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>

                <?php if (!empty($tamanhos)): ?>
                <form action="adicionar_cart.php" method="POST">
                    <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                    <input type="hidden" name="preco" value="<?php echo $produto['preco']; ?>">

                    <?php if ($tamanhoUnico): ?>
                        <input type="hidden" name="tamanho" value="<?= htmlspecialchars($tamanhoUnicoValor) ?>">
                        <p><strong>Tamanho:</strong> Único</p>
                        <label for="quantidade">Quantidade:</label>
                        <input type="number" name="quantidade" value="1" min="1" max="<?= $stockUnico ?>" required>
                    <?php else: ?>
                        <label for="tamanho">Tamanho:</label>
                        <div class="tamanhos-wrapper">
                            <?php foreach ($tamanhos as $t): ?>
                                <label class="tamanho-btn" data-stock="<?= $t['stock'] ?>">
                                    <input type="radio" name="tamanho" value="<?= htmlspecialchars($t['tamanho']) ?>" required>
                                    <?= htmlspecialchars($t['tamanho']) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>

                    <label for="quantidade">Quantidade:</label>
                    <select name="quantidade" required>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>

                    <?php endif; ?>

                    <button type="submit" class="btn">Adicionar ao carrinho</button>
                </form>
                <?php else: ?>
                    <p class="out-of-stock">Sem stock disponível de momento</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<footer>
    <p>2025 © Sk8Nation. Todos os direitos reservados.</p>
</footer>

<script>
document.querySelectorAll('.tamanho-btn input').forEach(input => {
    input.addEventListener('change', () => {
        document.querySelectorAll('.tamanho-btn').forEach(btn => btn.classList.remove('selected'));
        input.parentElement.classList.add('selected');

        const stock = parseInt(input.parentElement.dataset.stock);
        const quantidadeInput = document.querySelector('input[name="quantidade"]');
        quantidadeInput.max = stock;
        quantidadeInput.value = 1;
    });
});
</script>
</body>
</html>
