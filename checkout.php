<?php
require_once 'config/db.php';
require_once 'includes/header.php';

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    // Store that they were trying to checkout so we can redirect them back after login
    $_SESSION['redirect_after_login'] = 'checkout.php';
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['customer_name'] ?? '');
    $phone = trim($_POST['customer_phone'] ?? '');
    $address = trim($_POST['delivery_address'] ?? '');
    $delivery_type = $_POST['delivery_type'] ?? 'pickup';
    $payment_method = $_POST['payment_method'] ?? 'cash';

    if (empty($name) || empty($phone) || ($delivery_type === 'delivery' && empty($address))) {
        $error = 'Please fill in all required fields.';
    } else {
        try {
            $pdo->beginTransaction();

            // Calculate total
            $total_amount = 0;
            $items = [];
            $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
            $stmt = $pdo->query("SELECT * FROM menu_items WHERE id IN ($ids)");
            while ($row = $stmt->fetch()) {
                $qty = $_SESSION['cart'][$row->id];
                $total_amount += ($row->price * $qty);
                $items[] = ['id' => $row->id, 'qty' => $qty, 'price' => $row->price];
            }

            // Create order
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status, delivery_type, delivery_address, customer_name, customer_phone) VALUES (?, ?, 'pending', ?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $total_amount, $delivery_type, $address, $name, $phone]);
            $order_id = $pdo->lastInsertId();

            // Insert items
            $stmt_items = $pdo->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($items as $item) {
                $stmt_items->execute([$order_id, $item['id'], $item['qty'], $item['price']]);
            }

            // Create transaction (Simulated)
            $payment_status = ($payment_method === 'card') ? 'successful' : 'pending'; // Simulate instant card payment success
            $stmt_txn = $pdo->prepare("INSERT INTO transactions (order_id, payment_method, payment_status) VALUES (?, ?, ?)");
            $stmt_txn->execute([$order_id, $payment_method, $payment_status]);

            $pdo->commit();

            // Clear cart
            unset($_SESSION['cart']);
            $success = "Order placed successfully! Your order number is #$order_id.";

        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Failed to process order. Please try again.";
        }
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Checkout</h2>

    <?php if ($success): ?>
        <div class="alert alert-success text-center py-5">
            <i class="fas fa-check-circle fa-4x mb-3 text-success"></i>
            <h3><?= $success ?></h3>
            <p>Thank you for choosing Rato Ghar!</p>
            <a href="index.php" class="btn btn-primary mt-3">Return to Menu</a>
        </div>
    <?php else: ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="checkout.php" method="POST">
            <div class="row">
                <div class="col-lg-7">
                    <div class="card mb-4">
                        <div class="card-header bg-white font-weight-bold">Delivery Details</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Full Name *</label>
                                    <input type="text" name="customer_name" class="form-control" required value="<?= htmlspecialchars($_POST['customer_name'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Phone Number *</label>
                                    <input type="text" name="customer_phone" class="form-control" required value="<?= htmlspecialchars($_POST['customer_phone'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="d-block">Order Type *</label>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="typePickup" name="delivery_type" value="pickup" class="custom-control-input" checked onchange="document.getElementById('addressField').style.display='none'">
                                    <label class="custom-control-label" for="typePickup">Pickup</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="typeDelivery" name="delivery_type" value="delivery" class="custom-control-input" onchange="document.getElementById('addressField').style.display='block'">
                                    <label class="custom-control-label" for="typeDelivery">Delivery</label>
                                </div>
                            </div>

                            <div class="mb-3" id="addressField" style="display: none;">
                                <label>Delivery Address *</label>
                                <textarea name="delivery_address" class="form-control" rows="3"><?= htmlspecialchars($_POST['delivery_address'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-white font-weight-bold">Payment Method</div>
                        <div class="card-body">
                            <div class="custom-control custom-radio mb-2">
                                <input type="radio" id="payCash" name="payment_method" value="cash" class="custom-control-input" checked>
                                <label class="custom-control-label" for="payCash">Cash / Card on Delivery</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="payCard" name="payment_method" value="card" class="custom-control-input">
                                <label class="custom-control-label" for="payCard">Credit Card (Simulated)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card bg-light sticky-top" style="top: 100px;">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Review Order</h4>
                            <?php
                            $total = 0;
                            $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
                            $stmt = $pdo->query("SELECT * FROM menu_items WHERE id IN ($ids)");
                            while ($row = $stmt->fetch()) {
                                $qty = $_SESSION['cart'][$row->id];
                                $sub = $row->price * $qty;
                                $total += $sub;
                                echo "<div class='d-flex justify-content-between mb-2'>";
                                echo "<span>{$row->name} x $qty</span>";
                                echo "<span>$" . number_format($sub, 2) . "</span>";
                                echo "</div>";
                            }
                            ?>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <strong>Total to Pay</strong>
                                <strong class="price-tag text-danger">$<?= number_format($total, 2) ?></strong>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block btn-lg">Place Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
