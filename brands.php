<?php
require_once 'db.php';

$sql = "SELECT DISTINCT marca, logo_marca FROM produtos WHERE marca IS NOT NULL";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include('head.html'); ?>
    <title>Brands</title>
    <style>
        .marca-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .marca-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: transform 0.3s;
        }
        .marca-card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .marca-logo {
            max-width: 100%;
            height: 80px;
            object-fit: contain;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>
    
    <h1 style="text-align:center; margin:20px 0;">Our Brands</h1>
    
    <div class="marca-container">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="marca-card" 
                 onclick="window.location.href='produtos_por_marca.php?marca=<?= urlencode($row['marca']) ?>'">
                <?php if(!empty($row['logo_marca'])): ?>
                    <img src="static/images/marcas/<?= htmlspecialchars($row['logo_marca']) ?>" 
                         alt="<?= htmlspecialchars($row['marca']) ?>" 
                         class="marca-logo">
                <?php endif; ?>
                <h3><?= htmlspecialchars($row['marca']) ?></h3>
            </div>
        <?php endwhile; ?>
    </div>

    <?php $conn->close(); ?>
</body>
</html>