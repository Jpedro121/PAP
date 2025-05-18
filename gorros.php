<?php
require 'db.php';

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Query corrigida para usar a estrutura real do seu banco de dados
$query = "SELECT p.id, p.nome, p.preco, p.imagem, g.tamanho, g.cor
          FROM produtos p
          JOIN gorros g ON p.id = g.produto_id
          WHERE p.categoria = 'gorros'";

$result = $conn->query($query);

if ($result === false) {
    die("Erro na consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <?php include('head.html'); ?>
    <title>Gorros</title>
</head>
<body>
    <?php include('header.php'); ?>

    <h1>Gorros</h1>
    <div class="deck-container">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="produto-card">
                    <a href="produto.php?id=<?= $row['id'] ?>">
                        <img src="static/images/<?= $row['imagem'] ?>" alt="<?= htmlspecialchars($row['nome']) ?>">
                        <h3><?= htmlspecialchars($row['nome']) ?></h3>
                        <?php if(isset($row['tamanho'])): ?>
                            <p>Tamanho: <?= htmlspecialchars($row['tamanho']) ?></p>
                        <?php endif; ?>
                        <?php if(isset($row['cor'])): ?>
                            <p>Cor: <?= htmlspecialchars($row['cor']) ?></p>
                        <?php endif; ?>
                        <p>€<?= number_format($row['preco'], 2, ',', '.') ?></p>
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