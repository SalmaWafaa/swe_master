<?php

class BasicPants extends AbstractProduct {
    private $fitType;

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
       // $this->fitType = $fitType;
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
    public function getFitType() {
        return $this->fitType;
    }
    public function setFitType($fitType) {
        $this->fitType = $fitType;
    }
}
?>
