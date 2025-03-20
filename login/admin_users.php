<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login/login.php");
    exit();
}

include('db.php');

$result = $conn->query("SELECT id, username, role, created_at FROM users");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        $id = $_POST['user_id'];
        $conn->query("DELETE FROM users WHERE id = $id");
        header("Location: login/admin_users.php");
        exit();
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['user_id'];
        $new_role = $_POST['role'];
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $new_role, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: login/admin_users.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gerir Utilizadores</title>
</head>
<body>
    <h1>Gestão de Utilizadores</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Função</th>
            <th>Data de Criação</th>
            <th>Ações</th>
        </tr>
        <?php while ($user = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['role']; ?></td>
                <td><?php echo $user['created_at']; ?></td>
                <td>
                    <form action="login/admin_users.php" method="POST" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <select name="role">
                            <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>Utilizador</option>
                            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Administrador</option>
                        </select>
                        <button type="submit" name="edit">Alterar Função</button>
                    </form>

                    <form action="admin_users.php" method="POST" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" name="delete" onclick="return confirm('Tem certeza que deseja eliminar este utilizador?')">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
    <a href="login/login.php"><button>Sair</button></a>
</body>
</html>
