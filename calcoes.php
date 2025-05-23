<?php
require 'db.php';

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$sql = "SELECT p.id, p.nome, p.descricao, p.preco, p.imagem, s.tamanho, s.marca, s.estoque
        FROM produtos p
        INNER JOIN shorts s ON p.id = s.produto_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <?php include('head.html'); ?>
    <title>Shorts</title>

</head> 
<body> <?php include('header.php'); ?>
<h1>Shorts</h1>
<div class="deck-container">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="produto-card">
                <a href="produto.php?id=<?= $row["id"] ?>">
                    <img src="static/images/<?= $row["imagem"] ?>" alt="<?= htmlspecialchars($row["nome"]) ?>">
                    <h3><?= htmlspecialchars($row["nome"]) ?> - <?= htmlspecialchars($row["tamanho"]) ?> - <?= htmlspecialchars($row["marca"]) ?></h3>
                    <p>€<?= number_format($row["preco"], 2, ',', '.') ?></p>
                </a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No products found</p>
    <?php endif; ?>
</div>
</body> 
</html> 
<?php $conn->close(); ?>
