<?php
session_start();
require_once '../../Model/Cart/CartModel.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add to cart.");
}

$userId = $_SESSION['user_id'];
$productId = $_GET['product_id'];

$cartModel = CartModel::getInstance($userId);
$cartId = $cartModel->getCartIdByUserId($userId);

if (!$cartId) {
    $cartId = $cartModel->createCart($userId);
}

$cartModel->addItemToCart($cartId, $productId, 1);

header('Location: /ecommerce_master/View/Cart/CartView.php');
exit;
?>
