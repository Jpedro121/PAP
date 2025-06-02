<?php
session_start();
if (!isset($_SESSION["username"]) || !isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
require '../db.php';
$user_id = $_SESSION["user_id"];

$sql = "SELECT username, email, morada, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $username = $data['username'];
    $email = $data['email'];
    $address = $data['morada'];
    $role = $data['role'];
} else {
    $username = "Unknown";
    $email = "Not available";
    $address = "";
    $role = "user";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile</title>
    <?php include('../head.html'); ?>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        .perfil-container {
            background-color: #fff;
            max-width: 900px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid #ccc;
            margin-bottom: 20px;
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            border-bottom: 3px solid transparent;
            background-color: transparent;
            font-weight: bold;
        }

        .tab.active {
            border-bottom: 3px solid #007bff;
            color: #007bff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        form {
            margin-top: 15px;
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }

        input[type="text"], input[type="password"], textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .btn {
            margin-top: 15px;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-admin {
            background-color: #dc3545;
        }

        .btn-admin:hover {
            background-color: #c82333;
        }

        .btn-group {
            text-align: center;
            margin-top: 30px;
        }

        .order-item {
            background-color: #f9f9f9;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 6px;
        }
        .encomenda {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 15px;
        }

        .info-principal {
            width: 200px;
            margin-right: 20px;
            background: #eee;
            padding: 10px;
            border-radius: 8px;
        }

        .detalhes {
            background: #f9f9f9;
            padding: 10px;
            border-radius: 8px;
            flex: 1;
            display: none;
        }

        .produto {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .produto img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            margin-right: 10px;
            border-radius: 5px;
        }

        .btn-ver {
            margin-top: 10px;
            padding: 6px 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-ver:hover {
            background-color: #218838;
        }
    </style>
</head>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });
});

function toggleDetails(id) {
    const panel = document.getElementById("details_" + id);
    panel.style.display = (panel.style.display === "none" || panel.style.display === "") ? "block" : "none";
}
</script>
<body>
<?php include('../header.php'); ?>
<div class="perfil-container">
    <h2>Hello, <?php echo htmlspecialchars($username); ?></h2>

    <div class="tabs">
        <button class="tab active" data-tab="info">Information</button>
        <button class="tab" data-tab="security">Security</button>
        <button class="tab" data-tab="orders">Orders</button>
    </div>

    <div id="info" class="tab-content active">
        <h3>Edit Username</h3>
        <form action="update_profile.php" method="post">
            <label for="novo_username">New Name:</label>
            <input type="text" name="novo_username" id="novo_username" value="<?php echo htmlspecialchars($username); ?>" required>
            <button type="submit" class="btn">Update Name</button>
        </form>

        <h3>Email</h3>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>

        <h3>Address</h3>
        <form action="update_address.php" method="post">
            <label for="morada">Current Address:</label>
            <textarea name="morada" id="morada" rows="3" required><?php echo htmlspecialchars($address); ?></textarea>
            <button type="submit" class="btn">Update Address</button>
        </form>
    </div>

    <div id="security" class="tab-content">
        <h3>Change Password</h3>
        <form action="update_password.php" method="post">
            <label for="password_atual">Current Password:</label>
            <input type="password" name="password_atual" id="password_atual" required>
            <label for="nova_password">New Password:</label>
            <input type="password" name="nova_password" id="nova_password" required>
            <button type="submit" class="btn">Change Password</button>
        </form>
    </div>

    <div id="orders" class="tab-content">
        <h3>Your Orders</h3>
        <?php
        try {
            $query = "SELECT id, data_encomenda, total FROM encomendas WHERE user_id = ? ORDER BY data_encomenda DESC";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows > 0) {
                while ($order = $res->fetch_assoc()) {
                    $id = $order['id'];
                    echo "<div class='encomenda'>";
                    echo "<div class='info-principal'>";
                    echo "<strong>ORD-{$id}</strong><br>";
                    echo "Date: " . htmlspecialchars($order['data_encomenda']) . "<br>";
                    echo "Total: " . htmlspecialchars($order['total']) . "€<br>";
                    echo "<button class='btn-ver' onclick='toggleDetails($id)'>View details</button>";
                    echo "</div>";

                    echo "<div class='detalhes' id='details_$id'>";

                    $query_products = "SELECT p.nome, p.imagem, ep.quantidade 
                                    FROM encomenda_produtos ep 
                                    JOIN produtos p ON ep.produto_id = p.id 
                                    WHERE ep.encomenda_id = ?";
                    $stmtProducts = $conn->prepare($query_products);
                    $stmtProducts->bind_param("i", $id);
                    $stmtProducts->execute();
                    $products = $stmtProducts->get_result();

                    if ($products->num_rows > 0) {
                        while ($product = $products->fetch_assoc()) {
                            echo "<div class='produto'>";
                            echo "<img src='/PAP/static/images/" . htmlspecialchars($product['imagem']) . "' alt='Product'>";
                            echo "<div><strong>" . htmlspecialchars($product['nome']) . "</strong><br>Quantity: " . htmlspecialchars($product['quantidade']) . "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No products in this order.</p>";
                    }

                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>You have no orders yet.</p>";
            }
        } catch (mysqli_sql_exception $e) {
            echo "<p>Error loading orders: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>

    <div class="btn-group">
        <a href="../home.php" class="btn">Home</a>
        <?php if ($role === 'admin'): ?>
            <a href="../dashboard_admin.php" class="btn btn-admin">Admin Dashboard</a>
        <?php endif; ?>
        <a href="logout.php" class="btn">Log Out</a>
    </div>
</div>
</body>
</html>
