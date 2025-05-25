<?php
session_start();

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
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: system-ui, sans-serif;
            color: #333;
        }
        .navbar {
            background-color: #fff;
            border-bottom: 1px solid #ddd;
        }
        .navbar-brand {
            font-weight: 500;
        }
        .logout-link {
            font-size: 0.9rem;
            color: #555;
            text-decoration: none;
            border: 1px solid #ccc;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .logout-link:hover {
            background-color: #eee;
        }
        .dashboard {
            max-width: 800px;
            margin: 60px auto;
        }
        .dashboard h2 {
            margin-bottom: 30px;
            text-align: center;
            font-weight: 500;
        }
        .dashboard a {
            display: block;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            transition: background-color 0.2s;
        }
        .dashboard a:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <nav class="navbar px-3 py-2">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <span class="navbar-brand">Admin - SkateShop</span>
            <a href="/PAP/home.php" class="logout-link">Sair</a>
        </div>
    </nav>

    <div class="dashboard">
        <h2>Painel de Administração</h2>
        <a href="login/admin_users.php">Gerir Utilizadores</a>
        <a href="produtos_admin.php">Gerir Produtos</a>
        <a href="encomendas_admin.php">Ver Encomendas</a>
        <a href="listar_produtos.php">Editar Produtos</a>
    </div>
</body>
</html>
