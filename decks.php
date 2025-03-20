<?php
require 'db.php'; // Arquivo de conexão com o banco de dados

// Consulta para buscar os decks
$sql = "SELECT p.id, p.nome, p.descricao, p.preco, p.imagem, d.tamanho, d.marca 
        FROM produtos p 
        JOIN decks d ON p.id = d.produto_id 
        WHERE p.categoria_id = 1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <link rel="stylesheet" href="static/estilos.css">
    <title>Decks - Skateshop</title>
    <?php include('head.html'); ?>
</head>

<body>
<?php include('header.php'); ?>
    <section class="deck-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="deck-item">
                    <a href="produto.php?id=<?php echo $row['id']; ?>">
                        <img src="static/images/<?php echo htmlspecialchars($row['imagem']); ?>" alt="<?php echo htmlspecialchars($row['nome']); ?>">
                        <h2><?php echo htmlspecialchars($row['nome']); ?></h2>
                    </a>
                    <p><strong>Marca:</strong> <?php echo htmlspecialchars($row['marca']); ?></p>
                    <p><strong>Tamanho:</strong> <?php echo htmlspecialchars($row['tamanho']); ?>"</p>
                    <p><?php echo htmlspecialchars($row['descricao']); ?></p>
                    <p><strong>Preço:</strong> €<?php echo number_format($row['preco'], 2, ',', '.'); ?></p>
                    <button>Adicionar ao Carrinho</button>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center; font-size: 18px; color: #777;">Nenhum deck encontrado.</p>
        <?php endif; ?>
    </section>
</body>
</html>
