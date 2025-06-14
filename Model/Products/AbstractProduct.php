<?php

abstract class AbstractProduct {
    protected $id;
    protected $name;
    protected $description;
    protected $price;
    protected $quantity;
    protected $sizes = [];
    protected $colors = [];
    protected $images = [];
    protected $category;
    protected $subcategory;

    abstract public function getDescription();
    abstract public function getPrice();
    abstract public function getColors();
    abstract public function getSizes();
    abstract public function getImages();
    abstract public function getCategory();
    abstract public function getSubCategory();
}

?>
