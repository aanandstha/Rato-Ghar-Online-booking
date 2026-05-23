<?php
require_once 'config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header("Location: index.php");
    exit;
}

$order_id = intval($_GET['order_id']);
$user_id = $_SESSION['user_id'];

// Verify the order belongs to the user and is pending
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ? AND status = 'pending'");
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    die("Invalid order.");
}

// Convert amount to cents for Stripe
$amount_in_cents = intval($order->total_amount * 100);

// Base URL for success/cancel redirects
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$base_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

$success_url = $base_url . "/stripe_success.php?session_id={CHECKOUT_SESSION_ID}&order_id=" . $order_id;
$cancel_url = $base_url . "/stripe_cancel.php?order_id=" . $order_id;

// Prepare data for Stripe API (x-www-form-urlencoded)
$stripe_data = http_build_query([
    'payment_method_types' => ['card'],
    'line_items' => [
        [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Order #' . $order_id . ' from Rato Ghar',
                ],
                'unit_amount' => $amount_in_cents,
            ],
            'quantity' => 1,
        ],
    ],
    'mode' => 'payment',
    'success_url' => $success_url,
    'cancel_url' => $cancel_url,
]);

// Call Stripe API using cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/checkout/sessions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $stripe_data);
curl_setopt($ch, CURLOPT_USERPWD, STRIPE_SECRET_KEY . ':');

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

if ($http_code === 200 && isset($result['url'])) {
    // Redirect to Stripe Checkout
    header("Location: " . $result['url']);
    exit;
} else {
    // Show error
    die("Stripe API Error: " . (isset($result['error']['message']) ? $result['error']['message'] : 'Unknown error'));
}
?>
