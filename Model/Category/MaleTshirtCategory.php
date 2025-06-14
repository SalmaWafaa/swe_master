<?php

require_once 'C:\xampp\htdocs\swe_master\Model\Category\CategoryComposite.php';

class MaleTshirtCategory extends CategoryComposite {
    public function __construct() {
        parent::__construct();
        $this->name="Male Tshirt";
        $this->image="image.jpg";
}
}