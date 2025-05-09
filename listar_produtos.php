<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login/login.php");
    exit();
}

include 'db.php';

$sql = "SELECT * FROM produtos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>Lista de Produtos</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
  <section class="section">
    <div class="container">
      <h1 class="title">Lista de Produtos</h1>

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
              <td><?= $row['nome'] ?></td>
              <td><?= $row['preco'] ?>€</td>
              <td><?= $row['categoria'] ?></td>
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
</body>
</html>
