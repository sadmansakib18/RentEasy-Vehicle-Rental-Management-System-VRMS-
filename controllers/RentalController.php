<?php
require_once __DIR__ . "/../models/RentalModel.php";
require_once __DIR__ . "/../models/VehicleModel.php";
require_once __DIR__ . "/../models/UserModel.php";

class RentalController {
    private $rentalModel;
    private $vehicleModel;
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION["user_id"])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        $this->rentalModel = new RentalModel();
        $this->vehicleModel = new VehicleModel();
        $this->userModel = new UserModel();
    }

    public function index() {
        $userId = $_SESSION["user_id"];
        $myRentals = $this->rentalModel->getByCustomer($userId);
        require __DIR__ . "/../views/rentals/index.php";
    }

    public function manage() {
        $role = $_SESSION["user_role"] ?? "";
        if ($role !== "super_admin" && $role !== "admin" && $role !== "staff") {
            header("Location: index.php?controller=rentals&action=index");
            exit;
        }
        $allRentals = $this->rentalModel->getAllWithDetails();
        $customers = array_filter($this->userModel->getAll(), function($u) { return $u["role"] === "customer"; });
        $availableVehicles = $this->vehicleModel->getAvailable();
        require __DIR__ . "/../views/rentals/manage.php";
    }

    public function request() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $customerId = $_SESSION["user_id"];
            $vehicleId = (int)($_POST["vehicle_id"] ?? 0);
            $startDate = $_POST["start_date"] ?? "";
            $endDate = $_POST["end_date"] ?? "";

            $vehicle = $this->vehicleModel->getById($vehicleId);
            if ($vehicle && !empty($startDate) && !empty($endDate)) {
                $d1 = new DateTime($startDate);
                $d2 = new DateTime($endDate);
                if ($d2 >= $d1) {
                    $days = $d1->diff($d2)->days + 1;
                    $totalCost = $days * (float)$vehicle["daily_rate"];
                    $this->rentalModel->create($customerId, $vehicleId, $startDate, $endDate, $totalCost, "pending");
                }
            }
        }
        header("Location: index.php?controller=rentals&action=index");
        exit;
    }

    public function walkin() {
        $role = $_SESSION["user_role"] ?? "";
        if ($role !== "super_admin" && $role !== "admin" && $role !== "staff") {
            header("Location: index.php?controller=dashboard&action=index");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $customerId = (int)($_POST["customer_id"] ?? 0);
            $vehicleId = (int)($_POST["vehicle_id"] ?? 0);
            $startDate = $_POST["start_date"] ?? "";
            $endDate = $_POST["end_date"] ?? "";
            $processedBy = $_SESSION["user_id"];

            $vehicle = $this->vehicleModel->getById($vehicleId);
            if ($vehicle && $customerId > 0 && !empty($startDate) && !empty($endDate)) {
                $d1 = new DateTime($startDate);
                $d2 = new DateTime($endDate);
                if ($d2 >= $d1) {
                    $days = $d1->diff($d2)->days + 1;
                    $totalCost = $days * (float)$vehicle["daily_rate"];
                    $this->rentalModel->create($customerId, $vehicleId, $startDate, $endDate, $totalCost, "rented", $processedBy);
                    $this->vehicleModel->updateStatus($vehicleId, "rented");
                }
            }
        }
        header("Location: index.php?controller=rentals&action=manage");
        exit;
    }

    public function approve() {
        $role = $_SESSION["user_role"] ?? "";
        if ($role !== "super_admin" && $role !== "admin" && $role !== "staff") {
            header("Location: index.php?controller=dashboard&action=index");
            exit;
        }

        $id = (int)($_GET["id"] ?? 0);
        $rental = $this->rentalModel->getById($id);
        if ($rental) {
            $this->rentalModel->updateStatus($id, "rented", $_SESSION["user_id"]);
            $this->vehicleModel->updateStatus($rental["vehicle_id"], "rented");
        }
        header("Location: index.php?controller=rentals&action=manage");
        exit;
    }

    public function returnVehicle() {
        $role = $_SESSION["user_role"] ?? "";
        if ($role !== "super_admin" && $role !== "admin" && $role !== "staff") {
            header("Location: index.php?controller=dashboard&action=index");
            exit;
        }

        $id = (int)($_GET["id"] ?? 0);
        $rental = $this->rentalModel->getById($id);
        if ($rental) {
            $this->rentalModel->updateStatus($id, "returned", $_SESSION["user_id"]);
            $this->vehicleModel->updateStatus($rental["vehicle_id"], "available");
        }
        header("Location: index.php?controller=rentals&action=manage");
        exit;
    }

    public function cancel() {
        $id = (int)($_GET["id"] ?? 0);
        $rental = $this->rentalModel->getById($id);
        if ($rental) {
            if ($_SESSION["user_role"] === "customer" && $rental["customer_id"] != $_SESSION["user_id"]) {
                header("Location: index.php?controller=rentals&action=index");
                exit;
            }
            $this->rentalModel->updateStatus($id, "cancelled", $_SESSION["user_id"]);
            $this->vehicleModel->updateStatus($rental["vehicle_id"], "available");
        }
        if ($_SESSION["user_role"] === "customer") {
            header("Location: index.php?controller=rentals&action=index");
        } else {
            header("Location: index.php?controller=rentals&action=manage");
        }
        exit;
    }
}
?>
