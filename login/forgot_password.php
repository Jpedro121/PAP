<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

$conn = new mysqli("localhost", "root", "", "skateshop");

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);

    $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($user = $res->fetch_assoc()) {
        $token = bin2hex(random_bytes(32));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        $resetLink = "http://localhost/PAP/login/reset_password.php?token=$token";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'joaopedroantunes1980@gmail.com';
            $mail->Password = 'qcbh hpkt uafr ivuj';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('joaopedroantunes1980@gmail.com', 'SK8nation');
            $mail->addAddress($email, $user['username']);
            $mail->isHTML(true);
            $mail->Subject = 'Recuperação de Palavra-passe - SK8nation';
            $mail->Body = "
                <h3>Olá, {$user['username']}!</h3>
                <p>Recebemos um pedido de recuperação da sua palavra-passe.</p>
                <p><a href='$resetLink'>Clique aqui para definir uma nova palavra-passe</a></p>
                <p>Este link é válido por 1 hora.</p>
            ";

            $mail->send();
            $mensagem = "Um email foi enviado com instruções de recuperação.";
        } catch (Exception $e) {
            $mensagem = "Erro ao enviar email: " . $mail->ErrorInfo;
        }
    } else {
        $mensagem = "Email não encontrado.";
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Palavra-passe</title>
</head>
<body>
    <h2>Recuperar Palavra-passe</h2>
    <?php if ($mensagem): ?>
        <p><?= $mensagem ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Email:</label>
        <input type="email" name="email" required>
        <button type="submit">Enviar Email</button>
    </form>
</body>
</html>
