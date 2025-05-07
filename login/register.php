<?php
session_start(); 
$conn = new mysqli("localhost", "root", "", "skateshop");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Todos os campos são obrigatórios.";
    } elseif ($password !== $confirm_password) {
        $error = "As senhas não coincidem.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Este nome de utilizador já existe.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user';

            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed_password, $role);

            if ($stmt->execute()) {
                $_SESSION["username"] = $username;
                $_SESSION["role"] = $role;
                header("Location: ../home.php");
                exit();
            } else {
                $error = "Erro ao criar a conta.";
            }
        }
        $stmt->close(); 
    }
}
$conn->close(); 
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <?php include('../head.html'); ?>
    <title>Registrar</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        main {
            max-width: 400px;
            margin: 60px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 6px;
            color: #444;
            font-weight: 500;
        }

        input[type="text"],
        input[type="password"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.2s;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .message a {
            color: #007bff;
            text-decoration: none;
        }

        .message a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php include('../header.php'); ?>
<main>
    <h2>Criar Conta</h2>

    <?php if (isset($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <label for="username">Nome de Utilizador</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Palavra-passe</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirmar Palavra-passe</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button type="submit" name="register">Registrar</button>
    </form>

    <div class="message">
        <?php if (isset($_SESSION["username"])): ?>
            Você já está logado como <strong><?php echo $_SESSION["username"]; ?></strong>. 
            <a href="userprofi.php">Ir para o perfil</a>
        <?php else: ?>
            Já tem uma conta? <a href="login.php">Fazer Login</a>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
