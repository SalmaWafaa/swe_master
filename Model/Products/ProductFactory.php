<?php

abstract class ProductFactory {
    abstract public function createProduct($id, $name, $description, $price, $quantity, $sizes, $colors, $images, $category, $subcategory);
}


class ConcreteProductFactory extends ProductFactory {
    public function createProduct($id, $name, $description, $price, $quantity, $sizes, $colors, $images, $category, $subcategory) {
        // Depending on the type of product, return the appropriate class
        switch ($category) {
            case 'Shirt':
                return new BasicShirt($id, $name, $description, $price, $quantity, $sizes, $colors, $images, $category, $subcategory);
            case 'Pants':
                return new BasicPants($id, $name, $description, $price, $quantity, $sizes, $colors, $images, $category, $subcategory);
            case 'Skirt':
                return new BasicSkirt($id, $name, $description, $price, $quantity, $sizes, $colors, $images, $category, $subcategory);
            default:
                throw new Exception("Product type not found.");
        }
    }
}
?>

