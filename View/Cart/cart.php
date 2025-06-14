<?php

require_once __DIR__. '/../Controller/CartController.php';
require_once __DIR__.'/../View/CartView.php';

if (!isset($_SESSION['customer_id'])) {
    $_SESSION['customer_id'] ; // Example: Assign a dummy customer ID for testing
}

$cartController = new CartController();
$cartView = new CartView($cartController);

$cartView->renderCart();

?>