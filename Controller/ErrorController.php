<?php
class ErrorController {
    public function showError($title, $message) {
        $errorTitle = $title;
        $errorMessage = $message;
        require_once __DIR__ . '/../View/Error/ErrorView.php';
    }

    public function invalidAction() {
        $this->showError(
            'Invalid Action',
            'The requested action is not valid. Please try again or return to the home page.'
        );
    }

    public function notFound() {
        $this->showError(
            'Page Not Found',
            'The page you are looking for does not exist. Please check the URL and try again.'
        );
    }

    public function unauthorized() {
        $this->showError(
            'Unauthorized Access',
            'You do not have permission to access this page. Please log in or contact support.'
        );
    }
} 