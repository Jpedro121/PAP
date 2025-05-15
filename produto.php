<?php
    $conn = new mysqli("localhost", "root", "", "skateshop");

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    if (isset($_GET['id'])) {
        $produto_id = $_GET['id'];

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

        // Buscar tamanhos disponíveis na tabela tamanhos_produto
        $sql_tamanhos = "SELECT tamanho FROM tamanhos_produto WHERE produto_id = ? AND disponivel = 1";
        $stmt_tamanhos = $conn->prepare($sql_tamanhos);
        $stmt_tamanhos->bind_param("i", $produto_id);
        $stmt_tamanhos->execute();
        $result_tamanhos = $stmt_tamanhos->get_result();
        $tamanhos_disponiveis = [];

        while ($row = $result_tamanhos->fetch_assoc()) {
            $tamanhos_disponiveis[] = $row['tamanho'];
        }

        function getTamanhosPorCategoria($categoria) {
            switch (strtolower($categoria)) {
                case 'deck':
                    return ['7\"', '7.25\"', '7.5\"', '7.75\"', '8\"', '8.25\"', '8.5\"', '8.75\"', '9\"', '9.25\"', '9.5\"', '10\"'];
                case 'truck':
                    return ['129mm', '139mm', '149mm', '159mm', '169mm'];
                case 'roda':
                    $tamanhos = [];
                    for ($i = 49; $i <= 75; $i++) {
                        $tamanhos[] = $i . 'mm';
                    }
                    return $tamanhos;
                case 'sapato':
                    return range(38, 47);
                case 'roupa':
                    return ['XS', 'S', 'M', 'L'];
                default:
                    return [];
            }
        }

        $todos_os_tamanhos = getTamanhosPorCategoria($produto['categoria']);
    } else {
        echo "ID do produto não especificado!";
        exit;
    }

    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<?php include('head.html'); ?>
<link rel="stylesheet" href="static/styles.css">
<style>
.tamanho-btn {
    margin: 4px;
    padding: 6px 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}
.tamanho-btn.disponivel {
    background-color: #4CAF50;
    color: white;
}
.tamanho-btn.indisponivel {
    background-color: #eee;
    color: #aaa;
    cursor: not-allowed;
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
                    <?php if (!empty($todos_os_tamanhos)): ?>
                        <div class="tamanhos">
                            <p><strong>Tamanhos disponíveis:</strong></p>
                            <?php foreach ($todos_os_tamanhos as $tamanho): ?>
                                <button class="tamanho-btn <?php echo in_array($tamanho, $tamanhos_disponiveis) ? 'disponivel' : 'indisponivel'; ?>" <?php echo in_array($tamanho, $tamanhos_disponiveis) ? '' : 'disabled'; ?>><?php echo $tamanho; ?></button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <p class="produto-descricao"><?php echo $produto['descricao']; ?>:</p>
                    <p class="produto-preco"><strong><?= $lang['Price'] ?></strong> €<?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                    <form action="adicionar_cart.php" method="POST">
                        <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                        <input type="hidden" name="preco" value="<?php echo $produto['preco']; ?>">
                        <input type="number" name="quantidade" value="1" min="1" required>
                        <button type="submit" class="btn"><?= $lang['add_to_cart'] ?></button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p><?= $lang['footer'] ?></p>
    </footer>
</body>
</html>
