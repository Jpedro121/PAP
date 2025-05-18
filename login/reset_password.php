<?php
require '../db.php';

// Verificar token válido
$token = $_GET['token'] ?? '';
$mensagem = "";
$mensagem_tipo = "";

// Verificar se o token existe e não expirou
$stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $mensagem = $lang['Invalid_Token'] ?? "Token inválido ou expirado.";
    $mensagem_tipo = "error";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($mensagem)) {
    $nova_password = $_POST['password'];
    $confirma_password = $_POST['confirm_password'];

    // Validação da senha
    if (strlen($nova_password) < 8) {
        $mensagem = $lang['Password_Too_Short'] ?? "A senha deve ter pelo menos 8 caracteres.";
        $mensagem_tipo = "error";
    } elseif ($nova_password !== $confirma_password) {
        $mensagem = $lang['Password_Mismatch'] ?? "As senhas não coincidem.";
        $mensagem_tipo = "error";
    } else {
        $hashed = password_hash($nova_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?");
        $stmt->bind_param("ss", $hashed, $token);
        
        if ($stmt->execute()) {
            $mensagem = $lang['Password_Updated'] ?? "Senha atualizada com sucesso!";
            $mensagem_tipo = "success";
            
            // Redirecionar após 3 segundos
            header("Refresh: 3; url=login.php");
        } else {
            $mensagem = $lang['Password_Update_Error'] ?? "Erro ao atualizar a senha. Por favor, tente novamente.";
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
    <title>Nova Senha - SK8nation</title>
    <link rel="stylesheet" href="/PAP/static/auth.css">
    <script>
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('password-strength-bar');
            let strength = 0;
            
            // Verifica o comprimento
            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;
            
            // Verifica caracteres diversos
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            
            // Atualiza a barra visual
            const width = strength * 20;
            strengthBar.style.width = width + '%';
            
            // Muda a cor baseada na força
            if (strength < 2) {
                strengthBar.style.backgroundColor = '#e74c3c';
            } else if (strength < 4) {
                strengthBar.style.backgroundColor = '#f39c12';
            } else {
                strengthBar.style.backgroundColor = '#2ecc71';
            }
        }
        
        function validatePasswords() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            const submitBtn = document.getElementById('submit-btn');
            
            if (password && confirm && password === confirm && password.length >= 8) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }
    </script>
</head>
<body>
    <div class="auth-container">
        <h1 class="auth-title">Definir Nova Senha</h1>
        
        <?php if ($mensagem): ?>
            <div class="alert alert-<?= $mensagem_tipo ?>">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <?php if ($result->num_rows > 0 || $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <form class="auth-form" method="post">
                <div class="form-group">
                    <label for="password">Nova Senha</label>
                    <input type="password" id="password" name="password" class="form-control" 
                           required minlength="8" oninput="checkPasswordStrength(); validatePasswords();">
                    <div class="password-strength">
                        <div id="password-strength-bar" class="password-strength-bar"></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirmar Nova Senha</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" 
                           required minlength="8" oninput="validatePasswords();">
                </div>
                
                <button type="submit" id="submit-btn" class="btn" disabled>Atualizar Senha</button>
            </form>
        <?php else: ?>
            <div class="auth-footer">
                <p>Token inválido ou expirado. Por favor, solicite um novo link de recuperação.</p>
                <a href="forgot_password.php" class="btn">Solicitar Novo Link</a>
            </div>
        <?php endif; ?>
        
        <div class="auth-footer">
            <p><a href="login.php">Voltar para o login</a></p>
        </div>
    </div>
</body>
</html>