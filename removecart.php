<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Utilizador não logado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'];

    // Remove o item do carrinho
    $stmt = $pdo->prepare("DELETE FROM carrinho WHERE id = ? AND user_id = ?");
    $stmt->execute([$item_id, $_SESSION['user_id']]);

    echo "Item removido do carrinho!";
} else {
    die("Método de requisição inválido.");
}
?>