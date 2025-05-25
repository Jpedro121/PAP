<?php
require 'db.php'; // Arquivo com as credenciais do banco de dados

$conn = new mysqli("localhost", "root", "", "skateshop");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Categorias relacionadas com skateboard
$sql = "SELECT id, nome, descricao, preco, imagem, tamanho, marca FROM produtos 
        WHERE categoria_id IN (1, 2, 3, 4)";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<?php include('head.html'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skateboard - Sk8Nation</title>
</head>
<body>
<?php include('header.php'); ?>

    <h1>Skateboard</h1>
    <div class="deck-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="produto-card">';
                echo '<a href="produto.php?id=' . $row["id"] . '">';
                echo '<img src="static/images/' . $row["imagem"] . '" alt="' . $row["nome"] . '">';
                echo '<h3>' . $row["nome"] . ' - ' . $row["tamanho"] . '</h3>';
                echo '<p>€' . number_format($row["preco"], 2, ',', '.') . '</p>';
                echo '</a>';
                echo '</div>';
            }
        } else {
            echo "Nenhum produto de skateboard encontrado.";
        }
        ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>
<?php