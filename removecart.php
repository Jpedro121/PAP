<?php
session_start();
$conn = new mysqli("localhost", "root", "", "skateshop");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM carrinho WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

$conn->close();
header("Location: cart.php");
exit();
?>
