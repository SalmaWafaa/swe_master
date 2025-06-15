<?php
interface IOrderState 
{
    public function next(): string;
}