<?php
require 'db.php';

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Buscar produtos da marca NikeSB apenas da categoria 9 (sapatos)
$sql = "SELECT * FROM produtos WHERE marca = 'NikeSB' AND categoria_id = 9";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <?php include('head.html'); ?>
    <title>NikeSB - Sapatos</title>
</head>
<body>
    <?php include('header.php'); ?>

    <h1>NikeSB - Sapatos</h1>
    <div class="deck-container">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="produto-card">
                    <a href="produto.php?id=<?= $row['id'] ?>">
                        <img src="static/images/<?= $row['imagem'] ?>" alt="<?= htmlspecialchars($row['nome']) ?>">
                        <h3><?= htmlspecialchars($row['nome']) ?></h3>
                        <p>€<?= number_format($row['preco'], 2, ',', '.') ?></p>
                    </a>

                    <!-- Botões de tamanhos -->
                    <div class="tamanhos">
                        <p>Tamanhos disponíveis:</p>
                        <?php
                        // Buscar tamanhos disponíveis para este produto
                        $tamanhosDisponiveis = [];
                        $stmt = $conn->prepare("SELECT tamanho FROM tamanhos_produto WHERE produto_id = ? AND stock > 0");
                        $stmt->bind_param("i", $row['id']);
                        $stmt->execute();
                        $resTamanhos = $stmt->get_result();
                        while ($tam = $resTamanhos->fetch_assoc()) {
                            $tamanhosDisponiveis[] = $tam['tamanho'];
                        }

                        // Lista de todos os tamanhos possíveis (para sapatos)
                        $todosTamanhos = range(38, 47);

                        foreach ($todosTamanhos as $tam) {
                            $disponivel = in_array($tam, $tamanhosDisponiveis);
                            echo '<button class="botao-tamanho ' . ($disponivel ? '' : 'indisponivel') . '" ' . ($disponivel ? '' : 'disabled') . '>' . $tam . '</button>';
                        }
                        ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Nenhum produto encontrado.</p>
        <?php endif; ?>
    </div>

    <style>
        .tamanhos {
            margin-top: 10px;
        }

        .botao-tamanho {
            margin: 2px;
            padding: 5px 10px;
            border: 1px solid #ccc;
            background-color: white;
            cursor: pointer;
        }

        .botao-tamanho:hover {
            background-color: #eee;
        }

        .botao-tamanho.indisponivel {
            background-color: #f2f2f2;
            color: #aaa;
            border: 1px solid #ddd;
            cursor: not-allowed;
        }
    </style>
</body>
</html>

<?php $conn->close(); ?>
