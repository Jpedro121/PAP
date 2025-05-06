<?php
session_start();
$conn = new mysqli("localhost", "root", "", "skateshop");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = trim($_POST["username_or_email"]); // Pode ser username OU email
    $password = trim($_POST["password"]);

    if (empty($identifier) || empty($password)) {
        $error = "Todos os campos são obrigatórios.";
    } else {
        // Procurar por username ou email
        $stmt = $conn->prepare("SELECT id, username, email, password, role FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION["username"] = $user["username"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"] = $user["role"];
                header("Location: ../home.php");
                exit();
            } else {
                $error = "Senha incorreta.";
            }
        } else {
            $error = "Utilizador ou email não encontrado.";
        }
    }
}
$conn->close();
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
                <label for="login">Utilizador ou Email:</label>
                <input type="text" name="login" id="login" required>

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