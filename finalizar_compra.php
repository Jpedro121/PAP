<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: /PAP/login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$morada = $_POST['morada'] ?? '';

// Buscar produtos do carrinho do utilizador na BD
$sql = "SELECT produto_id, quantidade, preco FROM carrinho WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$produtos = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $produtos[] = $row;
    $total += $row['preco'] * $row['quantidade'];
}

if (count($produtos) === 0) {
    echo '<div class="notification is-warning">O seu carrinho está vazio.</div>';
    exit();
}

// Gerar código único da encomenda
function gerarCodigoUnico($conn) {
    do {
        $codigo = strtoupper(bin2hex(random_bytes(5)));
        $stmt = $conn->prepare("SELECT id FROM encomendas WHERE codigo_encomenda = ?");
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $stmt->store_result();
    } while ($stmt->num_rows > 0);
    return $codigo;
}

$codigo = gerarCodigoUnico($conn);

// Inserir encomenda
$stmt = $conn->prepare("INSERT INTO encomendas (user_id, total, morada, codigo_encomenda, data_encomenda) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("idss", $user_id, $total, $morada, $codigo);
$stmt->execute();
$encomenda_id = $stmt->insert_id;

// Inserir produtos da encomenda
$stmt = $conn->prepare("INSERT INTO encomenda_produtos (encomenda_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
foreach ($produtos as $item) {
    $stmt->bind_param("iiid", $encomenda_id, $item['produto_id'], $item['quantidade'], $item['preco']);
    $stmt->execute();
}

// Limpar carrinho
$stmt = $conn->prepare("DELETE FROM carrinho WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();


// Buscar email do utilizador
$stmt = $conn->prepare("SELECT email, nome FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$email = $user['email'];
$nome = $user['nome'];

// Preparar conteúdo do email
$mensagem = "Olá $nome,\n\nObrigado pela sua compra na SK8Nation!\n\n";
$mensagem .= "Resumo da sua encomenda (Código: $codigo):\n";

foreach ($produtos as $item) {
    $mensagem .= "- Produto ID: {$item['produto_id']} | Quantidade: {$item['quantidade']} | Preço unitário: €" . number_format($item['preco'], 2) . "\n";
}

$mensagem .= "\nTotal: €" . number_format($total, 2) . "\n";
$mensagem .= "Morada de entrega: $morada\n\n";
$mensagem .= "Esperamos vê-lo novamente em breve!\nSk8Nation";

// Enviar email com PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'login/phpmailer/Exception.php';
require 'login/phpmailer/PHPMailer.php';
require 'login/phpmailer/SMTP.php';

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'joaopedroantunes1980@gmail.com'; // Substitui pelo teu email
    $mail->Password   = 'qcbh hpkt uafr ivuj';           // Substitui pela tua senha de app
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('joaopedrantunes1980@gmail.com', 'Sk8Nation');
    $mail->addAddress($email, $nome);

    $mail->Subject = 'Confirmação da sua encomenda - Sk8Nation';
    $mail->Body    = $mensagem;

    $mail->send();
} catch (Exception $e) {
    error_log("Erro ao enviar email: {$mail->ErrorInfo}");
}

echo '<div class="notification is-success">Compra realizada com sucesso! Código da encomenda: ' . $codigo . '</div>';
echo '<a class="button" href="home.php">Voltar à loja</a>';
?>
