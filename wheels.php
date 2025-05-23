<?php
require 'db.php'; // Arquivo com as credenciais do banco de dados

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Seleciona todos os produtos que estão na tabela rodas
$sql = "SELECT p.id, p.nome, p.descricao, p.preco, p.imagem, d.tamanho, d.marca 
        FROM produtos p 
        INNER JOIN rodas d ON p.id = d.produto_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<?php include('head.html'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rodas</title>
</head>
<body>
<?php include('header.php'); ?>

    <h1>Wheels</h1>
    <div class="deck-container">
        <?php
        if ($result && $result->num_rows > 0) {
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
            echo "<p>Nenhum produto encontrado.</p>";
        }
        ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>
