<?php
session_start();
$conn = new mysqli("localhost", "root", "", "skateshop");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$produto_id = $_POST['produto_id'];
$quantidade = $_POST['quantidade'];
$preco = $_POST['preco'];
$tamanho = $_POST['tamanho'] ?? null;

if (isset($_SESSION['user_id'])) {
    // Utilizador autenticado — guardar no banco de dados
    $user_id = $_SESSION['user_id'];

    // Verifica se já existe esse produto e tamanho no carrinho
    $check = $conn->prepare("SELECT id FROM carrinho WHERE user_id = ? AND produto_id = ? AND tamanho = ?");
    $check->bind_param("iis", $user_id, $produto_id, $tamanho);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Atualiza quantidade
        $sql = "UPDATE carrinho SET quantidade = quantidade + ? WHERE user_id = ? AND produto_id = ? AND tamanho = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $quantidade, $user_id, $produto_id, $tamanho);
    } else {
        // Inserir novo
        $sql = "INSERT INTO carrinho (user_id, produto_id, quantidade, preco, tamanho) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiids", $user_id, $produto_id, $quantidade, $preco, $tamanho);
    }

    $stmt->execute();
} else {
    // Utilizador não autenticado — guardar em sessão
    $item = [
        'produto_id' => $produto_id,
        'quantidade' => $quantidade,
        'preco' => $preco,
        'tamanho' => $tamanho
    ];

    $_SESSION['carrinho'][] = $item;
}

header("Location: cart.php");
exit;
