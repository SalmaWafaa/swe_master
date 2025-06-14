<?php
require_once 'Database.php';

// class Shipping {
//     public function update($order) {
//         echo "Shipping process started for order ID: " . $order->getId() . "\n";

//         // Step 1: Generate a tracking number
//         $trackingNumber = $this->generateTrackingNumber();
//         echo "Tracking number generated: " . $trackingNumber . "\n";

//         // Step 2: Update the order status to "Shipped"
//         $this->updateOrderStatus($order->getId(), 'Shipped', $trackingNumber);
//         echo "Order status updated to 'Shipped'.\n";

//         // Step 3: Notify the customer
//         $this->notifyCustomer($order->getId(), $trackingNumber);
//         echo "Customer notified with tracking number.\n";

//         // Step 4: Simulate shipping delay (optional, for demonstration purposes)
//         sleep(2); // Simulate a 2-second delay in shipping
//         echo "Order ID: " . $order->getId() . " has been shipped.\n";
//     }

//     private function generateTrackingNumber() {
//         // Generate a random tracking number (for demonstration purposes)
//         return 'TRK' . strtoupper(substr(md5(uniqid()), 0, 10));
//     }

//     private function updateOrderStatus($orderId, $status, $trackingNumber) {
//         // Update the order status and tracking number in the database
//         $db = Database::getInstance()->getConnection();
//         $stmt = $db->prepare("UPDATE orders SET status = ?, tracking_number = ? WHERE id = ?");
//         $stmt->bind_param("ssi", $status, $trackingNumber, $orderId);
//         $stmt->execute();
//         $stmt->close();
//     }

//     private function notifyCustomer($orderId, $trackingNumber) {
//         // Simulate notifying the customer via email or other means
//         // For demonstration purposes, we'll just log the notification
//         $message = "Your order #" . $orderId . " has been shipped. Tracking number: " . $trackingNumber;
//         echo "Notification sent to customer: " . $message . "\n";
//     }
// }
?>