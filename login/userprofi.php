<?php
session_start();
if (!isset($_SESSION["username"]) || !isset($_SESSION["user_id"])) {
    header("Location: ../login.php"); 
    exit();
}
require '../db.php';
$user_id = $_SESSION["user_id"];


$sql = "SELECT username, morada FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $dados = $result->fetch_assoc();
    $username = $dados['username'];
    $morada = $dados['morada'];
} else {
    $username = "Desconhecido";
    $morada = "";
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <?php include('../head.html'); ?>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .perfil-container {
            background-color: #fff;
            max-width: 700px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2, h3 {
            text-align: center;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"], textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .btn {
            margin-top: 15px;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-group {
            text-align: center;
            margin-top: 30px;
        }
        .orders {
            margin-top: 30px;
        }
        .order-item {
            background-color: #f9f9f9;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <?php include('../header.php'); ?>
    <div class="perfil-container">
        <h2>Olá, <span style="color:#007bff;"><?php echo htmlspecialchars($username); ?></span></h2>
        <p style="text-align:center;">Aqui podes editar o teu perfil, morada e ver as tuas encomendas.</p>

        <!-- Editar nome de utilizador -->
        <h3>Editar Nome de Utilizador</h3>
        <form action="update_profile.php" method="post">
            <label for="novo_username">Novo Nome:</label>
            <input type="text" name="novo_username" id="novo_username" value="<?php echo htmlspecialchars($username); ?>" required>
            <button type="submit" class="btn">Atualizar Nome</button>
        </form>

        <!-- Alterar Palavra-passe -->
        <h3>Alterar Palavra-passe</h3>
        <form action="update_password.php" method="post">
            <label for="password_atual">Palavra-passe Atual:</label>
            <input type="password" name="password_atual" id="password_atual" required>
            <label for="nova_password">Nova Palavra-passe:</label>
            <input type="password" name="nova_password" id="nova_password" required>
            <button type="submit" class="btn">Alterar Palavra-passe</button>
        </form>
        
        <h3>Email</h3>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>

        </form>
        <!-- Morada -->
        <h3>Morada</h3>
        <form action="update_address.php" method="post">
            <label for="morada">Morada Atual:</label>
            <textarea name="morada" id="morada" rows="3" required><?php echo htmlspecialchars($morada); ?></textarea>
            <button type="submit" class="btn">Atualizar Morada</button>
        </form>

        <div class="btn-group">
            <a href="../home.php" class="btn"><i class="fas fa-home"></i> Home</a>
            <a href="logout.php" class="btn"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </div>
    </div>
</body>
</html>
