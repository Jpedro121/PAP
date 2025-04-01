<?php
session_start();
$conn = new mysqli("localhost", "root", "", "skateshop");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id']; 
$sql = "SELECT c.id, p.nome, p.imagem, c.quantidade, c.preco 
        FROM carrinho c 
        JOIN produtos p ON c.produto_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<?php include('head.html'); ?>
    <title>Meu Carrinho</title>
</head>
<body>
    <?php include('header.php'); ?>
    <h1>Meu Carrinho</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Imagem</th>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Ação</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><img src="static/images/<?php echo $row['imagem']; ?>" width="50"></td>
                    <td><?php echo $row['nome']; ?></td>
                    <td><?php echo $row['quantidade']; ?></td>
                    <td>€<?php echo number_format($row['preco'], 2, ',', '.'); ?></td>
                    <td>
                        <a href="removecart.php?id=<?php echo $row['id']; ?>">Remover</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Seu carrinho está vazio.</p>
    <?php endif; ?>

    <a href="home.php" class="btn">Continuar Comprando</a>
</body>
</html>
