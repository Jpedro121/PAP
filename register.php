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
        echo "Erro: Todos os campos são obrigatórios.";
    } elseif ($password !== $confirm_password) {
        echo "Erro: As senhas não coincidem.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "Este nome de usuário já existe. Escolha outro.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $role = 'user';

            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed_password, $role);

            if ($stmt->execute()) {
                $_SESSION["username"] = $username;
                $_SESSION["role"] = $role;

                header("Location: home.php");
                exit();
            } else {
                echo "Erro ao criar a conta.";
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
    <meta charset="UTF-8">
    <title>Registrar - Skateshop</title>
    <link rel="stylesheet" href="static/estilos.css"> 
</head>
<body>
<?php include('header.php'); ?>
    <main>
        
        <h2>Criar Conta</h2>

        <form action="register.php" method="post">
            <label>Nome de Utilizador:</label>
            <input type="text" name="username" required><br><br>
            
            <label>Palavra-passe:</label>
            <input type="password" name="password" required><br><br>

            <label>Confirmar Palavra-passe:</label>
            <input type="password" name="confirm_password" required><br><br>

            <button type="submit" name="register">Registrar</button>
        </form>

    
    

        <?php if (isset($_SESSION["username"])): ?>
            <p>Você já está logado como <?php echo $_SESSION["username"]; ?>. <a href="userprofi.php">Ir para o perfil</a></p>
        <?php else: ?>
            <p>Já tem uma conta? <a href="login.php">Fazer Login</a></p>
        <?php endif; ?>
    </main>
</body>
</html>