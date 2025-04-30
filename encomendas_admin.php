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
    <title>Ver Encomendas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
                    <a class="btn btn-outline-light" href="/PAP/home.php"><button>Sair</button></a>

    <h1>Lista de Encomendas</h1>
    <p>Consulta aqui todas as encomendas feitas pelos utilizadores.</p>
    <!-- Aqui irá uma tabela com as encomendas -->
</div>
</body>
</html>
