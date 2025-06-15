<?php
session_start();
require_once __DIR__ . '/../../Model/Cart/CartModel.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = 'Please log in to update your cart';
    header('Location: /swe_master/index.php?controller=User&action=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error_message'] = 'Invalid request method';
    header('Location: /swe_master/index.php?controller=RCart&action=viewCart');
    exit;
}

$itemId = $_POST['item_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$itemId || !$action) {
    $_SESSION['error_message'] = 'Missing required parameters';
    header('Location: /swe_master/index.php?controller=RCart&action=viewCart');
    exit;
}

try {
    $cartModel = CartModel::getInstance($_SESSION['user_id']);
    $cart = $cartModel->getCart();
    
    if (!$cart) {
        $_SESSION['error_message'] = 'Cart not found';
        header('Location: /swe_master/index.php?controller=RCart&action=viewCart');
        exit;
    }

    // Get current quantity
    $currentQuantity = $cartModel->getItemQuantity($cart['cart_id'], $itemId);
    
    // Calculate new quantity based on action
    $newQuantity = $action === 'increase' ? $currentQuantity + 1 : max(1, $currentQuantity - 1);

    if ($cartModel->updateItemQuantity($cart['cart_id'], $itemId, $newQuantity)) {
        $_SESSION['success_message'] = 'Cart updated successfully';
    } else {
        $_SESSION['error_message'] = 'Failed to update cart';
    }
} catch (Exception $e) {
    $_SESSION['error_message'] = 'An error occurred while updating the cart';
}

// Always redirect to cart page after updating
header('Location: /swe_master/index.php?controller=RCart&action=viewCart');
exit; 