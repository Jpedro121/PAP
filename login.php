    <?php
    session_start(); 

    if (isset($_SESSION['username'])) {
        header('Location: home.php');
        exit();
    }

    $error_message = isset($_GET['error']) ? $_GET['error'] : ''; 
    ?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <?php include('head.html'); ?>
    <title>Login</title>
</head>
<body>
    <?php include('header.php'); ?>
    <main>
        <h2>Login</h2>

        <form action="auth.php" method="post">
            <label>Utilizador:</label>
            <input type="text" name="username" required><br><br>
            <label>Palavra-passe:</label>
            <input type="password" name="password" required><br><br>
            <button type="submit" name="login">Entrar</button>
        </form>
        
        <p>Ainda não tem uma conta? <a href="register.php">Criar Conta</a></p>
        
    </main>
</body>
</html>
