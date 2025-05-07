<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit();
}

include('../db.php');

$result = $conn->query("SELECT id, username, role, created_at FROM users");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['delete'])) {
        $id = (int) $_POST['user_id'];
        if ($_SESSION['user_id'] != $id) { // Impede que o admin se apague a si próprio
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } elseif (isset($_POST['edit'])) {
        $id = (int) $_POST['user_id'];
        $new_role = $_POST['role'];
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $new_role, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gestão de Utilizadores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        form {
            display: inline;
        }
        button {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .edit-btn {
            background-color: #ffc107;
            color: #000;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        .btn-link {
            text-align: center;
            margin-top: 20px;
        }
        .btn-link a {
            padding: 10px 20px;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            border-radius: 6px;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <h1>Gestão de Utilizadores</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Função</th>
            <th>Data de Criação</th>
            <th>Ações</th>
        </tr>
        <?php while ($user = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td><?= htmlspecialchars($user['created_at']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <select name="role">
                            <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>Utilizador</option>
                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
                        </select>
                        <button type="submit" name="edit" class="edit-btn">Alterar Função</button>
                    </form>
                    <?php if ($user['id'] != $_SESSION['user_id']) { ?>
                        <form method="POST" onsubmit="return confirm('Tem a certeza que deseja eliminar este utilizador?');">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <button type="submit" name="delete" class="delete-btn">Eliminar</button>
                        </form>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>

    <div class="btn-link">
        <a href="/PAP/login/login.php">Sair</a>
        <a href="../dashboard_admin.php">Voltar à Administração</a>
    </div>
</body>
</html>
