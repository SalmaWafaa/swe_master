<?php
class PaidState implements IOrderState {
    public function next(): string {
        return 'Shipped';
    }
}