<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Model/Size/SizeSystemInterface.php';
require_once __DIR__ . '/../Model/Size/SizeSystemAdapter.php';

class SizeConversionController {
    public function convertSize() {
        try {
            // Validate input
            if (!isset($_POST['size']) || !isset($_POST['targetSystem']) || !isset($_POST['categoryId'])) {
                throw new Exception("Missing required parameters");
            }

            $size = $_POST['size'];
            $targetSystem = $_POST['targetSystem'];
            $categoryId = $_POST['categoryId'];

            // Get size system for category
            $sizeSystem = SizeSystemAdapter::getSystemForCategory($categoryId);
            if (!$sizeSystem) {
                throw new Exception("No size system found for this category");
            }

            // Convert size
            $convertedSize = $sizeSystem->convertSize($size, $targetSystem);
            
            // Store result in session
            $_SESSION['conversion_result'] = "Converted size: {$size} ({$sizeSystem->getSystemName()}) → {$convertedSize} ({$targetSystem})";
            $_SESSION['conversion_success'] = true;

            // Debug logging
            error_log("Size conversion successful: {$size} → {$convertedSize}");
            error_log("Session data: " . print_r($_SESSION, true));

        } catch (Exception $e) {
            error_log("Size conversion error: " . $e->getMessage());
            $_SESSION['conversion_result'] = "Error: " . $e->getMessage();
            $_SESSION['conversion_success'] = false;
        }

        // Redirect back to product page
        $returnUrl = isset($_POST['return_url']) ? $_POST['return_url'] : '/swe_master/index.php';
        header("Location: " . $returnUrl);
        exit();
    }

    public function getAvailableSystems() {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->query("SELECT id, name FROM size_systems ORDER BY name");
            $systems = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'systems' => $systems
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => "Failed to fetch size systems"
            ]);
        }
    }
}

// Handle direct controller access
if (isset($_POST['action']) && $_POST['action'] === 'convertSize') {
    $controller = new SizeConversionController();
    $controller->convertSize();
} 