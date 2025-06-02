<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['morada']) && !empty(trim($_POST['morada']))) {
    $morada = trim($_POST['morada']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE users SET morada = ? WHERE id = ?");
    $stmt->bind_param("si", $morada, $user_id);

    if ($stmt->execute()) {
        header("Location: userprofi.php?sucesso=1");
        exit();
    } else {
        echo "Error to update address: ";
    }

    $stmt->close();
} else {
    echo "The address field cannot be empty.";
}

$conn->close();
?>
