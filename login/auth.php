<?php
session_start();
$conn = new mysqli("localhost", "root", "", "skateshop");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: ../dashboard_admin.php");
    } else {
        header("Location: userprofi.php");
    }
    exit();
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $db_password, $role);
        $stmt->fetch();
    
        if (password_verify($password, $db_password)) {
            $_SESSION["user_id"] = $user_id; 
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $role;
    
            header("Location: " . ($role === "admin" ? "../dashboard_admin.php" : "../home.php"));
            exit();
        } else {
            header("Location: login.php?error=Palavra-passe incorreta.");
            exit();
        }
    } else {
        header("Location: login.php?error=Utilizador não encontrado.");
        exit();
    }
    

    $stmt->close();
}

$conn->close();
?>
