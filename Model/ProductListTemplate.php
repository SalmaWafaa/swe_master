<?php
require_once __DIR__ . '/BaseTemplate.php';

class ProductListTemplate extends BaseTemplate {
    private $products;
    private $category;

    public function __construct($products, $category = null) {
        $this->products = $products;
        $this->category = $category;
    }

    protected function loadContent() {
        $viewPath = __DIR__ . '/../View/categories/subcategory_products.php';
        if (file_exists($viewPath)) {
            include_once $viewPath;
        }
    }

    protected function loadHeader() {
        $title = $this->category ? "Products - " . $this->category['name'] : "All Products";
        $headerPath = __DIR__ . '/../View/User/header.php';
        if (file_exists($headerPath)) {
            include_once $headerPath;
        }
    }
} 