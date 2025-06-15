<?php
require_once 'Model/User/FacadeAdminDashboard.php';


class AdminDashboardController {
    private $model;
    private $view = 'admin_dashboard.php';

    public function __construct() 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
        
        $this->model = new AdminDashboardModel();
    }

    // public function index() 
    // {
    //     $data = [
    //         'statistics' => $this->model->getProductStatistics(),
    //         'products' => $this->model->getAllProducts(),
    //         'customers' => $this->model->getAllCustomers(),
    //         'orders' => $this->model->getAllOrdersWithStates() // NEW
    //     ];

    //     extract($data);
    //     require_once "View/{$this->view}";
    // }

    public function index() 
    {
        try {
            $facade = new FacadeAdminDashboard();
            $data = $facade->loadDashboardData();

            extract($data);
            require_once "View/User/{$this->view}";
        } catch (Exception $e) {
            error_log("Error in AdminDashboardController::index: " . $e->getMessage());
            $_SESSION['error'] = "Failed to load dashboard data. Please try again.";
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }
    }


    public function updateStockPrice()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? null;
            $price = $_POST['price'] ?? null;
            $stock = $_POST['stock'] ?? null;

            try {
                if (!$productId || $price === null || $stock === null) {
                    throw new \InvalidArgumentException("Missing input.");
                }

                $this->model->updateProductStockAndPrice($productId, $price, $stock);

                header("Location: index.php?controller=AdminDashboard&action=index&success=1");
                exit();

            } catch (\Exception $e) {
                error_log("AdminDashboardController update error: " . $e->getMessage());
                header("Location: index.php?controller=AdminDashboard&action=index&error=1");
                exit();
            }
        }
    }

    public function deleteCustomerAjax()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            header('Content-Type: application/json');

            if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
                echo json_encode(['success' => false, 'message' => 'Invalid customer ID']);
                return;
            }

            try {
                $this->model->deleteCustomerById($id);
                echo json_encode(['success' => true]);
            } catch (\Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }
}
