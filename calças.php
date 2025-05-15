<?php
require 'db.php';

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$sql = "SELECT p.id, p.nome, p.descricao, p.preco, p.imagem, pt.tamanho, pt.marca, pt.estoque
        FROM produtos p
        INNER JOIN pants pt ON p.id = pt.produto_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <?php include('head.html'); ?>
    <title>Calças</title>
</head>
<body>
    <?php include('header.php'); ?>

    <h1>Calças</h1>
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
            <p>Nenhum produto encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>
