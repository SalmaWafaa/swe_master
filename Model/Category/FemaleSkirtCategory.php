<?php

require_once 'C:\xampp\htdocs\ecommerce_master\Model\Category\CategoryComposite.php';

class FemaleSkirtCategory extends CategoryComposite {
    public function __construct() {
        parent::__construct();
        $this->name = " Female Skirt";
        $this->image = "female.jpg";
    }
}
?>