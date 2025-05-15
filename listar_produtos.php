<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login/login.php");
    exit();
}

include 'db.php';

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
    $stmt = $conn->prepare("SELECT * FROM produtos WHERE categoria = ?");
    $stmt->bind_param("s", $filtro_categoria);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM produtos";
    $result = $conn->query($sql);
}
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

    <a href="dashboard_admin.php" class="button is-light mt-4">Voltar ao Painel</a>
  </div>
</section>

<!-- JS -->
<script>
  function toggleDropdown() {
    const dropdown = document.getElementById("categoriaDropdown");
    dropdown.classList.toggle("is-active");
  }

  // Fecha dropdown se clicares fora
  document.addEventListener('click', function(event) {
    const dropdown = document.getElementById("categoriaDropdown");
    if (!dropdown.contains(event.target)) {
      dropdown.classList.remove("is-active");
    }
  });
</script>
</body>
</html>
