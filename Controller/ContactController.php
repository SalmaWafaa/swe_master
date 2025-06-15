<?php
require_once __DIR__ . '/../View/User/ContactView.php';
require_once __DIR__ . '/../Model/User/ContactInfo.php';

class ContactController {
    public function showContact() {
        // Create contact info object with your business details
        $contactInfo = new ContactInfo(
            "1-800-SYS-FASHION",  // Hotline
            "contact@sysfashion.com",  // Email
            "https://instagram.com/sys_fashion"  // Instagram
        );

        // Create and render the view
        $view = new ContactView();
        $view->render($contactInfo);
    }

    public function showForm() {
        include __DIR__ . '/../View/contact/contact_form.php';
    }

    public function submitForm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

            if (!$name || !$email || !$message) {
                $_SESSION['contact_error'] = "All fields are required.";
                header("Location: index.php?controller=Contact&action=showForm");
                exit();
            }

            // Here you would typically:
            // 1. Save the message to a database
            // 2. Send an email notification
            // 3. Handle any other contact form processing

            $_SESSION['contact_success'] = "Thank you for your message. We'll get back to you soon!";
            header("Location: index.php?controller=Category&action=listCategories");
            exit();
        }
    }
} 