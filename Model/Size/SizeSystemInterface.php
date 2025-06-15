<?php
interface SizeSystemInterface {
    public function convertSize(string $size, string $targetSystem): string;
    public function getSystemName(): string;
    public function getSystemId(): int;
} 