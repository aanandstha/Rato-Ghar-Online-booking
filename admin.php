<?php
require_once 'config/db.php';
require_once 'includes/header.php';

// Ensure user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];
    
    // Validate status
    $valid_statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
    if (in_array($status, $valid_statuses)) {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $order_id]);
        $success = "Order #$order_id status updated to $status.";
    }
}

// Fetch all orders
$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll();
?>

<div class="container-fluid mt-5 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: var(--rato-dark);">Admin Dashboard</h2>
        <div>
            <span class="badge badge-primary p-2">Total Orders: <?= count($orders) ?></span>
        </div>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $success ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-5">
        <div class="card-header bg-white font-weight-bold">Recent Orders</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-rato text-white">
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Type</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><strong>#<?= $order->id ?></strong></td>
                                <td><?= date('M j, Y g:i A', strtotime($order->created_at)) ?></td>
                                <td>
                                    <?= htmlspecialchars($order->customer_name) ?><br>
                                    <small class="text-muted"><?= htmlspecialchars($order->customer_phone) ?></small>
                                </td>
                                <td>
                                    <?php if($order->delivery_type == 'delivery'): ?>
                                        <span class="badge badge-info">Delivery</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Pickup</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong>$<?= number_format($order->total_amount, 2) ?></strong></td>
                                <td>
                                    <?php
                                        $badge_class = 'secondary';
                                        if($order->status == 'pending') $badge_class = 'warning text-dark';
                                        if($order->status == 'confirmed') $badge_class = 'primary';
                                        if($order->status == 'completed') $badge_class = 'success';
                                        if($order->status == 'cancelled') $badge_class = 'danger';
                                    ?>
                                    <span class="badge badge-<?= $badge_class ?> p-2 text-uppercase"><?= $order->status ?></span>
                                </td>
                                <td>
                                    <form action="admin.php" method="POST" class="d-flex align-items-center">
                                        <button type="button" class="btn btn-sm btn-info mr-2" data-toggle="modal" data-target="#orderModal<?= $order->id ?>" style="padding: 4px 10px;">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        <input type="hidden" name="order_id" value="<?= $order->id ?>">
                                        <select name="status" class="form-control form-control-sm mr-2" style="width: 130px;">
                                            <option value="pending" <?= $order->status == 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="confirmed" <?= $order->status == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                            <option value="completed" <?= $order->status == 'completed' ? 'selected' : '' ?>>Completed</option>
                                            <option value="cancelled" <?= $order->status == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(empty($orders)): ?>
                            <tr><td colspan="7" class="text-center py-4">No orders found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Order Modals -->
<?php foreach ($orders as $order): ?>
    <?php
    // Fetch items for this order
    $stmt_items = $pdo->prepare("
        SELECT oi.*, mi.name 
        FROM order_items oi 
        JOIN menu_items mi ON oi.menu_item_id = mi.id 
        WHERE oi.order_id = ?
    ");
    $stmt_items->execute([$order->id]);
    $order_items = $stmt_items->fetchAll();
    ?>
    <div class="modal fade" id="orderModal<?= $order->id ?>" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel<?= $order->id ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header bg-rato text-white">
                    <h5 class="modal-title" id="orderModalLabel<?= $order->id ?>">Order Details #<?= $order->id ?></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="p-3 bg-light border-bottom">
                        <strong>Customer:</strong> <?= htmlspecialchars($order->customer_name) ?><br>
                        <strong>Phone:</strong> <?= htmlspecialchars($order->customer_phone) ?><br>
                        <?php if($order->delivery_type === 'delivery'): ?>
                            <strong>Address:</strong> <?= nl2br(htmlspecialchars($order->delivery_address)) ?>
                        <?php endif; ?>
                    </div>
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($order_items as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item->name) ?></td>
                                    <td><?= $item->quantity ?></td>
                                    <td>$<?= number_format($item->price, 2) ?></td>
                                    <td>$<?= number_format($item->price * $item->quantity, 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="p-3 bg-light text-right border-top">
                        <h5 class="mb-0">Total: <span class="text-danger">$<?= number_format($order->total_amount, 2) ?></span></h5>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-white">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php require_once 'includes/footer.php'; ?>
