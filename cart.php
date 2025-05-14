<?php
session_start();
$conn = new mysqli("localhost", "root", "", "skateshop");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}


$total = 0;
$produtos = [];

if (isset($_SESSION['user_id'])) {
    // Carrinho guardado na base de dados (logado)
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT c.id, p.nome, p.imagem, c.quantidade, c.preco 
            FROM carrinho c 
            JOIN produtos p ON c.produto_id = p.id 
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
        $total += $row['preco'] * $row['quantidade'];
    }
} else {
    // Carrinho em sessão (não logado)
    if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
        foreach ($_SESSION['carrinho'] as $item) {
            $produto_id = $item['produto_id'];
            $quantidade = $item['quantidade'];
            $query = $conn->prepare("SELECT nome, imagem, preco FROM produtos WHERE id = ?");
            $query->bind_param("i", $produto_id);
            $query->execute();
            $res = $query->get_result();
            if ($res->num_rows > 0) {
                $produto = $res->fetch_assoc();
                $produto['quantidade'] = $quantidade;
                $produto['id'] = $produto_id;
                $produtos[] = $produto;
                $total += $produto['preco'] * $quantidade;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title><?= $lang['My_Cart'] ?? 'Carrinho' ?></title>
    <?php include('head.html'); ?>
<style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .titulo-carrinho {
            text-align: center;
            padding: 30px 0 10px;
            font-size: 2em;
            color: #222;
        }

        .carrinho-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .item-carrinho {
            display: flex;
            background-color: #fff;
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .item-carrinho img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-right: 1px solid #ddd;
        }

        .item-carrinho .info {
            padding: 15px;
            flex-grow: 1;
        }

        .item-carrinho h2 {
            margin: 0 0 10px;
            font-size: 1.3em;
            color: #333;
        }

        .item-carrinho p {
            margin: 5px 0;
            color: #555;
        }

        .remover {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 12px;
            background-color: #ff4d4d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .remover:hover {
            background-color: #e60000;
        }

        .btn-voltar {
            display: block;
            text-align: center;
            margin: 30px auto;
            width: fit-content;
            padding: 10px 20px;
            background-color: #222;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-voltar:hover {
            background-color: #444;
        }

        .vazio {
            text-align: center;
            font-size: 1.2em;
            margin: 40px 0;
            color: #666;
        }

        .total-carrinho {
            text-align: center;
            font-size: 1.5em;
            margin-top: 20px;
            color: #111;
        }
    </style>
</head>
<body>

<?php include('header.php'); ?>

<h1 class="titulo-carrinho"><?= $lang['My_Cart'] ?? 'Carrinho' ?></h1>

<div class="carrinho-container">
    <?php if (!empty($produtos)): ?>
        <?php foreach ($produtos as $row): ?>
            <div class="item-carrinho">
                <img src="static/images/<?php echo $row['imagem']; ?>" alt="<?php echo $row['nome']; ?>">
                <div class="info">
                    <h2><?php echo $row['nome']; ?></h2>
                    <p><?= $lang['Quantity'] ?? 'Quantidade:' ?> <strong><?php echo $row['quantidade']; ?></strong></p>
                    <p><?= $lang['Price'] ?? 'Preço:' ?> <strong>€<?php echo number_format($row['preco'], 2, ',', '.'); ?></strong></p>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="removecart.php?id=<?php echo $row['id']; ?>" class="remover"><?= $lang['Remove'] ?? 'Remover' ?></a>
                    <?php else: ?>
                        <a href="removecart.php?pid=<?php echo $row['id']; ?>" class="remover"><?= $lang['Remove'] ?? 'Remover' ?></a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="total-carrinho">
            <strong>Total: €<?php echo number_format($total, 2, ',', '.'); ?></strong>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="checkout.php" method="POST">
                <button type="submit" class="button is-success">Finalizar Compra</button>
            </form>
        <?php else: ?>
            <p style="text-align:center; margin-top:20px; color:#555;">
                <strong>Faça login para finalizar a compra.</strong><br>
                <a href="login/login.php" class="btn-voltar">Login</a>
            </p>
        <?php endif; ?>
    <?php else: ?>
        <p class="vazio"><?= $lang['empty_cart'] ?? 'O carrinho está vazio.' ?></p>
    <?php endif; ?>
</div>

<a href="home.php" class="btn-voltar">← <?= $lang['Back_to_Shopping'] ?? 'Voltar às Compras' ?></a>

</body>
</html>
