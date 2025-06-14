<?php

require_once 'C:\xampp\htdocs\ecommerce_master\Model\Category\CategoryComposite.php';

class MaleCategory extends CategoryComposite {
    public function __construct() {
        parent::__construct();
        $this->name = "Male";
        $this->image = "male.jpg";
    }
}