<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';
require '../db.php';

// Configurações de internacionalização
$mensagem = "";
$mensagem_tipo = ""; // success ou error

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);

    // Validação adicional
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "Please enter a valid email address.";
        $mensagem_tipo = "error";
    } else {
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($user = $res->fetch_assoc()) {
            $token = bin2hex(random_bytes(32));
            $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

            // Limpa tokens antigos
            $conn->query("UPDATE users SET reset_token = NULL, reset_token_expiry = NULL WHERE email = '$email'");

            $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
            $stmt->bind_param("sss", $token, $expiry, $email);
            
            if ($stmt->execute()) {
                $resetLink = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . "/PAP/login/reset_password.php?token=$token";

                $mail = new PHPMailer(true);
                try {
                    // Configurações do servidor SMTP
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'joaopedroantunes1980@gmail.com';
                    $mail->Password = 'qcbh hpkt uafr ivuj';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    $mail->CharSet = 'UTF-8';

                    // Destinatários
                    $mail->setFrom('joaopedroantunes1980@gmail.com', 'SK8nation');
                    $mail->addAddress($email, $user['username']);
                    
                    // Conteúdo
                    $mail->isHTML(true);
                    $mail->Subject = 'Recover Password- SK8nation';
                    
                    // Template de email melhorado
                    $mail->Body = "
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                            <h2 style='color: #3498db;'>Olá, {$user['username']}!</h2>
                            <pWe received a request to Recover Password.</p>
                            <p style='margin: 20px 0;'>
                                <a href='$resetLink' style='background-color: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>
                                    Redefinir Palavra-passe
                                </a>
                            </p>
                            <p>If you didn't make a request ,please ignore this email.</p>
                            <p style='font-size: 12px; color: #7f8c8d;'>Este link expira em 1 hora.</p>
                            <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
                            <p style='font-size: 12px; color: #7f8c8d;'>Equipe SK8nation</p>
                        </div>
                    ";
                    
                    $mail->AltBody = "To redefine your password, Click this Link: $resetLink";

                    $mail->send();
                    $mensagem = "An email with instructions was send to this mail: $email";
                    $mensagem_tipo = "success";
                } catch (Exception $e) {
                    $mensagem = "There was an error sending the email. Please try again later..";
                    $mensagem_tipo = "error";
                    // Log do erro (em produção, usar um sistema de logs)
                    error_log("Erro ao enviar email: " . $mail->ErrorInfo);
                }
            } else {
                $mensagem = "Error to update your account. Try again later.";
                $mensagem_tipo = "error";
            }
        } else {
            $mensagem = "Email not found in our system.";
            $mensagem_tipo = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover of Password - SK8nation</title>
    <link rel="stylesheet" href="/PAP/static/auth.css">
</head>
<body>
    <div class="auth-container">
        <h1 class="auth-title">Recover of Password</h1>
        
        <?php if ($mensagem): ?>
            <div class="alert alert-<?= $mensagem_tipo ?>">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <form class="auth-form" method="post">
            <div class="form-group">
                <label for="email">Registered email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            
            <button type="submit" class="btn">Send a recover Link</button>
        </form>
        
        <div class="auth-footer">
            <p>Remember the password <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>