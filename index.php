<?php
// Autoload classes
spl_autoload_register(function ($class_name) {
    $directories = [
        __DIR__ . '/Controller/',
        __DIR__ . '/Model/Products/',
        __DIR__ . '/Controller/Cart/',
            
    ];

    $class_name = str_replace('\\', '/', $class_name);

    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    die("Class '{$class_name}' not found.");
});

// Database configuration
require_once __DIR__ . '/config/dbConnectionSingelton.php';

// Get the controller and action from the URL
$controller = $_GET['controller'] ?? 'Category'; // Default controller
$action = $_GET['action'] ?? 'listCategories';  // Default action

// Construct the controller class name
$controllerClassName = ucfirst($controller) . 'Controller';

try {
    // Check if the controller class exists
    if (!class_exists($controllerClassName)) {
        throw new Exception("Controller '{$controllerClassName}' not found.");
    }

    // Instantiate the controller
    $controllerInstance = new $controllerClassName();

    // Check if the action method exists in the controller
    if (!method_exists($controllerInstance, $action)) {
        throw new Exception("Action '{$action}' not found in controller '{$controllerClassName}'.");
    }

    // Prepare parameters for the action method
    $params = [];
    $controllerName = $_GET['controller'] ?? 'Category'; // Default controller
    $actionName = $_GET['action'] ?? 'listCategories';  // Default action
    // If the action is 'deleteProduct', pass the product ID
    if ($controllerName === 'Product') {
        if ($actionName === 'addProduct' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $actionName = 'handleAddProduct'; // Map POST request to handler method
        } elseif ($actionName === 'addProduct' && $_SERVER['REQUEST_METHOD'] === 'GET') {
             $actionName = 'showAddProductForm'; // Map GET request to form display method
        } elseif ($actionName === 'updateProduct' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $actionName = 'updateProductPost'; // Map POST update to handler
        } elseif ($actionName === 'editProduct' && $_SERVER['REQUEST_METHOD'] === 'GET') {
             $actionName = 'editProduct'; // Keep GET for edit form display
             // Note: The edit form POSTs to action=updateProduct, which gets mapped above
        }
        // Add similar mappings if needed for other controllers/actions (e.g., profile update)
    } elseif ($controllerName === 'User' && $actionName === 'updateProfile' && $_SERVER['REQUEST_METHOD'] === 'POST') {
         $actionName = 'handleUpdateProfile'; // Example mapping
    }
    
    
    else {
        // For other actions, pass only the relevant GET parameters
        $params = array_values(array_filter($_GET, function($key) {
            return $key !== 'controller' && $key !== 'action';
        }, ARRAY_FILTER_USE_KEY));
    }
    
    // Call the action method with the prepared parameters
    call_user_func_array([$controllerInstance, $action], $params);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

// session_start();

// // Autoload classes
// spl_autoload_register(function ($class_name) {
//     $directories = [
//         __DIR__ . '/Controller/',
//         __DIR__ . '/Model/',
//         __DIR__ . '/Model/Products/',
//         __DIR__ . '/View/'
//     ];

//     if (!isset($_SESSION['customer_id'])) {
//         $_SESSION['customer_id'] = 1; // Example: Assign a dummy customer ID for testing
//     }

//     foreach ($directories as $directory) {
//         $file = $directory . $class_name . '.php';
//         if (file_exists($file)) {
//             require_once $file;
//             return;
//         }
//     }
//     die("Class '{$class_name}' not found.");
// });

// // Database configuration
// require_once __DIR__ . '/config/Database.php';

// // Get the controller and action from the URL
// $controller = $_GET['controller'] ?? 'Category'; // Default controller
// $action = $_GET['action'] ?? 'listCategories';  // Default action

// // Special handling for user routes
// if ($controller === 'User') {
//     // For login/register views
//     if (in_array($action, ['showLogin', 'showRegister'])) {
//         $viewClass = ucfirst(str_replace('show', '', $action)) . 'View';
//         $view = new $viewClass();
//         $view->render();
//         exit;
//     }
// }

// // Construct the controller class name
// $controllerClassName = ucfirst($controller) . 'Controller';

// try {
//     // Check if the controller class exists
//     if (!class_exists($controllerClassName)) {
//         throw new Exception("Controller '{$controllerClassName}' not found.");
//     }

//     // Instantiate the controller
//     $controllerInstance = new $controllerClassName();

//     // Check if the action method exists in the controller
//     if (!method_exists($controllerInstance, $action)) {
//         throw new Exception("Action '{$action}' not found in controller '{$controllerClassName}'.");
//     }

//     // Prepare parameters for the action method
//     $params = [];

//     // Special parameter handling
//     switch ($action) {
//         case 'login':
//         case 'register':
//             $params = [$_POST];
//             break;
//         case 'deleteProduct':
//             $params = [$_GET['id'] ?? null];
//             break;
//         default:
//             $params = array_values(array_filter($_GET, function($key) {
//                 return $key !== 'controller' && $key !== 'action';
//             }, ARRAY_FILTER_USE_KEY));
//     }

//     // Call the action method with the prepared parameters
//     call_user_func_array([$controllerInstance, $action], $params);
// } catch (Exception $e) {
//     die("Error: " . $e->getMessage());
// }
