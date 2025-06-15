<?php

abstract class ProductFactory {
    abstract public function createProduct($data);
}

class ConcreteProductFactory extends ProductFactory {
    public function createProduct($data) {
        $product = new Product();
        $product->setId($data['id'] ?? null);
        $product->setName($data['name']);
        $product->setDescription($data['description'] ?? '');
        $product->setPrice($data['price']);
        $product->setStock($data['stock'] ?? 0);
        $product->setCategoryId($data['category_id']);
        $product->setImageUrl($data['image_url'] ?? null);
        return $product;
    }

    public static function createProductFromRow($row) {
        return self::createProduct([
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'stock' => $row['stock'],
            'category_id' => $row['category_id'],
            'image_url' => $row['image_url']
        ]);
    }
}
?>

