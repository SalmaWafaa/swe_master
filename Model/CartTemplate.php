<?php
require_once __DIR__ . '/BaseTemplate.php';

class CartTemplate extends BaseTemplate {
    private $cartItems;
    private $total;

    public function __construct($cartItems, $total) {
        $this->cartItems = $cartItems;
        $this->total = $total;
    }

    protected function loadContent() {
        $cartPath = __DIR__ . '/../View/Cart/CartView.php';
        if (file_exists($cartPath)) {
            include_once $cartPath;
        }
    }

    protected function loadHeader() {
        $title = "Shopping Cart";
        $headerPath = __DIR__ . '/../View/User/header.php';
        if (file_exists($headerPath)) {
            include_once $headerPath;
        }
    }

    protected function initialize() {
        parent::initialize();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /swe_master/index.php?controller=User&action=login');
            exit;
        }
    }
} 