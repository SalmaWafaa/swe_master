<?php
require_once 'AbstractProduct.php';
class BasicPant extends AbstractProduct {
    public function __construct(
        int $id,
        string $name,
        array $colors,
        array $sizes,
        array $images,
        float $rate,
        array $reviews,
        int $quantity,
        float $salePercentage,
        float $cost
    ) {
        parent::__construct($id, $name, $colors, $sizes, $images, $rate, $reviews, $quantity, $salePercentage, $cost);
    }

    public function getDescription(): string {
        return "This is a basic pair of pants.";
    }
}
?>