<?php
interface IOrder 
{
    public function getTotal(): float;
    public function update(int $orderId): float;
}