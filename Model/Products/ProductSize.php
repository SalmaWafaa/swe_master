<?php

class ProductSize {
    private $id;
    private $product_id;
    private $size;

    public function __construct($id, $product_id, $size) {
        $this->id = $id;
        $this->product_id = $product_id;
        $this->size = $size;
    }

    public static function getSizesByProductId($productId) {
        $db = Database::getInstance()->getConnection();
        $query = "SELECT * FROM product_sizes WHERE product_id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$productId]);

        $sizes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sizes[] = new ProductSize($row['id'], $row['product_id'], $row['size']);
        }

        return $sizes;
    }

    public function getSize() {
        return $this->size;
    }
}
?>
