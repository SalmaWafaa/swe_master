<?php
session_start();
require_once __DIR__ . '/../../Model/Cart/CartModel.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = 'You must be logged in to add items to your cart';
    header('Location: /swe_master/index.php?controller=User&action=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error_message'] = 'Invalid request method';
    header('Location: /swe_master/index.php?controller=RCart&action=viewCart');
    exit;
}

$productId = $_POST['product_id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;
$size = $_POST['size'] ?? null;
$color = $_POST['color'] ?? null;

if (!$productId) {
    $_SESSION['error_message'] = 'Missing product ID';
    header('Location: /swe_master/index.php?controller=RCart&action=viewCart');
    exit;
}

try {
    $cartModel = CartModel::getInstance($_SESSION['user_id']);
    
    if ($cartModel->addItemToCart($productId, $quantity, $size, $color)) {
        $_SESSION['success_message'] = 'Item added to cart successfully';
    } else {
        $_SESSION['error_message'] = 'Failed to add item to cart';
    }
} catch (Exception $e) {
    $_SESSION['error_message'] = 'Error: ' . $e->getMessage();
}

// Always redirect to cart page after adding an item
header('Location: /swe_master/index.php?controller=RCart&action=viewCart');
exit;
?>
