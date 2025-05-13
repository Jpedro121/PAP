<?php
require_once 'includes/db.php';

// Consulta para marcas distintas
$sql = "SELECT DISTINCT marca FROM produtos WHERE marca IS NOT NULL";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include('head.html'); ?>
    <title><?= $lang['our_brands'] ?>-Sk8Nation</title>
    <style>
        .marca-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            padding: 30px;
            justify-content: center;
        }
        .marca-card {
            border: 1px solid #eee;
            padding: 20px;
            width: 220px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            border-radius: 8px;
            background: white;
        }
        .marca-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .marca-logo {
            max-width: 100%;
            height: 120px;
            object-fit: contain;
            margin-bottom: 15px;
        }
        .marca-nome {
            font-size: 1.1em;
            margin: 10px 0;
            color: #333;
        }
        .marca-desc {
            color: #666;
            font-size: 0.9em;
        }
        h1 {
            text-align: center;
            margin-top: 30px;
            color: #333;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>
    
    <h1>Nossas Marcas</h1>
    
    <div class="marca-container">
        <?php while($row = $result->fetch_assoc()): 
            $marca = $row['marca'];
            $logo = isset($logos_marcas[$marca]) ? "static/images/marcas/" . $logos_marcas[$marca] : 'static/images/marcas/default-brand.png';
        ?>
            <div class="marca-card" onclick="window.location.href='produtos_por_marca.php?marca=<?= urlencode($marca) ?>'">
                <img src="<?= $logo ?>" alt="<?= htmlspecialchars($marca) ?>" class="marca-logo">
                <div class="marca-nome"><?= htmlspecialchars($marca) ?></div>
                <div class="marca-desc"><?= $lang['Click_here'] ?></div>
            </div>
        <?php endwhile; ?>
    </div>

    <?php $conn->close(); ?>
</body>
</html>