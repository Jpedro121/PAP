<?php
session_start();
$conn = new mysqli("localhost", "root", "", "skateshop");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$total = 0;
$produtos = [];

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT c.id, p.nome, p.imagem, c.quantidade, c.preco, c.tamanho
            FROM carrinho c 
            JOIN produtos p ON c.produto_id = p.id 
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
        $total += $row['preco'] * $row['quantidade'];
    }
} else {
    if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
        foreach ($_SESSION['carrinho'] as $item) {
            $produto_id = $item['produto_id'];
            $quantidade = $item['quantidade'];
            $tamanho = $item['tamanho'] ?? null;
            $query = $conn->prepare("SELECT nome, imagem, preco FROM produtos WHERE id = ?");
            $query->bind_param("i", $produto_id);
            $query->execute();
            $res = $query->get_result();
            if ($res->num_rows > 0) {
                $produto = $res->fetch_assoc();
                $produto['quantidade'] = $quantidade;
                $produto['tamanho'] = $tamanho;
                $produto['id'] = $produto_id;
                $produtos[] = $produto;
                $total += $produto['preco'] * $quantidade;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>My Cart</title>
    <?php include('head.html'); ?>
</head>
<body>
<?php include('header.php'); ?>
<h1 class="titulo-carrinho">My Cart</h1>

<div class="carrinho-container">
    <?php if (!empty($produtos)): ?>
        <?php foreach ($produtos as $row): ?>
            <div class="item-carrinho">
                <img src="static/images/<?php echo $row['imagem']; ?>" alt="<?php echo $row['nome']; ?>">
                <div class="info">
                    <h2><?php echo $row['nome']; ?></h2>
                    <?php if (!empty($row['tamanho'])): ?>
                        <p>Tamanho: <strong><?php echo htmlspecialchars($row['tamanho']); ?></strong></p>
                    <?php endif; ?>
                    <p>Quantidade: <strong><?php echo $row['quantidade']; ?></strong></p>
                    <p>Preço: <strong>€<?php echo number_format($row['preco'], 2, ',', '.'); ?></strong></p>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="removecart.php?id=<?php echo $row['id']; ?>" class="remover">Remover</a>
                    <?php else: ?>
                        <a href="removecart.php?pid=<?php echo $row['id']; ?>" class="remover">Remover</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="total-carrinho">
            <strong>Total: €<?php echo number_format($total, 2, ',', '.'); ?></strong>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="checkout.php" method="POST">
                <button type="submit" class="btn-voltar">Finish Purchase</button>
            </form>
        <?php else: ?>
            <p style="text-align:center; margin-top:20px; color:#555;">
                <strong>Log in to complete your purchase.</strong><br>
                <a href="login/login.php" class="btn-voltar">Login</a>
            </p>
        <?php endif; ?>
    <?php else: ?>
        <p class="vazio">The cart is empty</p>
    <?php endif; ?>
</div>

<a href="home.php" class="btn-voltar">&larr;GO back to shopping</a>
</body>
</html>
