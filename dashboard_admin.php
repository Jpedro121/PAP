<?php
session_start();

// Verificar se o utilizador tem sessão iniciada e é admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Administrador</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin - SkateShop</a>
            <div class="d-flex">
                <a class="btn btn-outline-light" href="/PAP/home.php"><button>Sair</button></a>

            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Painel de Administração</h1>
        <div class="row g-4">
            <div class="col-md-4">
                <a href="login/admin_users.php" class="btn btn-primary w-100">Gerir Utilizadores</a>
            </div>
            <div class="col-md-4">
                <a href="produtos_admin.php" class="btn btn-success w-100">Gerir Produtos</a>
            </div>
            <div class="col-md-4">
                <a href="encomendas_admin.php" class="btn btn-warning w-100">Ver Encomendas</a>
            </div>
        </div>
    </div>
</body>
</html>