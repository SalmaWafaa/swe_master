<?php
require_once 'E:\xampp\htdocs\swe_master\config\Database.php';

// class Order {
//     private $id;
//     private $items;
//     private $dateCreated;
//     private $shipmentDate;
//     private $total;
//     private $status;
//     private $address;
//     private $paymentType;
//     private $observers = [];

//     public function __construct($id, $items, $dateCreated, $shipmentDate, $total, $status, $address, $paymentType) {
//         $this->id = $id;
//         $this->items = $items;
//         $this->dateCreated = $dateCreated;
//         $this->shipmentDate = $shipmentDate;
//         $this->total = $total;
//         $this->status = $status;
//         $this->address = $address;
//         $this->paymentType = $paymentType;
//     }

//     public function attach($observer) {
//         $this->observers[] = $observer;
//     }

//     public function detach($observer) {
//         $this->observers = array_filter($this->observers, function ($obs) use ($observer) {
//             return $obs !== $observer;
//         });
//     }

//     public function notify() {
//         foreach ($this->observers as $observer) {
//             $observer->update($this);
//         }
//     }

//     public function placeOrder() {
//         // Save order to database
//         $db = Database::getInstance()->getConnection();
//         $stmt = $db->prepare("INSERT INTO orders (customer_id, date_created, shipment_date, total, status, address, payment_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
//         $stmt->bind_param("issdsss", $this->id, $this->dateCreated, $this->shipmentDate, $this->total, $this->status, $this->address, $this->paymentType);
//         $stmt->execute();
//         $stmt->close();

//         // Notify observers
//         $this->notify();
//     }

//     public function getId() {
//         return $this->id;
//     }

//     public function getItems() {
//         return $this->items;
//     }

//     public function getStatus() {
//         return $this->status;
//     }
// }
?>