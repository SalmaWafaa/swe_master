<?php
class decreaseItemCommand {
    private $cartModel;
    private $cartId;
    private $productId;

    public function __construct($cartModel, $cartId, $productId) {
        $this->cartModel = $cartModel;
        $this->cartId = $cartId;
        $this->productId = $productId;
    }

    public function execute() {
        $this->cartModel->decreaseItemQuantity($this->cartId, $this->productId);
    }
}
