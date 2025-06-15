<?php 
class TaxDecorator implements IOrder {
    private IOrder $order;
    private float $taxRate;
    public function __construct(IOrder $order, float $taxRate) {
        $this->order = $order;
        $this->taxRate = $taxRate;
    }
    public function getTotal(): float {
        return $this->order->getTotal() * (1 + $this->taxRate / 100);
    }
    public function update(int $orderId): float {
        return $this->getTotal();
    }
}