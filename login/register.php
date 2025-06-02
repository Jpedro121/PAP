<?php
session_start();

if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: /PAP/dashboard_admin.php");
    } else {
        header("Location: /PAP/login/userprofi.php");
    }
    exit();
}

require 'C:/xampp/htdocs/PAP/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = new mysqli("localhost", "root", "", "skateshop");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $mensagem = "Erro: Todos os campos são obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "Erro: O email não é válido.";
    } elseif ($password !== $confirm_password) {
        $mensagem = "Erro: As senhas não coincidem.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $mensagem = "Erro: Nome de usuário ou email já existe.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user';

            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                $_SESSION["user_id"] = $stmt->insert_id;
                $_SESSION["username"] = $username;
                $_SESSION["email"] = $email;
                $_SESSION["role"] = $role;

                // Enviar email de boas-vindas
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'joaopedroantunes1980@gmail.com';
                    $mail->Password = 'qcbh hpkt uafr ivuj'; // Use App Password do Gmail
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
                    $mail->CharSet = 'UTF-8';

                    $mail->setFrom('joaopedroantunes1980@gmail.com', 'Sk8Nation');
                    $mail->addAddress($email, $username);

                    $mail->Subject = "🎉 Bem-vindo à Sk8Nation!";
                    $mail->isHTML(true);
                    
                    $mail->Body = "
                    <html>
                    <body style='font-family: Arial, sans-serif;'>
                        <h2>What's up, " . htmlspecialchars($username) . "!</h2>
                        <p>Thank you for registering at <strong>SkateShop</strong>! We're happy to have you with us. 🛹</p>
                        <p>Explore our store and enjoy the best skateboarding products.</p>
                        <p><a href='http://localhost/PAP'>Click here to visit our store</a></p>
                        <p>If you have any questions, feel free to reach us at: suporte@skateshop.com</p>
                        <br>
                        <p>The SkateShop Team</p>
                    </body>
                    </html>
                    ";

                    $mail->AltBody = "Hey $username!\n\nThank you for registering at SkateShop! Visit our store at: http://localhost/PAP\n\nThe SkateShop Team.";


                    $mail->send();
                } catch (Exception $e) {
                    error_log("Erro ao enviar email de boas-vindas: " . $mail->ErrorInfo);
                }

                header("Location: userprofi.php");
                exit();
            } else {
                $mensagem = "Erro ao registrar. Tente novamente mais tarde.";
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <?php include('../head.html'); ?>
    <title>Register</title>
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
            width: 100%;
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
    <h2>Create Account</h2>

    <?php if (!empty($mensagem)): ?>
        <div class="error"><?php echo $mensagem; ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email</label>
        <input type="text" id="email" name="email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button type="submit" name="register">Register</button>
    </form>

    <div class="message">
        Have an Account? <a href="login.php">Login</a>
    </div>
</main>
</body>
</html>
