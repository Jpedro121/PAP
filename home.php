<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('head.html'); ?>
    <title>Sk8Nation</title>
</head>
<body>
<?php include('header.php'); ?>

<main>
    <section class="hero" style="background-image: url('static/images/header-desktop.webp')">
        <h1><?= $lang['Discover_World'] ?></h1>
        <p><?= $lang['gear_up'] ?></p>
    </section>

  <section class="brands">
    <h2 class="NossasMarcas"><?= $lang['our_brands'] ?></h2>
    <div class="brand-logos">
        <?php
        $brands = [
            "Butter" => "butter.png",
            "Carhartt" => "carhart.png",
            "Dime" => "dime.png",
            "NikeSB" => "nikesb.png",
            "Palace" => "palace.png",
            "Polar Skate Co." => "polar.png",
            "Vans" => "vans.png",
            "Fucking Awesome" => "fuckingawesome.png",
            "Ace Trucks MFG" => "ace.png",
        ];

        // Para duplicar (efeito de rolagem infinita, por exemplo)
        for ($i = 0; $i < 2; $i++) {
            foreach ($brands as $brandName => $imageFile) {
                $encodedBrand = urlencode($brandName);
                echo '<div class="brand-logo">';
                echo "<a href=\"/PAP/produtos_por_marca.php?marca=$encodedBrand\">";
                echo "<img src=\"static/images/marcas/$imageFile\" alt=\"$brandName\">";
                echo '</a>';
                echo '</div>';
            }
        }
        ?>
    </div>
</section>


    <section>
    <h1 class="Novidades"><?= $lang['new_arrivals'] ?></h1>
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
    <p><?= $lang['footer'] ?></p>
</footer>

</body>
</html>
