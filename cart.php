<?php
session_start();
$conn = new mysqli("localhost", "root", "", "skateshop");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT c.id, p.nome, p.imagem, c.quantidade, c.preco 
        FROM carrinho c 
        JOIN produtos p ON c.produto_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Meu Carrinho</title>
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
    </style>
</head>
<body>

<?php include('header.php'); ?>

<h1 class="titulo-carrinho">Meu Carrinho</h1>

<div class="carrinho-container">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="item-carrinho">
                <img src="static/images/<?php echo $row['imagem']; ?>" alt="<?php echo $row['nome']; ?>">
                <div class="info">
                    <h2><?php echo $row['nome']; ?></h2>
                    <p>Quantidade: <strong><?php echo $row['quantidade']; ?></strong></p>
                    <p>Preço: <strong>€<?php echo number_format($row['preco'], 2, ',', '.'); ?></strong></p>
                    <a href="removecart.php?id=<?php echo $row['id']; ?>" class="remover">Remover</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="vazio">Seu carrinho está vazio.</p>
    <?php endif; ?>
</div>

<a href="home.php" class="btn-voltar">← Continuar Comprando</a>

</body>
</html>
