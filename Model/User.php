<?php

abstract class User {
    protected $id;
    protected $firstName;
    protected $lastName;
    protected $email;
    protected $password;

    public function __construct($id, $firstName, $lastName, $email, $password) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
    }

    // Setter for ID
    public function setId($id) {
        $this->id = $id;
    }

    // Getter for ID
    public function getId() {
        return $this->id;
    }

    // Setter for first name
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    // Setter for last name
    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    // Setter for email
    public function setEmail($email) {
        $this->email = $email;
    }

    // Setter for password
    public function setPassword($password) {
        $this->password = $password;
    }

    abstract public function login();
    abstract public function register();
    abstract public function editAccount();
}