<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Utilizador não logado.");
}

$user_id = $_SESSION['user_id'];

// Busca os itens do carrinho
$stmt = $pdo->prepare("SELECT carrinho.*, produtos.nome, produtos.imagem FROM carrinho JOIN produtos ON carrinho.produto_id = produtos.id WHERE user_id = ?");
$stmt->execute([$user_id]);
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($itens)) {
    echo "Carrinho vazio.";
} else {
    echo "<h2>Carrinho de Compras</h2>";
    echo "<ul>";
    $total = 0;
    foreach ($itens as $item) {
        echo "<li>";
        echo "<img src='{$item['imagem']}' alt='{$item['nome']}' width='100'>";
        echo "{$item['nome']} - Quantidade: {$item['quantidade']} - Preço: € " . number_format($item['preco'], 2, ',', '.');
        echo "</li>";
        $total += $item['preco'] * $item['quantidade'];
    }
    echo "</ul>";
    echo "<p>Total: € " . number_format($total, 2, ',', '.') . "</p>";
}
?>