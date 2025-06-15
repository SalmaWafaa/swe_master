<?php
session_start();
require_once __DIR__ . '/../../Model/Cart/CartModel.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = 'Please log in to remove items from your cart';
    header('Location: /swe_master/index.php?controller=User&action=login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error_message'] = 'Invalid request method';
    header('Location: /swe_master/index.php?controller=RCart&action=viewCart');
    exit;
}

$itemId = $_POST['item_id'] ?? null;

if (!$itemId) {
    $_SESSION['error_message'] = 'Missing item ID';
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

    if ($cartModel->removeItemFromCart($cart['cart_id'], $itemId)) {
        $_SESSION['success_message'] = 'Item removed from cart successfully';
    } else {
        $_SESSION['error_message'] = 'Failed to remove item from cart';
    }
} catch (Exception $e) {
    $_SESSION['error_message'] = 'An error occurred while removing the item from cart';
}

// Always redirect to cart page after removing an item
header('Location: /swe_master/index.php?controller=RCart&action=viewCart');
exit; 