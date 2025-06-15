<?php

require_once 'Icommand.php';
class CartInvoker {
    private $command;

    public function setCommand(Command $command) {
        $this->command = $command;
    }

    public function run() {
        $this->command->execute();
    }
}
