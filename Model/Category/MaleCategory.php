<?php

require_once __DIR__ . '/CategoryComposite.php';

class MaleCategory extends CategoryComposite {
    protected $maleSizeChart = [];  // Example: ['S' => 'Small', 'M' => 'Medium', 'L' => 'Large']
    protected $maleShirtSizeChart = []; // Example: Male shirt size chart

    public function __construct() {
        parent::__construct();
        $this->name = "Male";
        $this->image = "male.jpg";
    }

    public function getMaleSizeChart(): array {
        return $this->maleSizeChart;
    }

    public function getMaleShirtSizeChart(): array {
        return $this->maleShirtSizeChart;
    }

    // // Method to get products from Male Shirt Category
    // public function getMaleShirtCollection(): array {
    //     // Fetch the products related to Male Shirt category
    //     return $this->getProductsByCategory(5); // Assuming 5 is the Male Shirt category ID
    // }

    // // Method to get products from Male Pants Category
    // public function getMalePantsCollection(): array {
    //     // Fetch the products related to Male Pants category
    //     return $this->getProductsByCategory(6); // Assuming 6 is the Male Pants category ID
    // }
}
?>
