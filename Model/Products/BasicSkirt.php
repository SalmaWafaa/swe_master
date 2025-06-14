<?php

class BasicSkirt extends AbstractProduct {
    private $skirtLength;

    public function __construct($id, $name, $description, $price, $quantity, $sizes, $colors, $images, $category, $subcategory) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->sizes = $sizes;
        $this->colors = $colors;
        $this->images = $images;
        $this->category = $category;
        $this->subcategory = $subcategory;
        //$this->skirtLength = $skirtLength;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getSizes() {
        return $this->sizes;
    }

    public function getColors() {
        return $this->colors;
    }

    public function getImages() {
        return $this->images;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getSubcategory() {
        return $this->subcategory;
    }
    public function getSkirtLength() {
        return $this->skirtLength;
    }
    public function setSkirtLength($skirtLength) {
        $this->skirtLength = $skirtLength;
    }

}
?>
