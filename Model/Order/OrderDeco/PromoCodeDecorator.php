<?php
require_once __DIR__ . '/IOrder.php';
class PromoCodeDecorator implements IOrder 
{
    private IOrder $order;
    private float $discount;
    public function __construct(IOrder $order, float $discount) {
        $this->order = $order;
        $this->discount = $discount;
    }
    public function getTotal(): float {
        return max(0, $this->order->getTotal() - $this->discount);
    }
    public function update(int $orderId): float {
        return $this->getTotal();
    }
}