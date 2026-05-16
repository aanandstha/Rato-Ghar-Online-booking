<?php
require_once 'config/db.php';
require_once 'includes/header.php';

$cart_items = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
    $stmt = $pdo->query("SELECT * FROM menu_items WHERE id IN ($ids)");
    while ($row = $stmt->fetch()) {
        $qty = $_SESSION['cart'][$row->id];
        $row->quantity = $qty;
        $row->subtotal = $row->price * $qty;
        $total_price += $row->subtotal;
        $cart_items[] = $row;
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Your Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <div class="alert alert-info">Your cart is currently empty. <a href="index.php">Browse Menu</a></div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-rato text-white">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= htmlspecialchars($item->image_url ?: 'https://via.placeholder.com/50') ?>" alt="<?= htmlspecialchars($item->name) ?>" class="img-thumbnail mr-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            <strong><?= htmlspecialchars($item->name) ?></strong>
                                        </div>
                                    </td>
                                    <td>$<?= number_format($item->price, 2) ?></td>
                                    <td>
                                        <form action="cart_action.php" method="POST" class="d-flex align-items-center">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="item_id" value="<?= $item->id ?>">
                                            <input type="number" name="quantity" value="<?= $item->quantity ?>" min="1" class="form-control form-control-sm mr-2" style="width: 70px;">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="fas fa-sync-alt"></i></button>
                                        </form>
                                    </td>
                                    <td><strong>$<?= number_format($item->subtotal, 2) ?></strong></td>
                                    <td>
                                        <form action="cart_action.php" method="POST">
                                            <input type="hidden" name="action" value="remove">
                                            <input type="hidden" name="item_id" value="<?= $item->id ?>">
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Order Summary</h4>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <span>$<?= number_format($total_price, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Delivery/Tax</span>
                            <span class="text-muted">Calculated at checkout</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total</strong>
                            <strong class="price-tag">$<?= number_format($total_price, 2) ?></strong>
                        </div>
                        <a href="checkout.php" class="btn btn-primary btn-block btn-lg">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
