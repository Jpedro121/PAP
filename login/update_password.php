<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (
    isset($_POST['password_atual'], $_POST['nova_password']) &&
    !empty($_POST['password_atual']) &&
    !empty($_POST['nova_password'])
) {
    $user_id = $_SESSION['user_id'];
    $password_atual = $_POST['password_atual'];
    $nova_password = password_hash($_POST['nova_password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($password_bd);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($password_atual, $password_bd)) {
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $nova_password, $user_id);
        if ($stmt->execute()) {
            header("Location: userprofi.php?sucesso=1");
            exit();
        } else {
            echo "Erro ao atualizar a password.";
        }
        $stmt->close();
    } else {
        echo "Palavra-passe atual incorreta.";
    }
} else {
    echo "Preenche todos os campos.";
}

$conn->close();
?>
