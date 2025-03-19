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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decks - Skateshop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        header {
            background-color: #222;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .deck-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 40px;
            max-width: 1200px;
            margin: auto;
        }
        .deck-item {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 10px;
            background-color: #fff;
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }
        .deck-item:hover {
            transform: scale(1.05);
        }
        .deck-item img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .deck-item h2 {
            font-size: 18px;
            margin: 10px 0;
        }
        .deck-item p {
            margin: 5px 0;
            color: #555;
        }
        .deck-item button {
            background-color: #ff4500;
            color: #fff;
            border: none;
            padding: 12px;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .deck-item button:hover {
            background-color: #e03e00;
        }
    </style>
</head>
<body>
<?php include('header.php'); ?>
    <section class="deck-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="deck-item">
                    <a href="produto.php?id=<?php echo $row['id']; ?>">
                        <img src="images/<?php echo htmlspecialchars($row['imagem']); ?>" alt="<?php echo htmlspecialchars($row['nome']); ?>">
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
