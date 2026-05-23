<?php
require_once 'config/db.php';
session_start();

if (!isset($_GET['order_id'])) {
    header("Location: index.php");
    exit;
}

$order_id = intval($_GET['order_id']);

try {
    $pdo->beginTransaction();

    // Mark transaction as failed
    $stmt = $pdo->prepare("UPDATE transactions SET payment_status = 'failed' WHERE order_id = ?");
    $stmt->execute([$order_id]);

    // Mark order as cancelled
    $stmt = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
    $stmt->execute([$order_id]);

    $pdo->commit();

} catch (Exception $e) {
    $pdo->rollBack();
    die("Error updating order status.");
}

require_once 'includes/header.php';
?>

<div class="container mt-5">
    <div class="alert alert-danger text-center py-5">
        <i class="fas fa-times-circle fa-4x mb-3 text-danger"></i>
        <h3>Payment Cancelled</h3>
        <p>Your payment was cancelled or failed. Your order #<?= htmlspecialchars($order_id) ?> has been cancelled.</p>
        <a href="checkout.php" class="btn btn-primary mt-3">Try Again</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
