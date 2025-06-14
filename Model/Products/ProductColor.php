<?php

class ProductColor {
    private $id;
    private $product_id;
    private $color;

    public function __construct($id, $product_id, $color) {
        $this->id = $id;
        $this->product_id = $product_id;
        $this->color = $color;
    }

    public static function getColorsByProductId($productId) {
        $db = Database::getInstance()->getConnection();
        $query = "SELECT * FROM product_colors WHERE product_id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$productId]);

        $colors = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $colors[] = new ProductColor($row['id'], $row['product_id'], $row['color']);
        }

        return $colors;
    }

    public function getColor() {
        return $this->color;
    }
}
?>
