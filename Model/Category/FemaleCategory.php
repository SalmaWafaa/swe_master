<?php

require_once 'C:\xampp\htdocs\ecommerce_master\Model\Category\CategoryComposite.php';

class FemaleCategory extends CategoryComposite {
    public function __construct() {
        parent::__construct();
        $this->name = "Female";
        $this->image = "female.jpg";
    }
}