<?php
require_once 'db.php';

$marca = $conn->real_escape_string($_GET['marca'] ?? ''); // Segurança básica

$sql = "SELECT * FROM produtos WHERE marca = '$marca'";
$result = $conn->query($sql);

$produtos = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include('head.html'); ?>
    <title><?= htmlspecialchars($marca) ?> - SkateShop</title>
    <style>
        .produto-container { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 20px; 
            padding: 20px; 
            justify-content: center;
        }
        .produto-card { 
            border: 1px solid #ddd; 
            padding: 15px; 
            width: 200px;
            text-align: center;
            transition: transform 0.3s;
        }
        .produto-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .produto-imagem { 
            max-width: 100%; 
            height: 180px;
            object-fit: contain;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        .back-link:hover {
            color: #0066cc;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>
    
    <div class="container">
        <h1><?= htmlspecialchars($marca) ?></h1>

        <div class="produto-container">
            <?php if (empty($produtos)): ?>
                <p><?= $lang['No_products_found'] ?>.</p>
            <?php else: ?>
                <?php foreach ($produtos as $produto): ?>
                    <div class="produto-card">
                        <?php if (!empty($produto['imagem'])): ?>
                            <img src="static/images/<?= htmlspecialchars($produto['imagem']) ?>" 
                                 alt="<?= htmlspecialchars($produto['nome']) ?>" 
                                 class="produto-imagem">
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                        <p><?= $lang['price'] ?> €<?= number_format($produto['preco'], 2) ?></p>
                        <?php if (!empty($produto['tamanho'])): ?>
                            <p><?= $lang['size'] ?><?= htmlspecialchars($produto['tamanho']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php $conn->close(); ?>
</body>
</html>