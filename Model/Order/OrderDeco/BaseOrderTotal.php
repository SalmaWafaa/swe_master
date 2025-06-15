<?php
require_once __DIR__ . '/IOrder.php';

class BaseOrderTotal implements IOrder {
    protected float $orderTotal;
    public function __construct(float $orderTotal = 0) {
        $this->orderTotal = $orderTotal;
    }

    public function getTotal(): float {
        return $this->orderTotal;
    }

    public function update(int $orderId): float {
        return $this->getTotal();
    }
}
