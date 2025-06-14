<?php

require_once 'C:\xampp\htdocs\ecommerce_master\Model\Category\CategoryComposite.php';

class MalePantsCategory extends CategoryComposite {
    public function __construct() {
        parent::__construct();
        $this->name='Male Pants';
        $this->image= 'hhhh.jpg';
    }
}