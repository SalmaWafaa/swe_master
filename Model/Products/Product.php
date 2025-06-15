<?php
class Product {
    private $id;
    private $name;
    private $description;
    private $price;
    private $stock;
    private $categoryId;
    private $imageUrl;

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setStock($stock) {
        $this->stock = $stock;
    }

    public function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }

    public function setImageUrl($imageUrl) {
        $this->imageUrl = $imageUrl;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getStock() {
        return $this->stock;
    }

    public function getCategoryId() {
        return $this->categoryId;
    }

    public function getImageUrl() {
        return $this->imageUrl;
    }
} 