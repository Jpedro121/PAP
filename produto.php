<?php
    $conn = new mysqli("localhost", "root", "", "skateshop");

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    if (isset($_GET['id'])) {
        $produto_id = $_GET['id'];

        $sql = "SELECT p.id, p.nome, p.imagem, p.preco, p.descricao, 
                    COALESCE(d.tamanho, t.tamanho, r.tamanho) AS tamanho
                FROM produtos p
                LEFT JOIN decks d ON p.id = d.produto_id
                LEFT JOIN trucks t ON p.id = t.produto_id
                LEFT JOIN rodas r ON p.id = r.produto_id
                WHERE p.id = ?";

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
                    <?php if (!empty($produto['tamanho'])): ?>
                        <p class="produto-tamanho"><strong>Tamanho:</strong> <?php echo $produto['tamanho']; ?></p>
                    <?php endif; ?>
                    <p class="produto-descricao"><?php echo $produto['descricao']; ?></p>
                    <p class="produto-preco"><strong>Preço:</strong> €<?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                    <form action="adicionar_cart.php" method="POST">
                        <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                        <input type="hidden" name="preco" value="<?php echo $produto['preco']; ?>">
                        <input type="number" name="quantidade" value="1" min="1" required>
                        <button type="submit" class="btn">Adicionar ao carrinho</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>2025 Skateshop. Todos os direitos reservados.</p>
    </footer>
</body>
</html>