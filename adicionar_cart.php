<?php
session_start();
require 'db.php'; 

if (!isset($_SESSION['usuario_id'])) {
    die("Utilizador não logado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];

    $stmt = $pdo->prepare("SELECT preco FROM produtos WHERE id = ?");
    $stmt->execute([$produto_id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        die("Produto não encontrado.");
    }

    $preco = $produto['preco'];

    // Insere o item no 
    $stmt = $pdo->prepare("INSERT INTO carrinho (user_id, produto_id, quantidade, preco) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $produto_id, $quantidade, $preco]);

    echo "Item adicionado ao carrinho!";
} else {
    die("Método de requisição inválido.");
}
?>