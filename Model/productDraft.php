<?php
class Product {
    public static function getAllProducts() {
        return [
            ["name" => "Product 1", "price" => "$20", "image" => "product1.jpg"],
            ["name" => "Product 2", "price" => "$30", "image" => "product2.jpg"],
            ["name" => "Product 3", "price" => "$40", "image" => "product3.jpg"]
        ];
    }
}
?>
