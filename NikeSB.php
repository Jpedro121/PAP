<?php
require 'db.php';

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Buscar produtos da marca NikeSB apenas da categoria 9 (sapatos)
$sql = "SELECT * FROM produtos WHERE marca = 'NikeSB' AND categoria_id = 9";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <?php include('head.html'); ?>
    <title>NikeSB - Shoes</title>
</head>
<body>
    <?php include('header.php'); ?>

    <h1>NikeSB -Shoes</h1>
    <div class="deck-container">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="produto-card">
                    <a href="produto.php?id=<?= $row['id'] ?>">
                        <img src="static/images/<?= $row['imagem'] ?>" alt="<?= htmlspecialchars($row['nome']) ?>">
                        <h3><?= htmlspecialchars($row['nome']) ?></h3>
                        <p>€<?= number_format($row['preco'], 2, ',', '.') ?></p>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products Found</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>
