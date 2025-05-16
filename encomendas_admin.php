<?php
require 'db.php';

// Consulta para buscar encomendas com o nome do utilizador (ajustado)
$sql = "
    SELECT e.*, u.username 
    FROM encomendas e
    JOIN users u ON e.user_id = u.id
    ORDER BY u.username, e.data_encomenda DESC
";

$result = $conn->query($sql);

if (!$result) {
    die("Erro na consulta: " . $conn->error);
}

$encomendas_por_utilizador = [];
while ($row = $result->fetch_assoc()) {
    $encomendas_por_utilizador[$row['user_id']]['nome'] = $row['username'];
    $encomendas_por_utilizador[$row['user_id']]['encomendas'][] = $row;
}
?>

<style>
/* Container geral */
div {
  max-width: 700px;
  margin: 30px auto;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #333;
}

/* Cada utilizador */
div > div {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  margin-bottom: 15px;
  padding: 15px 20px;
  transition: box-shadow 0.3s ease;
}
div > div:hover {
  box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

/* Caret */
.caret {
  cursor: pointer;
  user-select: none;
  display: inline-block;
  margin-right: 10px;
  color: #555;
  transition: transform 0.3s ease, color 0.3s ease;
  font-size: 18px;
  vertical-align: middle;
}
.caret:hover {
  color: #007bff;
}
.caret-down {
  transform: rotate(90deg);
  color: #007bff;
}

/* Nome do utilizador */
strong {
  cursor: pointer;
  font-size: 1.2em;
  vertical-align: middle;
  user-select: none;
  color: #222;
  transition: color 0.3s ease;
}
strong:hover {
  color: #007bff;
}

/* Dropdown das encomendas */
.encomendas-dropdown {
  display: none;
  margin-left: 28px;
  margin-top: 12px;
  border-left: 3px solid #007bff;
  padding-left: 15px;
}

/* Cada encomenda */
.encomendas-dropdown > div {
  background: #f9f9f9;
  border-radius: 6px;
  padding: 12px 15px;
  margin-bottom: 12px;
  box-shadow: inset 0 0 5px rgba(0,0,0,0.05);
  transition: background-color 0.3s ease;
}
.encomendas-dropdown > div:hover {
  background-color: #e9f0ff;
}

/* Texto das encomendas */
.encomendas-dropdown p {
  margin: 4px 0;
  font-size: 0.95em;
  color: #444;
}
.encomendas-dropdown p strong {
  color: #007bff;
}

/* Linha separadora */
hr {
  border: none;
  border-top: 1px solid #ddd;
  margin: 10px 0 0 0;
}

</style>

<div>
<?php foreach($encomendas_por_utilizador as $user_id => $user_data): ?>
    <div>
        <span class="caret" onclick="toggleDropdown(<?= $user_id ?>)">&#9654;</span>
        <strong style="cursor:pointer;" onclick="toggleDropdown(<?= $user_id ?>)"><?= htmlspecialchars($user_data['nome']) ?></strong>
        <div id="dropdown-<?= $user_id ?>" class="encomendas-dropdown">
            <?php foreach ($user_data['encomendas'] as $encomenda): ?>
                <div>
                    <p><strong>Encomenda:</strong> <?= htmlspecialchars($encomenda['codigo_encomenda']) ?> - <?= htmlspecialchars($encomenda['data_encomenda']) ?></p>
                    <p><strong>Morada:</strong> <?= htmlspecialchars($encomenda['morada']) ?></p>
                    <p><strong>Total:</strong> <?= number_format($encomenda['total'], 2, ',', '.') ?>€</p>
                    <p><strong>Pagamento:</strong> <?= htmlspecialchars($encomenda['metodo_pagamento']) ?></p>
                    <p><strong>Entrega:</strong> <?= htmlspecialchars($encomenda['tipo_entrega']) ?></p>
                    <hr>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>
</div>

<script>
function toggleDropdown(userId) {
    const dropdown = document.getElementById('dropdown-' + userId);
    const caret = dropdown.previousElementSibling.previousElementSibling; // O span com caret
    
    if (dropdown.style.display === "block") {
        dropdown.style.display = "none";
        caret.classList.remove('caret-down');
    } else {
        dropdown.style.display = "block";
        caret.classList.add('caret-down');
    }
}
</script>
