<?php

class ProductImage {
    private $id;
    private $product_id;
    private $image_url;

    public function __construct($id, $product_id, $image_url) {
        $this->id = $id;
        $this->product_id = $product_id;
        $this->image_url = $image_url;
    }

    public static function getImagesByProductId($productId) {
        $db = Database::getInstance()->getConnection();
        $query = "SELECT * FROM product_images WHERE product_id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$productId]);

        $images = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $images[] = new ProductImage($row['id'], $row['product_id'], $row['image_url']);
        }

        return $images;
    }

    public function getImageUrl() {
        return $this->image_url;
    }
}
?>
