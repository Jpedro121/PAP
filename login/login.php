<?php
session_start();

if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: /PAP/dashboard_admin.php");
    } else {
        header("Location: /PAP/userprofi.php");
    }
    exit();
}


$conn = new mysqli("localhost", "root", "", "skateshop");
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (!empty($username_or_email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, username, email, password, role FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username_or_email, $username_or_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION["username"] = $user["username"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"] = $user["role"];
                header("Location: ../userprofi.php");
                exit();
            } else {
                $mensagem = "Palavra-passe incorreta.";
            }
        } else {
            $mensagem = "Utilizador ou email não encontrado.";
        }
    } else {
        $mensagem = "Por favor preencha todos os campos.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <?php include('../head.html'); ?>
    <title>Login</title>
<style>
        body {
        font-family: "Segoe UI", sans-serif;
        background-color: #f5f5f5;
        }

        .form-container {
            max-width: 460px;
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
            width: 105%;
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
            text-decoration: unset;
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
                <label for="username"><?= $lang['Username'] ?></label>
                <input type="text" name="username" id="username" required>

                <label for="password"><?= $lang['Password'] ?></label>
                <input type="password" name="password" id="password" required>

                <button type="submit" name="login"><?= $lang['Login'] ?></button>
            </form>

            <div class="signup-link">
                <p><?= $lang['No_Account'] ?><a class="signup-link" href="register.php">Criar Conta</a></p>
            </div>
            <div class="signup-link">
                <p><a class="signup-link" href="forgot_password.php"><?= $lang['Forgot_Password'] ?></a></p>
            </div>

        </div>
    </main>
</body>
</html>
