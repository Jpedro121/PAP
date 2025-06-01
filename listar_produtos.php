<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login/login.php");
    exit();
}

include 'db.php';

// Configurações da paginação
$produtos_por_pagina = 10;
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_atual < 1) $pagina_atual = 1;
$offset = ($pagina_atual - 1) * $produtos_por_pagina;

// Obter categorias únicas
$categorias = [];
$categoria_query = "SELECT DISTINCT categoria FROM produtos";
$categoria_result = $conn->query($categoria_query);
while ($cat = $categoria_result->fetch_assoc()) {
    $categorias[] = $cat['categoria'];
}

// Filtragem por categoria
$filtro_categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

if ($filtro_categoria) {
    // Contar total para paginação
    $stmt_total = $conn->prepare("SELECT COUNT(*) as total FROM produtos WHERE categoria = ?");
    $stmt_total->bind_param("s", $filtro_categoria);
    $stmt_total->execute();
    $total_result = $stmt_total->get_result();
    $total_produtos = $total_result->fetch_assoc()['total'];

    // Buscar produtos com limite
    $stmt = $conn->prepare("SELECT * FROM produtos WHERE categoria = ? LIMIT ?, ?");
    $stmt->bind_param("sii", $filtro_categoria, $offset, $produtos_por_pagina);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Contar total para paginação
    $total_sql = "SELECT COUNT(*) as total FROM produtos";
    $total_result = $conn->query($total_sql);
    $total_produtos = $total_result->fetch_assoc()['total'];

    // Buscar produtos com limite
    $stmt = $conn->prepare("SELECT * FROM produtos LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $produtos_por_pagina);
    $stmt->execute();
    $result = $stmt->get_result();
}

$total_paginas = ceil($total_produtos / $produtos_por_pagina);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Lista de Produtos</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .icon-caret {
      transition: transform 0.3s ease;
    }
    .dropdown.is-active .icon-caret {
      transform: rotate(180deg);
    }
    .dropdown-menu {
      animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-5px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
<section class="section">
  <div class="container">
    <h1 class="title">Lista de Produtos</h1>

    <!-- Dropdown de Categorias -->
    <div class="dropdown mb-5" id="categoriaDropdown">
      <div class="dropdown-trigger">
        <button class="button is-link" aria-haspopup="true" aria-controls="dropdown-menu" onclick="toggleDropdown()">
          <span><?= $filtro_categoria ? htmlspecialchars($filtro_categoria) : 'Filtrar por Categoria' ?></span>
          <span class="icon is-small">
            <i class="fas fa-angle-down icon-caret" aria-hidden="true"></i>
          </span>
        </button>
      </div>
      <div class="dropdown-menu" id="dropdown-menu" role="menu">
        <div class="dropdown-content">
          <?php foreach ($categorias as $categoria): ?>
            <a href="?categoria=<?= urlencode($categoria) ?>" class="dropdown-item"><?= htmlspecialchars($categoria) ?></a>
          <?php endforeach; ?>
          <hr class="dropdown-divider">
          <a href="listar_produtos.php" class="dropdown-item">Mostrar Todos</a>
        </div>
      </div>
    </div>

    <!-- Tabela -->
    <table class="table is-fullwidth is-striped is-hoverable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Preço</th>
          <th>Categoria</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['nome']) ?></td>
            <td><?= number_format($row['preco'], 2) ?>€</td>
            <td><?= htmlspecialchars($row['categoria']) ?></td>
            <td>
              <a href="editar_produto.php?id=<?= $row['id'] ?>" class="button is-small is-link">Editar</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <!-- Paginação -->
    <?php if ($total_paginas > 1): ?>
    <nav class="pagination is-centered mt-5" role="navigation" aria-label="pagination">
      <?php if ($pagina_atual > 1): ?>
        <a class="pagination-previous" href="?<?= $filtro_categoria ? 'categoria=' . urlencode($filtro_categoria) . '&' : '' ?>pagina=<?= $pagina_atual - 1 ?>">Anterior</a>
      <?php else: ?>
        <a class="pagination-previous" disabled>Anterior</a>
      <?php endif; ?>

      <?php if ($pagina_atual < $total_paginas): ?>
        <a class="pagination-next" href="?<?= $filtro_categoria ? 'categoria=' . urlencode($filtro_categoria) . '&' : '' ?>pagina=<?= $pagina_atual + 1 ?>">Seguinte</a>
      <?php else: ?>
        <a class="pagination-next" disabled>Seguinte</a>
      <?php endif; ?>

      <ul class="pagination-list">
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
          <li>
            <a class="pagination-link <?= $i == $pagina_atual ? 'is-current' : '' ?>" href="?<?= $filtro_categoria ? 'categoria=' . urlencode($filtro_categoria) . '&' : '' ?>pagina=<?= $i ?>">
              <?= $i ?>
            </a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
    <?php endif; ?>

    <a href="dashboard_admin.php" class="button is-light mt-4">Voltar ao Painel</a>
  </div>
</section>

<!-- JS -->
<script>
  function toggleDropdown() {
    const dropdown = document.getElementById("categoriaDropdown");
    dropdown.classList.toggle("is-active");
  }

  document.addEventListener('click', function(event) {
    const dropdown = document.getElementById("categoriaDropdown");
    if (!dropdown.contains(event.target)) {
      dropdown.classList.remove("is-active");
    }
  });
</script>
</body>
</html>
