<?php
// Autoload classes
spl_autoload_register(function ($class_name) {
    $directories = [
        __DIR__ . '/Controller/',
        __DIR__ . '/Model/Products/',
        __DIR__ . '/Controller/Cart/',
        __DIR__ . '/Controller/Order/',
        __DIR__ . '/Controller/Payment/', 
    ];

    $class_name = str_replace('\\', '/', $class_name);

    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

    //  Shows which class was not found
    die("Class '{$class_name}' not found.");
});

// Database config
require_once __DIR__ . '/config/dbConnectionSingelton.php';

// Default routing
$controllerName = $_GET['controller'] ?? 'Category';
$actionName     = $_GET['action'] ?? 'listCategories';

// Controller class name
$controllerClassName = ucfirst($controllerName) . 'Controller';

try {
    // 🔍 Check controller file and class
    if (!class_exists($controllerClassName)) {
        throw new Exception("Controller '{$controllerClassName}' not found.");
    }

    $controllerInstance = new $controllerClassName();

    if (!method_exists($controllerInstance, $actionName)) {
        throw new Exception("Action '{$actionName}' not found in '{$controllerClassName}'.");
    }

    // Handle special POST mappings (optional cleanup below)
    if ($controllerName === 'Product') {
        if ($actionName === 'addProduct' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $actionName = 'handleAddProduct';
        } elseif ($actionName === 'addProduct') {
            $actionName = 'showAddProductForm';
        } elseif ($actionName === 'updateProduct' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $actionName = 'updateProductPost';
        } elseif ($actionName === 'editProduct') {
            $actionName = 'editProduct';
        }
    } elseif ($controllerName === 'User' && $actionName === 'updateProfile' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $actionName = 'handleUpdateProfile';
    }

    // Get additional parameters from URL
    $params = array_values(array_filter($_GET, function($key) {
        return $key !== 'controller' && $key !== 'action';
    }, ARRAY_FILTER_USE_KEY));

    // Call the action method
    call_user_func_array([$controllerInstance, $actionName], $params);

} catch (Exception $e) {
    die("<strong>Error:</strong> " . $e->getMessage());
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
