<?php

abstract class BaseTemplate {
    // Template method that defines the skeleton of the algorithm
    final public function render() {
        $this->initialize();
        $this->loadHeader();
        $this->loadContent();
        $this->loadFooter();
        $this->finalize();
    }

    // Abstract methods that must be implemented by subclasses
    abstract protected function loadContent();

    // Hook methods with default implementations
    protected function initialize() {
        // Check if session is already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function loadHeader() {
        // Default implementation - can be overridden by subclasses
        $headerPath = __DIR__ . '/../View/User/header.php';
        if (file_exists($headerPath)) {
            include_once $headerPath;
        }
    }

    protected function loadFooter() {
        // Default implementation - can be overridden by subclasses
        $footerPath = __DIR__ . '/../View/User/footer.php';
        if (file_exists($footerPath)) {
            include_once $footerPath;
        }
    }

    protected function finalize() {
        // Default implementation - can be overridden by subclasses
        // Clean up or additional processing if needed
    }
} 