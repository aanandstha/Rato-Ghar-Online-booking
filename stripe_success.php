<?php
require_once 'config/db.php';
session_start();

if (!isset($_GET['order_id']) || !isset($_GET['session_id'])) {
    header("Location: index.php");
    exit;
}

$order_id = intval($_GET['order_id']);
$session_id = $_GET['session_id'];

// Securely verify payment status with Stripe API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/checkout/sessions/' . $session_id);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, STRIPE_SECRET_KEY . ':');

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$session_data = json_decode($response, true);

if ($http_code !== 200 || !isset($session_data['payment_status']) || $session_data['payment_status'] !== 'paid') {
    // Security check failed, payment was not successful
    header("Location: stripe_cancel.php?order_id=" . $order_id);
    exit;
}

try {
    $pdo->beginTransaction();

    // Mark transaction as successful
    $stmt = $pdo->prepare("UPDATE transactions SET payment_status = 'successful' WHERE order_id = ?");
    $stmt->execute([$order_id]);

    // Mark order as confirmed
    $stmt = $pdo->prepare("UPDATE orders SET status = 'confirmed' WHERE id = ?");
    $stmt->execute([$order_id]);

    $pdo->commit();

    // Clear the cart
    unset($_SESSION['cart']);

} catch (Exception $e) {
    $pdo->rollBack();
    die("Error updating order status.");
}

require_once 'includes/header.php';
?>

<div class="container mt-5">
    <div class="alert alert-success text-center py-5">
        <i class="fas fa-check-circle fa-4x mb-3 text-success"></i>
        <h3>Payment Successful!</h3>
        <p>Thank you for your payment. Your order number is #<?= htmlspecialchars($order_id) ?>.</p>
        <a href="index.php" class="btn btn-primary mt-3">Return to Menu</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
