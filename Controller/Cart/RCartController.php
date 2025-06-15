<?php
session_start();
require_once __DIR__ . '/../../Model/Cart/CartModel.php';
// require_once __DIR__ . '/../../Model/Cart/CartInvoker.php';
// require_once __DIR__ . '/../../Model/Cart/AddToCartCommand.php';
// require_once __DIR__ . '/../../Model/Cart/DeleteCartCommand.php';
                                                                                                                            

class RCartController {
    public function viewCart() {
        if (!isset($_SESSION['user_id'])) {
            die("You must be logged in to view your cart.");
        }

        $userId = $_SESSION['user_id'];
        $cartModel = CartModel::getInstance($userId);
        $cartItems = $cartModel->getCartItems();

        // Pass $cartItems to the view
        require_once __DIR__ . '/../../View/Cart/CartView.php'; // Adjusted path for this example's structure
    }

    public function addAndModifyCart() { // Renamed for clarity on combined actions
        if (!isset($_SESSION['user_id'])) {
            die("You must be logged in.");
        }

        $userId = $_SESSION['user_id'];
        $cartModel = CartModel::getInstance($userId);
        $cartId = $cartModel->getCartIdByUserId($userId);

        if (!$cartId) {
            $cartId = $cartModel->createCart($userId);
        }

        if (isset($_POST['action'])) {
            $productId = isset($_POST['product_id']) ? $_POST['product_id'] : null;

            if ($productId) {
                switch ($_POST['action']) {
                    case 'addItem':
                        $cartModel->addItemToCart($cartId, $productId); // Defaults to quantity 1
                        break;
                    case 'deleteItem':
                        $cartModel->deleteItemFromCart($cartId, $productId);
                        break;
                    case 'decreaseItem':
                        $cartModel->decreaseItemQuantity($cartId, $productId);
                        break;
                    default:
                        // Handle unknown action
                        break;
                }
            } else {
                // Product ID is missing for the action
                error_log("Product ID missing for cart action: " . $_POST['action']);
            }
        }

        // Redirect back to the cart view after any action
        // header('Location: /swe_master/index.php?controller=RCart&action=viewCart');
        // exit;
        // For this example, we'll just reload the view after processing to show immediate changes
        $this->viewCart();
    }

    public function addToCartDirect() {
        session_start(); // Ensure session is started for this direct call as well

        if (!isset($_SESSION['user_id'])) {
            die("You must be logged in to add to cart.");
        }

        $userId = $_SESSION['user_id'];
        $productId = $_GET['product_id']; // This comes from a GET request (e.g., from a product listing)

        $cartModel = CartModel::getInstance($userId);
        $cartId = $cartModel->getCartIdByUserId($userId);

        if (!$cartId) {
            $cartId = $cartModel->createCart($userId);
        }

        $cartModel->addItemToCart($cartId, $productId, 1);

        // Redirect to the cart view after adding
        header('Location: index.php?controller=RCart&action=viewCart'); // Use controller action for redirect
        exit;
    }
}
?>

<?php
// --- View/Cart/CartView.php ---
// This file is included by RCartController, so $cartItems and $_SESSION['user_id'] should be available.
// It's also serving as a simple index.php for this demonstration.

// Simulate a user login for testing purposes
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Assign a dummy user ID for testing
    echo "<p>Simulated login for User ID: " . $_SESSION['user_id'] . "</p>";
}

// Simple routing mechanism for demonstration
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'RCart';
$action = isset($_GET['action']) ? $_GET['action'] : 'viewCart';

// Instantiate and call controller method
$rcartController = new RCartController();

// Dynamically call the method based on the action
if (method_exists($rcartController, $action)) {
    $rcartController->$action();
} else {
    die("Action not found: " . htmlspecialchars($action));
}

// The HTML structure is now directly within this combined file for simplicity of demonstration.
// In a real application, the controller would include the view file AFTER processing.
?>
