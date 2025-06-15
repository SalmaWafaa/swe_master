<?php
class PlacedState implements IOrderState {
    public function next(): string {
        return 'Paid';
    }
}