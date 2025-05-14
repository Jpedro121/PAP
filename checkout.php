<?php
session_start();
require_once("db.php"); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit();
}

// Inicializar variáveis
$produtos = [];
$total = 0;
$user_info = [];
$mensagem_erro = '';
$mensagem_sucesso = '';

// Obter informações do usuário
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, morada FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_info = $result->fetch_assoc();

// Processar o formulário de checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalizar_compra'])) {
    try {
        // Validar dados do formulário
        $tipo_entrega = $_POST['tipo_entrega'] ?? 'delivery';
        $metodo_pagamento = $_POST['metodo_pagamento'] ?? '';
        $morada = ($tipo_entrega === 'delivery') ? ($_POST['morada'] ?? '') : 'Retirada na loja';
        
        if ($tipo_entrega === 'delivery' && (empty($morada) || empty($_POST['codigo_postal']) || empty($_POST['cidade']))) {
            throw new Exception("Por favor, preencha todos os campos de entrega");
        }

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

        if (empty($produtos)) {
            throw new Exception("Seu carrinho está vazio");
        }

        // Adicionar custo de entrega
        $portes = ($tipo_entrega === 'delivery') ? 5.00 : 0.00;
        $total_com_portes = $total + $portes;

        // Iniciar transação
        $conn->begin_transaction();

        // Criar encomenda
        $codigo_encomenda = 'EN' . strtoupper(uniqid());
        $stmt = $conn->prepare("INSERT INTO encomendas (user_id, total, morada, codigo_encomenda, metodo_pagamento, tipo_entrega) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("idssss", $user_id, $total_com_portes, $morada, $codigo_encomenda, $metodo_pagamento, $tipo_entrega);
        $stmt->execute();
        $encomenda_id = $conn->insert_id;

        // Adicionar itens à encomenda
        foreach ($produtos as $produto) {
            $stmt = $conn->prepare("INSERT INTO encomenda_produtos (encomenda_id, produto_id, quantidade, preco_unitario) 
                                  VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $encomenda_id, $produto['produto_id'], $produto['quantidade'], $produto['preco']);
            $stmt->execute();
        }

        // Limpar carrinho
        $stmt = $conn->prepare("DELETE FROM carrinho WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Confirmar transação
        $conn->commit();

        $mensagem_sucesso = "Compra realizada com sucesso! Código da encomenda: $codigo_encomenda";
        $produtos = []; // Limpar carrinho após sucesso
        $total = 0;
        
    } catch (Exception $e) {
        $conn->rollback();
        $mensagem_erro = "Erro ao processar pedido: " . $e->getMessage();
    }
} else {
    // Se não for POST, carregar itens do carrinho normalmente
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
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - SkateShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Finalizar Compra</h1>
        
        <?php if ($mensagem_sucesso): ?>
            <div class="alert alert-success"><?= $mensagem_sucesso ?></div>
            <a href="produtos.php" class="btn btn-primary">Voltar às Compras</a>
        <?php else: ?>
            <?php if ($mensagem_erro): ?>
                <div class="alert alert-danger"><?= $mensagem_erro ?></div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-8">
                    <form method="POST" action="checkout.php">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Informações de Entrega</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipo_entrega" id="delivery" value="delivery" checked>
                                        <label class="form-check-label" for="delivery">
                                            Entrega em Casa
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipo_entrega" id="pickup" value="pickup">
                                        <label class="form-check-label" for="pickup">
                                            Retirar na Loja
                                        </label>
                                    </div>
                                </div>
                                
                                <div id="delivery-fields">
                                    <div class="mb-3">
                                        <label for="morada" class="form-label">Morada</label>
                                        <input type="text" class="form-control" id="morada" name="morada" 
                                               value="<?= htmlspecialchars($user_info['morada'] ?? '') ?>" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="codigo_postal" class="form-label">Código Postal</label>
                                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cidade" class="form-label">Cidade</label>
                                            <input type="text" class="form-control" id="cidade" name="cidade" required>
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
                                            <img src="/static/images/payment/credit-card.png" alt="Cartão"> Cartão de Crédito/Débito
                                        </label>
                                    </div>
                                </div>
                                
                                <div id="cartao-fields">
                                    <div class="mb-3">
                                        <label for="numero_cartao" class="form-label">Número do Cartão</label>
                                        <input type="text" class="form-control" id="numero_cartao" name="numero_cartao" placeholder="0000 0000 0000 0000">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="validade" class="form-label">Validade (MM/AA)</label>
                                            <input type="text" class="form-control" id="validade" name="validade" placeholder="MM/AA">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cvv" class="form-label">CVV</label>
                                            <input type="text" class="form-control" id="cvv" name="cvv" placeholder="000">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nome_cartao" class="form-label">Nome no Cartão</label>
                                        <input type="text" class="form-control" id="nome_cartao" name="nome_cartao">
                                    </div>
                                </div>
                                
                                <div class="payment-method mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="metodo_pagamento" id="mbway" value="MB WAY">
                                        <label class="form-check-label" for="mbway">
                                            <img src="/static/images/payment/mbway.png" alt="MB WAY"> MB WAY
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="payment-method">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="metodo_pagamento" id="paypal" value="PayPal">
                                        <label class="form-check-label" for="paypal">
                                            <img src="/static/images/payment/paypal.png" alt="PayPal"> PayPal
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="finalizar_compra" value="1">
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
                                            <img src="/static/images/products/<?= htmlspecialchars(basename($produto['imagem'])) ?>" 
                                                 class="product-img me-3" alt="<?= htmlspecialchars($produto['nome']) ?>">
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
                                        <td class="text-end">€<?= number_format($total, 2, ',', '.') ?></td>
                                    </tr>
                                    <tr>
                                        <td>Portes</td>
                                        <td class="text-end">€5,00</td>
                                    </tr>
                                    <tr class="fw-bold">
                                        <td>Total</td>
                                        <td class="text-end">€<?= number_format($total + 5, 2, ',', '.') ?></td>
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
                document.getElementById('delivery-fields').style.display = 
                    this.value === 'delivery' ? 'block' : 'none';
                
                // Atualizar campos obrigatórios
                const fields = document.querySelectorAll('#delivery-fields input');
                fields.forEach(field => {
                    field.required = this.value === 'delivery';
                });
            });
        });

        // Formatar número do cartão
        document.getElementById('numero_cartao').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '');
            if (value.length > 0) {
                value = value.match(/.{1,4}/g).join(' ');
            }
            e.target.value = value;
        });

        // Formatar data de validade
        document.getElementById('validade').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    </script>
</body>
</html>