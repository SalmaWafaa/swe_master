<?php
session_start();

// FIXED PATH
require_once __DIR__ . '/../../Model/Cart/CartModel.php';
require_once __DIR__ . '/../../Model/CartTemplate.php';

class RCartController {
    private $cartModel;

    public function __construct() {
        if (isset($_SESSION['user_id'])) {
            $this->cartModel = CartModel::getInstance($_SESSION['user_id']);
        }
    }

    public function viewCart() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /swe_master/index.php?controller=User&action=login');
            exit;
        }

        $cart = $this->cartModel->getCart();
        if (!$cart) {
            $cartItems = [];
            $total = 0;
        } else {
            $cartItems = $this->cartModel->getCartItems();
            $total = $this->cartModel->getCartTotal();
        }

        // Use the template pattern
        $template = new CartTemplate($cartItems, $total);
        $template->render();
    }
}
