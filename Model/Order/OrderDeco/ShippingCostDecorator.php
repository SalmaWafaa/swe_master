<?php
require_once __DIR__ . '/IOrder.php';
class ShippingCostDecorator implements IOrder {
    private IOrder $order;
    private float $shippingCost;
    public function __construct(IOrder $order, float $shippingCost) {
        $this->order = $order;
        $this->shippingCost = $shippingCost;
    }
    public function getTotal(): float {
        return $this->order->getTotal() + $this->shippingCost;
    }
    public function update(int $orderId): float {
        return $this->getTotal();
    }
}