<?php
require_once __DIR__ . '/AdminDashboardModel.php';



class FacadeAdminDashboard 
{
    private $model;

    public function __construct() {
        $this->model = new AdminDashboardModel();
    }

    public function loadDashboardData(): array {
        return [
            'statistics' => $this->model->getProductStatistics(),
            'products'   => $this->model->getAllProducts(),
            'customers'  => $this->model->getAllCustomers(),
            'orders'     => $this->model->getAllOrdersWithStates()
        ];
    }
}
