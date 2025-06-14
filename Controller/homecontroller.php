<?php
require_once __DIR__ . '/../Model/product.php';

class HomeController {
    public function index() {
        $products = Product::getAllProducts();
        require_once __DIR__ . '/../View/home.php';

    }
}    //echo "Hello";

?>
