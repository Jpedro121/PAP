<?php
session_start(); 

if (isset($_SESSION['username'])) {
    header('Location: ../home.php');
    exit();
}

$error_message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : ''; 
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <?php include('../head.html'); ?>
    <link rel="stylesheet" href="../static/estilos.css">
    <title>Login</title>
    <style>
        .form-container {
            max-width: 400px;
            margin: 60px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
        label {
            display: block;
            margin-top: 15px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #0056b3;
        }
        .signup-link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <?php include('../header.php'); ?>
    <main>
        <div class="form-container">
            <h2>Login</h2>

            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="auth.php" method="post">
                <label for="username">Utilizador:</label>
                <input type="text" name="username" id="username" required>

                <label for="password">Palavra-passe:</label>
                <input type="password" name="password" id="password" required>

                <button type="submit" name="login">Entrar</button>
            </form>

            <div class="signup-link">
                <p>Ainda não tem uma conta? <a href="register.php">Criar Conta</a></p>
            </div>
        </div>
    </main>
</body>
</html>
