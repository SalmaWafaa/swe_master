<?php

interface ICategory {
    // Getters for basic attributes
    public function getId(): int;
    public function getName(): string;
    public function getImage(): string;
    
    // Methods for managing subcategories
    public function getSubcategories(int $parent_id);  // Returns an array of ICategory objects
    public function getSubcategoryById(int $subcategory_id): ?ICategory;
    
    // Methods for managing products
    public function getProducts(): array;  // Returns an array of products related to the category
    
    // CRUD operations
    public function save(): bool;
    public function update(): bool;
    public function delete(): bool;
}
