<?php
class ShippedState implements IOrderState {
    public function next(): string {
        return 'Delivered';
    }
}