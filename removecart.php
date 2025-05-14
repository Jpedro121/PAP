<?php
session_start();
$conn = new mysqli("localhost", "root", "", "skateshop");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Se o utilizador estiver logado, remover do BD
if (isset($_SESSION['user_id']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM carrinho WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
// Se não estiver logado, remover do carrinho de sessão
elseif (isset($_GET['pid']) && isset($_SESSION['carrinho'])) {
    $produto_id = $_GET['pid'];
    foreach ($_SESSION['carrinho'] as $index => $item) {
        if ($item['produto_id'] == $produto_id) {
            unset($_SESSION['carrinho'][$index]);
            // Reorganiza os índices do array para evitar problemas
            $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
            break;
        }
    }
}

$conn->close();
header("Location: cart.php");
exit();
?>
