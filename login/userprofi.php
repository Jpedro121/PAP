<?php
session_start();
if (!isset($_SESSION["username"]) || !isset($_SESSION["user_id"])) {
    header("Location: login.php"); 
    exit();
}
require '../db.php';
$user_id = $_SESSION["user_id"];

$sql = "SELECT username, email, morada FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $dados = $result->fetch_assoc();
    $username = $dados['username'];
    $email = $dados['email'];
    $morada = $dados['morada'];

} else {
    $username = "Desconhecido";
    $email = "Não disponível";
    $morada = "";
    $is_admin = 0;

}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <title>Perfil</title>
    <?php include('../head.html'); ?>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        .perfil-container {
            background-color: #fff;
            max-width: 900px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid #ccc;
            margin-bottom: 20px;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            border-bottom: 3px solid transparent;
            background-color: transparent;
            font-weight: bold;
        }

        .tab.active {
            border-bottom: 3px solid #007bff;
            color: #007bff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        form {
            margin-top: 15px;
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }

        input[type="text"], input[type="password"], textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .btn {
            margin-top: 15px;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-group {
            text-align: center;
            margin-top: 30px;
        }

        .order-item {
            background-color: #f9f9f9;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 6px;
        }
                .encomenda {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 15px;
        }

        .info-principal {
            width: 200px;
            margin-right: 20px;
            background: #eee;
            padding: 10px;
            border-radius: 8px;
        }

        .detalhes {
            background: #f9f9f9;
            padding: 10px;
            border-radius: 8px;
            flex: 1;
            display: none;
        }

        .produto {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .produto img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            margin-right: 10px;
            border-radius: 5px;
        }

        .btn-ver {
            margin-top: 10px;
            padding: 6px 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-ver:hover {
            background-color: #218838;
        }
    </style>
</head>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            this.classList.add('active');
            
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });
});
</script>
<body>
<?php include('../header.php'); ?>
<div class="perfil-container">
    <h2>Olá, <?php echo htmlspecialchars($username); ?></h2>

    <div class="tabs">
    <button class="tab active" data-tab="info">Informações</button>
    <button class="tab" data-tab="seguranca">Segurança</button>
    <button class="tab" data-tab="encomendas">Encomendas</button>
</div>

    <div id="info" class="tab-content active">
        <h3>Editar Nome de Utilizador</h3>
        <form action="update_profile.php" method="post">
            <label for="novo_username">Novo Nome:</label>
            <input type="text" name="novo_username" id="novo_username" value="<?php echo htmlspecialchars($username); ?>" required>
            <button type="submit" class="btn">Atualizar Nome</button>
        </form>

        <h3>Email</h3>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>

        <h3>Morada</h3>
        <form action="update_address.php" method="post">
            <label for="morada">Morada Atual:</label>
            <textarea name="morada" id="morada" rows="3" required><?php echo htmlspecialchars($morada); ?></textarea>
            <button type="submit" class="btn">Atualizar Morada</button>
        </form>
    </div>

    <div id="seguranca" class="tab-content">
        <h3>Alterar Palavra-passe</h3>
        <form action="update_password.php" method="post">
            <label for="password_atual">Palavra-passe Atual:</label>
            <input type="password" name="password_atual" id="password_atual" required>
            <label for="nova_password">Nova Palavra-passe:</label>
            <input type="password" name="nova_password" id="nova_password" required>
            <button type="submit" class="btn">Alterar Palavra-passe</button>
        </form>
    </div>

<div id="encomendas" class="tab-content">
    <h3>As suas encomendas</h3>
    <?php
    try {
        $query = "SELECT id, data_encomenda, total FROM encomendas WHERE user_id = ? ORDER BY data_encomenda DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            while ($encomenda = $res->fetch_assoc()) {
                $id = $encomenda['id'];
                echo "<div class='encomenda'>";
                echo "<div class='info-principal'>";
                echo "<strong>ENC-{$id}</strong><br>";
                echo "Data: " . htmlspecialchars($encomenda['data_encomenda']) . "<br>";
                echo "Total: " . htmlspecialchars($encomenda['total']) . "€<br>";
                echo "<button class='btn-ver' onclick='mostrarDetalhes($id)'>Ver detalhes</button>";
                echo "</div>";

                // detalhes escondidos inicialmente
                echo "<div class='detalhes' id='detalhes_$id'>";

                $query_produtos = "SELECT p.nome, p.imagem, ep.quantidade 
                                   FROM encomenda_produtos ep 
                                   JOIN produtos p ON ep.produto_id = p.id 
                                   WHERE ep.encomenda_id = ?";
                $stmtProdutos = $conn->prepare($query_produtos);
                $stmtProdutos->bind_param("i", $id);
                $stmtProdutos->execute();
                $produtos = $stmtProdutos->get_result();

                if ($produtos->num_rows > 0) {
                    while ($produto = $produtos->fetch_assoc()) {
                        echo "<div class='produto'>";
                        echo "<img src='/PAP/static/images/" . htmlspecialchars($produto['imagem']) . "' alt='Produto'>";
                        echo "<div><strong>" . htmlspecialchars($produto['nome']) . "</strong><br>Quantidade: " . htmlspecialchars($produto['quantidade']) . "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>Sem produtos nesta encomenda.</p>";
                }

                echo "</div>"; // .detalhes
                echo "</div>"; // .encomenda
            }
        } else {
            echo "<p>Não tens encomendas ainda.</p>";
        }
    } catch (mysqli_sql_exception $e) {
        echo "<p>Erro ao carregar encomendas: " . $e->getMessage() . "</p>";
    }
    ?>

    <script>
        function mostrarDetalhes(id) {
            const painel = document.getElementById("detalhes_" + id);
            if (painel.style.display === "none" || painel.style.display === "") {
                painel.style.display = "block";
            } else {
                painel.style.display = "none";
            }
        }
    </script>
</div>
    <div class="btn-group">
        <a href="../home.php" class="btn">Home</a>
        <a href="logout.php" class="btn">Sair</a>
    </div>
    
</div>

<script>
    function openTab(evt, tabId) {
        // Esconde todos os conteúdos das tabs
        const tabContents = document.getElementsByClassName("tab-content");
        for (let i = 0; i < tabContents.length; i++) {
            tabContents[i].classList.remove("active");
        }

        // Remove a classe active de todas as tabs
        const tabs = document.getElementsByClassName("tab");
        for (let i = 0; i < tabs.length; i++) {
            tabs[i].classList.remove("active");
        }

        // Mostra a tab atual e adiciona a classe active ao botão
        document.getElementById(tabId).classList.add("active");
        evt.currentTarget.classList.add("active");
    }
</script>
</body>
</html>