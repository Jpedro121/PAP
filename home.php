<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('head.html'); ?>
    <title>Skateshop</title>
</head>
<body>
<?php include('header.php'); ?>

<main>
    <section class="hero" style="background-image: url('static/images/header-desktop.webp')">
        <h1>Discover the World of Skateboarding</h1>
        <p>Gear up with the latest apparel, footwear, and skateboards.</p>
    </section>

    <section class="brands">
        <h2>Nossas Marcas</h2>
        <div class="brand-logos">
            <div class="brand-logo"><img src="static/images/butter.png" alt="butter"></div>
            <div class="brand-logo"><img src="static/images/carhart.png" alt="carhartt"></div>
            <div class="brand-logo"><img src="static/images/dime.png" alt="dime"></div>
            <div class="brand-logo"><img src="static/images/nikesb.png" alt="nikesb"></div>
            <div class="brand-logo"><img src="static/images/palace.png" alt="palace"></div>
            <div class="brand-logo"><img src="static/images/polar.png" alt="polar"></div>
            <div class="brand-logo"><img src="static/images/vans.png" alt="Vans"></div>
            <div class="brand-logo"><img src="static/images/Converse.png" alt="Converse"></div>
            <div class="brand-logo"><img src="static/images/fuckingawesome.png" alt="fuckingawesome"></div>

            <!--2x para infinito-->
            <div class="brand-logo"><img src="static/images/butter.png" alt="butter"></div>
            <div class="brand-logo"><img src="static/images/carhart.png" alt="carhartt"></div>
            <div class="brand-logo"><img src="static/images/dime.png" alt="dime"></div>
            <div class="brand-logo"><img src="static/images/nikesb.png" alt="nikesb"></div>
            <div class="brand-logo"><img src="static/images/palace.png" alt="palace"></div>
            <div class="brand-logo"><img src="static/images/polar.png" alt="polar"></div>
            <div class="brand-logo"><img src="static/images/vans.png" alt="Vans"></div>
            <div class="brand-logo"><img src="static/images/Converse.png" alt="Converse"></div>
            <div class="brand-logo"><img src="static/images/fuckingawesome.png" alt="fuckingawesome"></div>


        </div>
    </section>

    <section>
    <h1>Novidades</h1>
    <div class="produto-grid">
        <?php
        $conn = new mysqli("localhost", "root", "", "skateshop");

        if ($conn->connect_error) {
            die("Erro de conexão: " . $conn->connect_error);
        }

        $sql = "SELECT p.id, p.nome, p.imagem, p.preco, p.categoria_id, 
               COALESCE(d.tamanho, t.tamanho, r.tamanho) AS tamanho
        FROM produtos p
        LEFT JOIN decks d ON p.id = d.produto_id
        LEFT JOIN trucks t ON p.id = t.produto_id
        LEFT JOIN rodas r ON p.id = r.produto_id
        ORDER BY p.id DESC
        LIMIT 4";


        $result = $conn->query($sql);

        if (!$result) {
            echo "Erro na consulta SQL: " . $conn->error;
            exit;
        }

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
            echo "Nenhum produto encontrado.";
        }

        $conn->close();
        ?>
    </div>
</section>
</main>

<footer>
    <p>2025 Skateshop. Todos os direitos reservados.</p>
</footer>

</body>
</html>
