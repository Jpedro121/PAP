<?php
$conn = new mysqli("localhost", "root", "", "skateshop");
$token = $_GET['token'] ?? '';
$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nova_password = $_POST['password'];
    $confirma_password = $_POST['confirm_password'];

    if ($nova_password === $confirma_password) {
        $hashed = password_hash($nova_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?");
        $stmt->bind_param("ss", $hashed, $token);
        if ($stmt->execute()) {
            $mensagem = "Palavra-passe atualizada com sucesso.";
        } else {
            $mensagem = "Erro ao atualizar palavra-passe.";
        }
    } else {
        $mensagem = "As senhas não coincidem.";
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Nova Palavra-passe</title>
</head>
<body>
    <h2>Definir nova palavra-passe</h2>
    <?php if ($mensagem): ?>
        <p><?= $mensagem ?></p>
    <?php endif; ?>
    <form method="post">
        <input type="password" name="password" placeholder="Nova palavra-passe" required>
        <input type="password" name="confirm_password" placeholder="Confirmar" required>
        <button type="submit">Atualizar</button>
    </form>
</body>
</html>
