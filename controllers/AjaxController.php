<?php
require_once __DIR__ . "/../models/UserModel.php";
require_once __DIR__ . "/../models/VehicleModel.php";
require_once __DIR__ . "/../models/RentalModel.php";

class AjaxController {
    private $userModel;
    private $vehicleModel;
    private $rentalModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        header("Content-Type: application/json");
        $this->userModel = new UserModel();
        $this->vehicleModel = new VehicleModel();
        $this->rentalModel = new RentalModel();
    }

    public function checkEmail() {
        $email = trim($_GET["email"] ?? "");
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["valid" => false, "available" => false, "message" => "Invalid email"]);
            exit;
        }
        $exists = $this->userModel->emailExists($email);
        echo json_encode([
            "valid" => true,
            "available" => !$exists,
            "message" => $exists ? "Email is already registered" : "Email is available"
        ]);
        exit;
    }

    public function searchVehicles() {
        $category = trim($_GET["category"] ?? "");
        $keyword = trim($_GET["keyword"] ?? "");
        $results = $this->vehicleModel->search($category, $keyword);
        echo json_encode(["success" => true, "vehicles" => $results]);
        exit;
    }

    public function calculateCost() {
        $vehicleId = (int)($_POST["vehicle_id"] ?? 0);
        $startDate = $_POST["start_date"] ?? "";
        $endDate = $_POST["end_date"] ?? "";

        $vehicle = $this->vehicleModel->getById($vehicleId);
        if (!$vehicle || empty($startDate) || empty($endDate)) {
            echo json_encode(["success" => false, "message" => "Missing data"]);
            exit;
        }

        $d1 = new DateTime($startDate);
        $d2 = new DateTime($endDate);
        if ($d2 < $d1) {
            echo json_encode(["success" => false, "message" => "Return date cannot be before pickup date"]);
            exit;
        }

        $days = $d1->diff($d2)->days + 1;
        $rate = (float)$vehicle["daily_rate"];
        $totalCost = $days * $rate;

        echo json_encode([
            "success" => true,
            "days" => $days,
            "daily_rate" => $rate,
            "total_cost" => $totalCost,
            "formatted_cost" => number_format($totalCost, 2) . " BDT"
        ]);
        exit;
    }

    public function approveRental() {
        if (!isset($_SESSION["user_id"]) || !in_array($_SESSION["user_role"] ?? "", ["super_admin", "admin", "staff"])) {
            echo json_encode(["success" => false, "message" => "Unauthorized"]);
            exit;
        }

        $id = (int)($_POST["id"] ?? 0);
        $rental = $this->rentalModel->getById($id);
        if ($rental) {
            $this->rentalModel->updateStatus($id, "rented", $_SESSION["user_id"]);
            $this->vehicleModel->updateStatus($rental["vehicle_id"], "rented");
            echo json_encode(["success" => true, "status" => "rented"]);
            exit;
        }
        echo json_encode(["success" => false, "message" => "Rental not found"]);
        exit;
    }

    public function returnRental() {
        if (!isset($_SESSION["user_id"]) || !in_array($_SESSION["user_role"] ?? "", ["super_admin", "admin", "staff"])) {
            echo json_encode(["success" => false, "message" => "Unauthorized"]);
            exit;
        }

        $id = (int)($_POST["id"] ?? 0);
        $rental = $this->rentalModel->getById($id);
        if ($rental) {
            $this->rentalModel->updateStatus($id, "returned", $_SESSION["user_id"]);
            $this->vehicleModel->updateStatus($rental["vehicle_id"], "available");
            echo json_encode(["success" => true, "status" => "returned"]);
            exit;
        }
        echo json_encode(["success" => false, "message" => "Rental not found"]);
        exit;
    }

    public function toggleVehicleStatus() {
        if (!isset($_SESSION["user_id"]) || !in_array($_SESSION["user_role"] ?? "", ["super_admin", "admin"])) {
            echo json_encode(["success" => false, "message" => "Unauthorized"]);
            exit;
        }

        $id = (int)($_POST["id"] ?? 0);
        $status = $_POST["status"] ?? "available";
        $this->vehicleModel->updateStatus($id, $status);
        echo json_encode(["success" => true, "status" => $status]);
        exit;
    }
}
?>
