<?php
require_once __DIR__ . "/../models/VehicleModel.php";
require_once __DIR__ . "/../models/RentalModel.php";
require_once __DIR__ . "/../models/UserModel.php";

class DashboardController {
    private $vehicleModel;
    private $rentalModel;
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->vehicleModel = new VehicleModel();
        $this->rentalModel = new RentalModel();
        $this->userModel = new UserModel();
    }

    public function index() {
        if (!isset($_SESSION["user_id"])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $allVehicles = $this->vehicleModel->getAll();
        $totalVehicles = count($allVehicles);
        $availableVehicles = 0;
        $rentedVehicles = 0;
        foreach ($allVehicles as $v) {
            if ($v["status"] === "available") $availableVehicles++;
            if ($v["status"] === "rented") $rentedVehicles++;
        }

        $pendingApprovals = $this->rentalModel->getPendingCount();
        $totalRevenue = $this->rentalModel->getTotalRevenue();

        $userRole = $_SESSION["user_role"] ?? "customer";
        $userId = $_SESSION["user_id"];

        if ($userRole === "customer") {
            $recentRentals = $this->rentalModel->getByCustomer($userId);
        } else {
            $recentRentals = $this->rentalModel->getAllWithDetails();
        }

        require __DIR__ . "/../views/dashboard/index.php";
    }
}
?>
