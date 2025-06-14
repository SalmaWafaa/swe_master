<?php

require_once __DIR__ . '/MaleCategory.php';

class MalePantsCategory extends MaleCategory {
    public function __construct() {
        parent::__construct();
        $this->name = "Male Pants";
        $this->image = "male_pants.jpg";
    }

    // Optionally override methods specific to MalePantsCategory if needed
}
?>
