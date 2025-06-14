<?php
session_start();

// FIXED PATH
require_once __DIR__ . '/../../Model/Cart/CartModel.php';

class RCartController {
    public function viewCart() {
        if (!isset($_SESSION['user_id'])) {
            die("You must be logged in to view your cart.");
        }

        $userId = $_SESSION['user_id'];
        $cartModel = CartModel::getInstance($userId);
        $cartItems = $cartModel->getCartItems();

        require_once __DIR__ . '/../../View/Cart/CartView.php';

    }
}
