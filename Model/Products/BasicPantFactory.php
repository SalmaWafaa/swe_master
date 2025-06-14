<?php

require_once __DIR__ . '/ProductFactory.php';
require_once __DIR__ . '/Product.php';

class BasicPantFactory extends ProductFactory {
    public function createProduct($data) {
        $product = new Product();
        $product->name = $data['name'];
        $product->category_id = $data['category_id'];
        $product->product_type_id = $data['product_type_id'];
        $product->description = $data['description'];
        $product->price = $data['price'];
        $product->on_sale = $data['on_sale'];
        $product->rate = $data['rate'];
        $product->quantity = $data['quantity'];

        if ($product->create()) {
            return $product;
        }

        return null;
    }
}