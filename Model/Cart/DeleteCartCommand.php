<?php
class DeleteCartCommand {
    private $cartModel;
    private $cartId;
    private $productId;

    public function __construct($cartModel, $cartId, $productId) {
        $this->cartModel = $cartModel;
        $this->cartId = $cartId;
        $this->productId = $productId;
    }

    public function execute() {
        $this->cartModel->deleteItemFromCart($this->cartId, $this->productId);
    }
}
