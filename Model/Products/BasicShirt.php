<?php

class BasicShirt extends AbstractProduct {
    private $sleeveType;

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
      //  $this->sleeveType = $sleeveType;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getColors() {
        return $this->colors;
    }

    public function getSizes() {
        return $this->sizes;
    }

    public function getImages() {
        return $this->images;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getSubCategory() {
        return $this->subcategory;
    }
    public function getSleeveType() {
        return $this->sleeveType;
    }
    public function setSleeveType($sleeveType) {
        $this->sleeveType = $sleeveType;
    }
}
?>
