<?php
session_start();

// Se não estiver logado ou não for admin, manda pra login
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login/login.php");
    exit();
}

include('../db.php');

// Pega só os usuários normais (não admins)
$result = $conn->query("SELECT id, username, role, created_at FROM users WHERE role = 'user'");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['delete'])) {
        $id = (int) $_POST['user_id'];

        $conn->begin_transaction();

        try {
            // Primeiro apaga os produtos relacionados às encomendas do usuário
            $stmt = $conn->prepare("
                DELETE ep FROM encomenda_produtos ep
                JOIN encomendas e ON ep.encomenda_id = e.id
                WHERE e.user_id = ?
            ");
            if (!$stmt) throw new Exception("Erro no prepare (encomenda_produtos): " . $conn->error);
            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) throw new Exception("Erro no execute (encomenda_produtos): " . $stmt->error);
            $stmt->close();

            // Depois apaga as encomendas do usuário
            $stmt = $conn->prepare("DELETE FROM encomendas WHERE user_id = ?");
            if (!$stmt) throw new Exception("Erro no prepare (encomendas): " . $conn->error);
            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) throw new Exception("Erro no execute (encomendas): " . $stmt->error);
            $stmt->close();

            // Por fim, apaga o usuário
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            if (!$stmt) throw new Exception("Erro no prepare (users): " . $conn->error);
            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) throw new Exception("Erro no execute (users): " . $stmt->error);
            $stmt->close();

            $conn->commit();

        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error'] = "Erro ao eliminar utilizador e seus dados associados: " . $e->getMessage();
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin - Gestão de Utilizadores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
    body {
        background-color: #f5f5f5;
        font-family: system-ui, sans-serif;
        color: #333;
        padding: 40px 20px;
    }
    .container {
        max-width: 900px;
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }
    h1 {
        text-align: center;
        margin-bottom: 30px;
        font-weight: 500;
    }
    table {
        width: 100%;
    }
    th, td {
        text-align: center;
        vertical-align: middle;
        border-bottom: 1px solid #ddd;
    }
    thead tr {
        background-color: transparent !important; /* Remove cor do cabeçalho */
    }
    thead th {
        color: #333;
        font-weight: 600;
        border-bottom: 2px solid #ddd;
    }
    .error {
        color: red;
        text-align: center;
        margin-bottom: 20px;
    }
    form {
        display: inline-block;
    }
    select.form-select {
        width: auto;
        display: inline-block;
        margin-right: 8px;
        vertical-align: middle;
        padding: 5px 8px;
        font-size: 0.9rem;
        border-radius: 6px;
        border: 1px solid #ccc;
        background-color: #fafafa;
        cursor: pointer;
        transition: border-color 0.2s ease-in-out;
    }
    select.form-select:focus {
        outline: none;
        border-color: #999;
        background-color: #fff;
    }
    /* Botões edit e voltar iguais, minimalistas */
    button.edit-btn, .btn-links a {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        color: #333;
        padding: 6px 14px;   /* Botões menores */
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s;
        white-space: nowrap;
    }
    button.edit-btn:hover, .btn-links a:hover {
        background-color: #f0f0f0;
        text-decoration: none;
        color: #333;
    }
    /* Botão eliminar vermelho menor e do mesmo tamanho */
    button.delete-btn {
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 6px 14px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        white-space: nowrap;
        transition: background-color 0.2s;
    }
    button.delete-btn:hover {
        background-color: #b52b36;
    }
    .btn-links {
        margin-top: 25px;
        text-align: center;
    }
</style>

</head>
<body>
    <div class="container">
        <h1>Gestão de Utilizadores</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Função</th>
                    <th>Data de Criação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <select name="role" class="form-select form-select-sm">
                                    <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>Utilizador</option>
                                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
                                </select>
                                <button type="submit" name="edit" class="edit-btn">Alterar Função</button>
                            </form>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Tem a certeza que deseja eliminar este utilizador?');">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit" name="delete" class="delete-btn">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="btn-links">
            <a href="../dashboard_admin.php">Voltar à Administração</a>
        </div>
    </div>
</body>
</html>
