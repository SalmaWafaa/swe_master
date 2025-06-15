<?php
class AddToCartCommand {
    private $cartModel;
    private $cartId;
    private $productId;

    public function __construct($cartModel, $cartId, $productId) {
        $this->cartModel = $cartModel;
        $this->cartId = $cartId;
        $this->productId = $productId;
    }

    public function execute() {
        $this->cartModel->addItemToCart($this->cartId, $this->productId, 1);
    }
}
