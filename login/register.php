<?php
session_start();

if (isset($_SESSION['username']) && $_SERVER["REQUEST_METHOD"] !== "POST") {
    if ($_SESSION['role'] === 'admin') {
        header("Location: /PAP/dashboard_admin.php");
    } else {
        header("Location: /PAP/login/userprofi.php");
    }
    exit();
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

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
        $mensagem = "Error: All camps are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "Error: The email address is not valid.";
    } elseif ($password !== $confirm_password) {
        $mensagem = "Error: The passwords do not match.";
    } else {
        // Verifica se o nome de utilizador ou o email já existe
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $mensagem = "Error: The username or email already exists.";
        } else {
            // Inserir dados na base de dados
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'utilizador';  // Defina o papel do utilizador como "utilizador"

            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
           if ($stmt->execute()) {
            $new_user_id = $stmt->insert_id; // Obtem o ID do novo utilizador inserido

            $_SESSION["user_id"] = $new_user_id; // <-- esta linha é essencial!
            $_SESSION["username"] = $username;
            $_SESSION["email"] = $email;
            $_SESSION["role"] = $role;
            $mensagem = "Sucess Login!";
            $success = true;


                // Envio do email
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'joaopedroantunes1980@gmail.com';
                    $mail->Password = 'qcbh hpkt uafr ivuj'; // Usar app password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('joaopedroantunes1980@gmail.com', 'SK8nation');
                    $mail->addAddress($email, $username);

                    $mail->isHTML(true);
                    $mail->Subject = 'Bem-vindo à SK8nation!';

                    $logoURL = 'https://i.postimg.cc/VkXV804q/logopap.png';

                    $mail->Body = "
                        <img src='$logoURL' alt='SK8nation' width='275'><br><br>
                        <h3>Boas, $username!</h3>
                        <p>Bem-vindo à <strong>SK8nation</strong> – a tua nova crew no mundo do skate!</p>
                        <p>A tua conta foi criada com sucesso e estás oficialmente dentro da nossa comunidade.</p>
                        <p>Explora as nossas boards, peças e estilos. Prepara-te para elevar o teu nível!</p>
                        <p>Estamos felizes por te ter connosco. Let's roll 🤙</p>
                        <hr>";

                    $mail->send();
                } catch (Exception $e) {
                    // Opcional: log de erro
                    error_log("Error to send email " . $mail->ErrorInfo);
                }
            } else {
                $mensagem = "Error to register your account. Try again Later.";
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

    <?php if (isset($mensagem) && !empty($mensagem)): ?>
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
            <a>Have an account ? <a href="login.php">Login</a>
    </div>
</main>
</body>
</html>

