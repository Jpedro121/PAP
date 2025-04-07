<?php
session_start();
$conn = new mysqli("localhost", "root", "", "skateshop");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login\login.php"); // redireciona se não estiver logado
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];
    $user_id = $_SESSION['user_id'];
    
    // Verifica se já existe esse produto no carrinho
    $sql = "SELECT * FROM carrinho WHERE user_id = ? AND produto_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $produto_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $sql = "UPDATE carrinho SET quantidade = quantidade + ? WHERE user_id = ? AND produto_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $quantidade, $user_id, $produto_id);
        $stmt->execute();
    } else {
        $sql = "INSERT INTO carrinho (user_id, produto_id, quantidade, preco) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiid", $user_id, $produto_id, $quantidade, $preco);
        $stmt->execute();
    }

    $conn->close();
    header("Location: cart.php");
    exit();
}

?>
