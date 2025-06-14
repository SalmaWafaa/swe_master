<?php
// require_once 'Database.php';

// class InventoryCheck {
//     public function update($order) {
//         $db = Database::getInstance()->getConnection();
//         foreach ($order->getItems() as $productId => $quantity) {
//             $stmt = $db->prepare("UPDATE inventorycheck SET stock = stock - ? WHERE product_id = ?");
//             $stmt->bind_param("ii", $quantity, $productId);
//             $stmt->execute();
//             $stmt->close();
//         }
//         echo "Inventory updated for order ID: " . $order->getId() . "\n";
//     }
// }
?>