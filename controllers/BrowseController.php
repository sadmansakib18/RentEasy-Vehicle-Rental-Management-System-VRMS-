<?php
require_once __DIR__ . "/../models/VehicleModel.php";

class BrowseController {
    private $vehicleModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->vehicleModel = new VehicleModel();
    }

    public function home() {
        $vehicles = $this->vehicleModel->getAvailable();
        require __DIR__ . "/../views/home.php";
    }

    public function index() {
        $category = $_GET["category"] ?? "";
        $search = $_GET["search"] ?? "";

        if (!empty($category) || !empty($search)) {
            $vehicles = $this->vehicleModel->search($category, $search);
        } else {
            $vehicles = $this->vehicleModel->getAvailable();
        }

        require __DIR__ . "/../views/browse/index.php";
    }
}
?>
