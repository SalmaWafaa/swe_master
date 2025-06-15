<?php
require_once __DIR__ . '/OrderState/IOrderState.php';
require_once __DIR__ . '/OrderState/InCartState.php';
require_once __DIR__ . '/OrderState/PlacedState.php';
require_once __DIR__ . '/OrderState/PaidState.php';
require_once __DIR__ . '/OrderState/ShippedState.php';
require_once __DIR__ . '/OrderState/DeliveredState.php';

class OrderStateFactory 
{
    public static function create(string $status): IOrderState {
        return match($status) {
            'In Cart' => new InCartState(),
            'Placed' => new PlacedState(),
            'Paid' => new PaidState(),
            'Shipped' => new ShippedState(),
            'Delivered' => new DeliveredState(),
            default => throw new Exception("Invalid order status: $status"),
        };
    }
}