<?php
require 'db.php';

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
  /* Reset básico */
  * {
    box-sizing: border-box;
  }

  /* Container geral da página admin */
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f4f7fa;
    color: #333;
    margin: 0;
    padding: 0;
  }

  /* Container centralizado */
  .container {
    max-width: 900px;
    margin: 40px auto;
    background: white;
    border-radius: 10px;
    padding: 30px 40px 40px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
  }

  /* Header superior */
  .header {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
  }

  /* Botão voltar */
  .btn-voltar {
    background-color: #007bff;
    color: white;
    padding: 8px 16px;
    font-weight: 600;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-right: 20px;
    box-shadow: 0 2px 5px rgb(0 123 255 / 0.3);
  }
  .btn-voltar:hover {
    background-color: #0056b3;
  }

  /* Ícone da seta no botão voltar (usando CSS puro) */
  .btn-voltar::before {
    content: "←";
    font-weight: 700;
    margin-right: 6px;
  }

  /* Título principal */
  h1 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #222;
    margin: 0;
    flex-grow: 1;
  }

  /* Caixa do utilizador */
  .user-box {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 3px 12px rgb(0 0 0 / 0.1);
    margin-bottom: 18px;
    padding: 20px 28px;
    cursor: pointer;
    user-select: none;
    transition: box-shadow 0.3s ease;
    border-left: 5px solid transparent;
  }
  .user-box:hover {
    box-shadow: 0 6px 18px rgb(0 0 0 / 0.15);
    border-left-color: #007bff;
  }

  /* Cabeçalho utilizador (nome + caret) */
  .user-header {
    display: flex;
    align-items: center;
    font-size: 1.3rem;
    font-weight: 600;
    color: #333;
    gap: 12px;
  }

  /* Caret */
  .caret {
    font-size: 20px;
    transition: transform 0.3s ease, color 0.3s ease;
    color: #555;
    user-select: none;
  }
  .caret:hover {
    color: #007bff;
  }
  .caret.caret-down {
    transform: rotate(90deg);
    color: #007bff;
  }

  /* Dropdown das encomendas */
  .encomendas-dropdown {
    display: none;
    margin-top: 18px;
    margin-left: 32px;
    border-left: 3px solid #007bff;
    padding-left: 22px;
  }

  /* Cada encomenda */
  .encomenda-item {
    background: #f9faff;
    border-radius: 8px;
    padding: 14px 20px;
    margin-bottom: 16px;
    box-shadow: inset 0 0 8px rgba(0,123,255,0.1);
    transition: background-color 0.3s ease;
  }
  .encomenda-item:hover {
    background-color: #e3edff;
  }

  /* Texto das encomendas */
  .encomenda-item p {
    margin: 6px 0;
    font-size: 1rem;
    color: #444;
  }
  .encomenda-item p strong {
    color: #007bff;
  }

  /* Linha separadora */
  hr {
    border: none;
    border-top: 1px solid #ddd;
    margin: 12px 0 0 0;
  }

</style>

<div class="container">
  <div class="header">
    <a href="dashboard_admin.php" class="btn-voltar" title="Voltar ao Painel">Voltar</a>
    <h1>Encomendas dos Utilizadores</h1>
  </div>

  <?php foreach($encomendas_por_utilizador as $user_id => $user_data): ?>
    <div class="user-box" onclick="toggleDropdown(<?= $user_id ?>)">
      <div class="user-header">
        <span class="caret">&#9654;</span>
        <span><?= htmlspecialchars($user_data['nome']) ?></span>
      </div>
      <div id="dropdown-<?= $user_id ?>" class="encomendas-dropdown">
        <?php foreach ($user_data['encomendas'] as $encomenda): ?>
          <div class="encomenda-item">
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
  const userBox = dropdown.parentElement;
  const caret = userBox.querySelector('.caret');

  if (dropdown.style.display === "block") {
    dropdown.style.display = "none";
    caret.classList.remove('caret-down');
  } else {
    dropdown.style.display = "block";
    caret.classList.add('caret-down');
  }
}
</script>
