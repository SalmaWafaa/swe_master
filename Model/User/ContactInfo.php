<?php
class ContactInfo {
    private $hotline;
    private $email;
    private $instagram;

    public function __construct() {
        $this->hotline = "+1 (123) 456-7890";
        $this->email = "contact@sys.com";
        $this->instagram = "https://instagram.com";
    }

    public function getHotline() {
        return $this->hotline;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getInstagram() {
        return $this->instagram;
    }
}
?> 