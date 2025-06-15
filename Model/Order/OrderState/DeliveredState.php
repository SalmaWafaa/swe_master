<?php
class DeliveredState implements IOrderState {
    public function next(): string {
        return 'Delivered';
    }
}