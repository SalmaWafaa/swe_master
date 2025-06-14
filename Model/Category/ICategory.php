<?php

interface ICategory {
    public function getId(): int;
    public function getName(): string;
    public function getImage(): string;
    public function getParentCategory(): ?ICategory;
    public function getSubcategories($category_id);
    public function getProducts(): array;
    public function addProduct($product): void;
    public function removeProduct(int $productId): void;
    public function getProductById(int $productId): ?array;
    public function addSubcategory(ICategory $category): void;
    public function removeSubcategory(int $categoryId): void;
    public function getSubcategoryById(int $categoryId): ?ICategory;
    public function save(): bool;
    public function update(): bool;
    public function delete(): bool;
}