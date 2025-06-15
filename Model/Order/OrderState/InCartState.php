<?php
class InCartState implements IOrderState {
    public function next(): string {
        return 'Placed';
    }
}