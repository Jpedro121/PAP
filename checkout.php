<?php
session_start();
require_once("db.php");

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

// Buscar dados do utilizador
$stmt = $conn->prepare("SELECT username, email, morada FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_info = $result->fetch_assoc();

function buscarProdutosCarrinho($conn, $user_id, &$produtos, &$total) {
    $stmt = $conn->prepare("SELECT c.id, p.id as produto_id, p.nome, p.imagem, c.quantidade, c.preco 
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

// Processar checkout
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
            $stmt = $conn->prepare("INSERT INTO encomenda_produtos (encomenda_id, produto_id, quantidade, preco_unitario) 
                                    VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $encomenda_id, $produto['produto_id'], $produto['quantidade'], $produto['preco']);
            $stmt->execute();
        }

        $stmt = $conn->prepare("DELETE FROM carrinho WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $conn->commit();

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
                <form method="POST" action="checkout.php" novalidate>
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
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="codigo_postal" class="form-label">Código Postal</label>
                                        <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" required />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cidade" class="form-label">Cidade</label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" required />
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
                                    <label class="form-check-label" for="cartao">
                                        <img src="/PAP/static/images/payments/credit-card.png" alt="Cartão" /> Cartão de Crédito/Débito
                                    </label>
                                </div>
                            </div>
                            <div id="cartao-fields">
                                <div class="mb-3">
                                    <label for="numero_cartao" class="form-label">Número do Cartão</label>
                                    <input type="text" class="form-control" id="numero_cartao" name="numero_cartao" placeholder="0000 0000 0000 0000" />
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="validade" class="form-label">Validade (MM/AA)</label>
                                        <input type="text" class="form-control" id="validade" name="validade" placeholder="MM/AA" />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cvv" class="form-label">CVV</label>
                                        <input type="text" class="form-control" id="cvv" name="cvv" placeholder="000" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="nome_cartao" class="form-label">Nome no Cartão</label>
                                    <input type="text" class="form-control" id="nome_cartao" name="nome_cartao" />
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
                           <ul class="list-unstyled mb-4">
                                <?php foreach ($produtos as $produto): ?>
                                    <li class="d-flex mb-3">
                                        <img src="/<?= htmlspecialchars($produto['imagem']) ?>" class="product-img me-3" alt="<?= htmlspecialchars($produto['nome']) ?>" />
                                    <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($produto['nome']) ?></h6>
                                            <small class="text-muted"><?= $produto['quantidade'] ?> × €<?= number_format($produto['preco'], 2, ',', '.') ?></small>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                            <table class="table">
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

    // Formatar número do cartão
    const numeroCartaoInput = document.getElementById('numero_cartao');
    if(numeroCartaoInput) {
        numeroCartaoInput.addEventListener('input', e => {
        let value = e.target.value.replace(/\D/g, '').substring(0,16);
        let formatted = '';
        for(let i=0; i<value.length; i+=4){
        formatted += value.substring(i,i+4) + ' ';
        }
        e.target.value = formatted.trim();
        });
    }
// Atualizar preços com base no tipo de entrega
function atualizarTotais(){
    const tipoEntrega = document.querySelector('input[name="tipo_entrega"]:checked').value;
    const subtotal = <?= $total ?>;
    const portes = tipoEntrega === 'delivery' ? 5 : 0;
    document.getElementById('portes').textContent = '€' + portes.toFixed(2).replace('.', ',');
    document.getElementById('total').textContent = '€' + (subtotal + portes).toFixed(2).replace('.', ',');
}

atualizarTotais();
</script>
</body>
</html>