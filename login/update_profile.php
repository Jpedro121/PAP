<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['novo_username']) && !empty(trim($_POST['novo_username']))) {
    $novo_username = trim($_POST['novo_username']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
    $stmt->bind_param("si", $novo_username, $user_id);

    if ($stmt->execute()) {
        $_SESSION['username'] = $novo_username;
        header("Location: userprofi.php?sucesso=1");
        exit();
    } else {
        echo "Erro ao atualizar: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "O nome de utilizador não pode estar vazio.";
}

$conn->close();
?>
