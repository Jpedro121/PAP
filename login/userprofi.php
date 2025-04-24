<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location:login.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Perfil - Skateshop</title>
    <link rel="stylesheet" href="../static/estilos.css">
</head>
<body>
    <h2>Bem-vindo, <?php echo $_SESSION["username"]; ?>!</h2>
    <p>Esta é a sua página de perfil.</p>
    <a href="../home.php"><button>Voltar para Home</button></a>
    <a href="logout.php"><button>Sair</button></a>
</body>
</html>