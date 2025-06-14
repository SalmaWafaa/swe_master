<?php
session_start();
require_once 'cartmodelll.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add to cart.");
}

$userId = $_SESSION['user_id'];
$productId = $_GET['product_id'];

$cartModel = new CartModel();
$cartId = $cartModel->getCartIdByUserId($userId);

if (!$cartId) {
    $cartId = $cartModel->createCart($userId);
}

$cartModel->addItemToCart($cartId, $productId, 1);

header('Location: view.php');
exit;
?>
