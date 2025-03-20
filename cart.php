<?php
session_start();
require 'db.php'; 

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['produto_id'])) {
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'] ?? 1;
    
    if (isset($_SESSION['cart'][$produto_id])) {
        $_SESSION['cart'][$produto_id] += $quantidade;
    } else {
        $_SESSION['cart'][$produto_id] = $quantidade;
    }
    header("Location: cart.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantidade'] as $id => $quantidade) {
        if ($quantidade <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = $quantidade;
        }
    }
}

if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}

$cart_items = [];
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $query = "SELECT * FROM produtos WHERE id IN ($ids)";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $row['quantity'] = $_SESSION['cart'][$row['id']];
        $cart_items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <?php include('head.html'); ?>     
    <title>Carrinho de compras</title>
</head>
<body>
<?php include('header.php'); ?>
    <h2>Seu Carrinho</h2>
    
    <?php if (empty($cart_items)): ?>
        <p>Seu carrinho está vazio.</p>
    <?php else: ?>
        <form method="POST">
            <table>
                <tr>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th>Ações</th>
                </tr>
                <?php $total = 0; ?>
                <?php foreach ($cart_items as $item): ?>
                    <?php $subtotal = $item['preco'] * $item['quantity']; ?>
                    <tr>
                        <td><?php echo $item['nome']; ?></td>
                        <td>€<?php echo number_format($item['preco'], 2); ?></td>
                        <td>
                            <input type="number" name="quantidade[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1">
                        </td>
                        <td>€<?php echo number_format($subtotal, 2); ?></td>
                        <td><a href="cart.php?remove=<?php echo $item['id']; ?>">Remover</a></td>
                    </tr>
                    <?php $total += $subtotal; ?>
                <?php endforeach; ?>
            </table>
            <p><strong>Total: €<?php echo number_format($total, 2); ?></strong></p>
            <button type="submit" name="update_cart">Atualizar Carrinho</button>
            <a href="checkout.php">Finalizar Compra</a>
        </form>
    <?php endif; ?>
</body>
</html>
