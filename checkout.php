<?php
session_start();
require_once("db.php");
require 'C:/xampp/htdocs/PAP/login/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$produtos = [];
$total = 0;
$user_info = [];
$mensagem_erro = '';
$mensagem_sucesso = '';

$stmt = $conn->prepare("SELECT username, email, morada FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_info = $result->fetch_assoc();

function buscarProdutosCarrinho($conn, $user_id, &$produtos, &$total) {
    $stmt = $conn->prepare("SELECT c.id, p.id as produto_id, p.nome, p.imagem, c.quantidade, c.preco, c.tamanho 
                            FROM carrinho c 
                            JOIN produtos p ON c.produto_id = p.id 
                            WHERE c.user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
        $total += $row['preco'] * $row['quantidade'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalizar_compra'])) {
    try {
        $tipo_entrega = $_POST['tipo_entrega'] ?? 'delivery';
        $metodo_pagamento = trim($_POST['metodo_pagamento'] ?? '');

        if ($tipo_entrega === 'delivery') {
            $morada = trim($_POST['morada'] ?? '');
            $codigo_postal = trim($_POST['codigo_postal'] ?? '');
            $cidade = trim($_POST['cidade'] ?? '');

            if (empty($morada) || empty($codigo_postal) || empty($cidade)) {
                throw new Exception("Por favor, preencha todos os campos de entrega.");
            }

            $morada_completa = "$morada, $codigo_postal, $cidade";
        } else {
            $morada_completa = "Retirada na loja";
        }

        if (empty($metodo_pagamento)) {
            throw new Exception("Escolha um método de pagamento.");
        }

        if ($metodo_pagamento === 'Cartão') {
            $numero_cartao = str_replace(' ', '', $_POST['numero_cartao'] ?? '');
            $validade = $_POST['validade'] ?? '';
            $cvv = $_POST['cvv'] ?? '';
            $nome_cartao = trim($_POST['nome_cartao'] ?? '');

            if (strlen($numero_cartao) !== 16 || !ctype_digit($numero_cartao)) {
                throw new Exception("Número do cartão inválido. Deve conter 16 dígitos.");
            }

            if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $validade)) {
                throw new Exception("Data de validade inválida. Use o formato MM/AA.");
            }

            if (!preg_match('/^\d{3,4}$/', $cvv)) {
                throw new Exception("CVV inválido. Deve conter 3 ou 4 dígitos.");
            }

            if (empty($nome_cartao)) {
                throw new Exception("Por favor, insira o nome no cartão.");
            }
        }

        buscarProdutosCarrinho($conn, $user_id, $produtos, $total);

        if (empty($produtos)) {
            throw new Exception("O seu carrinho está vazio.");
        }

        $portes = ($tipo_entrega === 'delivery') ? 5.00 : 0.00;
        $total_com_portes = $total + $portes;

        $conn->begin_transaction();

        $codigo_encomenda = 'EN' . strtoupper(uniqid());
        $stmt = $conn->prepare("INSERT INTO encomendas (user_id, total, morada, codigo_encomenda, metodo_pagamento, tipo_entrega) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("idssss", $user_id, $total_com_portes, $morada_completa, $codigo_encomenda, $metodo_pagamento, $tipo_entrega);
        $stmt->execute();
        $encomenda_id = $conn->insert_id;

        foreach ($produtos as $produto) {
            $stmt = $conn->prepare("INSERT INTO encomenda_produtos (encomenda_id, produto_id, quantidade, preco_unitario, tamanho) 
                                    VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiids", $encomenda_id, $produto['produto_id'], $produto['quantidade'], $produto['preco'], $produto['tamanho']);
            $stmt->execute();
            
            if (!empty($produto['tamanho'])) {
                $stmt = $conn->prepare("UPDATE tamanhos_produto SET stock = stock - ? 
                                        WHERE produto_id = ? AND tamanho = ? AND stock >= ?");
                $stmt->bind_param("iisi", $produto['quantidade'], $produto['produto_id'], $produto['tamanho'], $produto['quantidade']);
                $stmt->execute();
                
                if ($stmt->affected_rows === 0) {
                    throw new Exception("Sem stock suficiente para {$produto['nome']} (Tamanho: {$produto['tamanho']})");
                }
            }
        }

        $stmt = $conn->prepare("DELETE FROM carrinho WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $conn->commit();

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'joaopedroantunes1980@gmail.com';
            $mail->Password = 'qcbh hpkt uafr ivuj';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('joaopedroantunes1980@gmail.com', 'SkateShop');
            $mail->addAddress($user_info['email'], $user_info['username']);

            $mail->Subject = "✅ Confirmação de Encomenda #$codigo_encomenda";

            $email_content = '
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #f8f9fa; padding: 20px; text-align: center; }
                    .order-details { margin: 20px 0; border: 1px solid #ddd; padding: 15px; }
                    table { width: 100%; border-collapse: collapse; margin: 15px 0; }
                    th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
                    .total { font-weight: bold; font-size: 1.1em; }
                    .footer { margin-top: 20px; font-size: 0.9em; color: #777; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1>Obrigado pela sua compra, '.htmlspecialchars($user_info['username']).'!</h1>
                    </div>
                    
                    <div class="order-details">
                        <h2>📋 Resumo da Encomenda #'.$codigo_encomenda.'</h2>
                        <p><strong>Data:</strong> '.date('d/m/Y H:i').'</p>
                        <p><strong>Método de Pagamento:</strong> '.htmlspecialchars($metodo_pagamento).'</p>
                        <p><strong>Endereço de Entrega:</strong><br>'.nl2br(htmlspecialchars($morada_completa)).'</p>
                        
                        <h3>🛍️ Produtos</h3>
                        <table>
                            <tr>
                                <th>Produto</th>
                                <th>Tamanho</th>
                                <th>Qtd</th>
                                <th>Preço</th>
                            </tr>';
            
            foreach ($produtos as $produto) {
                $email_content .= '
                            <tr>
                                <td>'.htmlspecialchars($produto['nome']).'</td>
                                <td>'.htmlspecialchars($produto['tamanho'] ?? 'N/A').'</td>
                                <td>'.$produto['quantidade'].'</td>
                                <td>€'.number_format($produto['preco'] * $produto['quantidade'], 2, ',', '.').'</td>
                            </tr>';
            }
            
            $email_content .= '
                            <tr>
                                <td colspan="3"><strong>Portes de Envio</strong></td>
                                <td>€'.number_format($portes, 2, ',', '.').'</td>
                            </tr>
                            <tr class="total">
                                <td colspan="3"><strong>Total</strong></td>
                                <td>€'.number_format($total_com_portes, 2, ',', '.').'</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="footer">
                        <p>📞 Dúvidas? Contacte-nos: suporte@skateshop.com</p>
                        <p>🛒 <a href="http://localhost/PAP">Visite nossa loja novamente</a></p>
                    </div>
                </div>
            </body>
            </html>';

            $mail->isHTML(true);
            $mail->Body = $email_content;
            
            $mail->AltBody = "Obrigado pela sua compra!\n\n"
                           . "Encomenda #$codigo_encomenda\n"
                           . "Total: €".number_format($total_com_portes, 2, ',', '.')."\n"
                           . "Método de Pagamento: $metodo_pagamento\n"
                           . "Entrega: $morada_completa\n\n"
                           . "Produtos:\n";
            
            foreach ($produtos as $produto) {
                $mail->AltBody .= "- {$produto['nome']} ({$produto['tamanho']}) x {$produto['quantidade']}: €".number_format($produto['preco'] * $produto['quantidade'], 2, ',', '.')."\n";
            }
            
            $mail->AltBody .= "\nPortes: €".number_format($portes, 2, ',', '.')."\n";
            $mail->AltBody .= "Total: €".number_format($total_com_portes, 2, ',', '.')."\n\n";
            $mail->AltBody .= "Agradecemos a sua preferência!";

            $mail->send();
            
        } catch (Exception $e) {
            error_log("Erro ao enviar email: " . $e->getMessage());
        }

        $mensagem_sucesso = "Compra realizada com sucesso! Código da encomenda: $codigo_encomenda";
        $produtos = [];
        $total = 0;

    } catch (Exception $e) {
        $conn->rollback();
        $mensagem_erro = "Erro ao processar pedido: " . $e->getMessage();
    }
} else {
    buscarProdutosCarrinho($conn, $user_id, $produtos, $total);
}
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <?php include('head.html'); ?>
    <title>Finalizar Compra - SkateShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .payment-method img {
            height: 30px;
            margin-right: 10px;
        }
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        #delivery-fields {
            display: block;
        }
        .card-icons {
            height: 25px;
            margin-left: 10px;
        }
        .was-validated .form-control:invalid, .form-control.is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        /* Estilos para a coluna de produtos */
        .order-summary {
            font-size: 0.9rem;
        }
        
        .product-column {
            max-height: 200px;
            overflow-y: auto;
            padding-right: 10px;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .product-column::-webkit-scrollbar {
            width: 5px;
        }
        
        .product-column::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .product-column::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 5px;
        }
        
        .product-column::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body>
<?php include('header.php'); ?>
<div class="container py-5">
    <h1 class="mb-4">Finalizar Compra</h1>

    <?php if ($mensagem_sucesso): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mensagem_sucesso) ?></div>
        <a href="home.php" class="btn btn-primary">Voltar às Compras</a>
    <?php else: ?>
        <?php if ($mensagem_erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($mensagem_erro) ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <form method="POST" action="checkout.php" novalidate class="needs-validation">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Informações de Entrega</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_entrega" id="delivery" value="delivery" checked>
                                    <label class="form-check-label" for="delivery">Entrega em Casa</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="tipo_entrega" id="pickup" value="pickup">
                                    <label class="form-check-label" for="pickup">Retirar na Loja</label>
                                </div>
                            </div>

                            <div id="delivery-fields">
                                <div class="mb-3">
                                    <label for="morada" class="form-label">Morada</label>
                                    <input type="text" class="form-control" id="morada" name="morada" value="<?= htmlspecialchars($user_info['morada'] ?? '') ?>" required />
                                    <div class="invalid-feedback">Por favor, insira sua morada.</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="codigo_postal" class="form-label">Código Postal</label>
                                        <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" required />
                                        <div class="invalid-feedback">Por favor, insira o código postal.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cidade" class="form-label">Cidade</label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" required />
                                        <div class="invalid-feedback">Por favor, insira sua cidade.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Método de Pagamento</h5>
                        </div>
                        <div class="card-body">
                            <div class="payment-method mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="metodo_pagamento" id="cartao" value="Cartão" checked>
                                    <label class="form-check-label d-flex align-items-center" for="cartao">
                                        <img src="/PAP/static/images/payments/credit-card.png" alt="Cartão" /> Cartão de Crédito/Débito
                                    </label>
                                </div>
                            </div>
                            <div id="cartao-fields">
                                <div class="mb-3">
                                    <label for="numero_cartao" class="form-label">Número do Cartão</label>
                                    <input type="text" class="form-control" id="numero_cartao" name="numero_cartao" 
                                           placeholder="0000 0000 0000 0000" maxlength="19"
                                           pattern="\d{4}\s\d{4}\s\d{4}\s\d{4}" 
                                           title="Digite os 16 dígitos do cartão" required />
                                    <div class="invalid-feedback">Por favor, insira um número de cartão válido (16 dígitos).</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="validade" class="form-label">Validade (MM/AA)</label>
                                        <input type="text" class="form-control" id="validade" name="validade" 
                                               placeholder="MM/AA" maxlength="5"
                                               pattern="(0[1-9]|1[0-2])\/\d{2}" 
                                               title="Digite no formato MM/AA" required />
                                        <div class="invalid-feedback">Por favor, insira uma data válida (MM/AA).</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cvv" class="form-label">CVV</label>
                                        <input type="text" class="form-control" id="cvv" name="cvv" 
                                               placeholder="000" maxlength="4"
                                               pattern="\d{3,4}" 
                                               title="Digite os 3 ou 4 dígitos do CVV" required />
                                        <div class="invalid-feedback">Por favor, insira um CVV válido (3 ou 4 dígitos).</div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="nome_cartao" class="form-label">Nome no Cartão</label>
                                    <input type="text" class="form-control" id="nome_cartao" name="nome_cartao" required />
                                    <div class="invalid-feedback">Por favor, insira o nome como aparece no cartão.</div>
                                </div>
                            </div>

                            <div class="payment-method mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="metodo_pagamento" id="mbway" value="MB WAY" />
                                    <label class="form-check-label" for="mbway">
                                        <img src="/PAP/static/images/payments/mbway.png" alt="MB WAY" /> MB WAY
                                    </label>
                                </div>
                            </div>

                            <div class="payment-method">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="metodo_pagamento" id="paypal" value="PayPal" />
                                    <label class="form-check-label" for="paypal">
                                        <img src="/PAP/static/images/payments/paypal.png" alt="PayPal" /> PayPal
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="finalizar_compra" value="1" />
                    <button type="submit" class="btn btn-primary btn-lg w-100">Finalizar Compra</button>
                </form>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Resumo do Pedido</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($produtos)): ?>
                            <div class="order-summary">
                                <?php if (count($produtos) > 1): ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <h6>Produtos:</h6>
                                            <div class="product-column">
                                                <?php foreach ($produtos as $produto): ?>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <div>
                                                            <span class="fw-bold"><?= htmlspecialchars($produto['nome']) ?></span>
                                                            <small class="text-muted d-block"><?= $produto['quantidade'] ?> × €<?= number_format($produto['preco'], 2, ',', '.') ?></small>
                                                        </div>
                                                        <div>€<?= number_format($produto['preco'] * $produto['quantidade'], 2, ',', '.') ?></div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="d-flex mb-3">
                                        <img src="/PAP/static/images/<?= htmlspecialchars($produtos[0]['imagem']) ?>" class="product-img me-3" alt="<?= htmlspecialchars($produtos[0]['nome']) ?>" />
                                        <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($produtos[0]['nome']) ?></h6>
                                            <small class="text-muted"><?= $produtos[0]['quantidade'] ?> × €<?= number_format($produtos[0]['preco'], 2, ',', '.') ?></small>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <table class="table mt-3">
                                    <tr>
                                        <td>Subtotal</td>
                                        <td class="text-end" id="subtotal">€<?= number_format($total, 2, ',', '.') ?></td>
                                    </tr>
                                    <tr>
                                        <td>Portes</td>
                                        <td class="text-end" id="portes">€5,00</td>
                                    </tr>
                                    <tr class="fw-bold">
                                        <td>Total</td>
                                        <td class="text-end" id="total">€<?= number_format($total + 5, 2, ',', '.') ?></td>
                                    </tr>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <p>Seu carrinho está vazio</p>
                                <a href="produtos.php" class="btn btn-outline-primary">Continuar Comprando</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Formatar número do cartão (grupos de 4 dígitos)
    document.getElementById('numero_cartao').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '').substring(0,16);
        let formatted = '';
        for(let i=0; i<value.length; i+=4) {
            formatted += value.substring(i, i+4) + ' ';
        }
        e.target.value = formatted.trim();
    });

    // Formatar data de validade (MM/AA)
    document.getElementById('validade').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '').substring(0,4);
        if (value.length > 2) {
            e.target.value = value.substring(0,2) + '/' + value.substring(2,4);
        } else {
            e.target.value = value;
        }
        
        // Validar mês
        if (value.length >= 2) {
            const month = parseInt(value.substring(0,2));
            if (month < 1 || month > 12) {
                e.target.setCustomValidity('Mês inválido');
            } else {
                e.target.setCustomValidity('');
            }
        }
    });

    // Validar CVV (3 ou 4 dígitos)
    document.getElementById('cvv').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '').substring(0,4);
    });

    // Mostrar/ocultar campos de entrega
    document.querySelectorAll('input[name="tipo_entrega"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const deliveryFields = document.getElementById('delivery-fields');
            if (this.value === 'delivery') {
                deliveryFields.style.display = 'block';
                deliveryFields.querySelectorAll('input').forEach(i => i.required = true);
            } else {
                deliveryFields.style.display = 'none';
                deliveryFields.querySelectorAll('input').forEach(i => i.required = false);
            }
            atualizarTotais();
        });
    });

    // Atualizar preços com base no tipo de entrega
    function atualizarTotais() {
        const tipoEntrega = document.querySelector('input[name="tipo_entrega"]:checked').value;
        const subtotal = <?= $total ?>;
        const portes = tipoEntrega === 'delivery' ? 5 : 0;
        document.getElementById('portes').textContent = '€' + portes.toFixed(2).replace('.', ',');
        document.getElementById('total').textContent = '€' + (subtotal + portes).toFixed(2).replace('.', ',');
    }

    // Habilitar validação do Bootstrap quando o formulário for enviado
    (function() {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();

    atualizarTotais();
</script>
</body>
</html>