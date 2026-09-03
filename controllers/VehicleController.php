<?php
require_once __DIR__ . "/../models/VehicleModel.php";

class VehicleController {
    private $vehicleModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION["user_id"])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        $role = $_SESSION["user_role"] ?? "";
        if ($role !== "super_admin" && $role !== "admin" && $role !== "staff") {
            header("Location: index.php?controller=dashboard&action=index");
            exit;
        }
        $this->vehicleModel = new VehicleModel();
    }

    public function index() {
        $vehicles = $this->vehicleModel->getAll();
        require __DIR__ . "/../views/vehicles/index.php";
    }

    public function add() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $brand = trim($_POST["brand"] ?? "");
            $model = trim($_POST["model"] ?? "");
            $plate = trim($_POST["plate_number"] ?? "");
            $type = trim($_POST["type"] ?? "Sedan");
            $year = (int)($_POST["year"] ?? date("Y"));
            $rate = (float)($_POST["daily_rate"] ?? 0);
            $status = trim($_POST["status"] ?? "available");
            $image = !empty($_POST["image_path"]) ? trim($_POST["image_path"]) : "uploads/premio.jpg";

            if (!empty($brand) && !empty($model) && !empty($plate) && $rate > 0) {
                $this->vehicleModel->create($plate, $brand, $model, $type, $year, $rate, $status, $image);
            }
        }
        header("Location: index.php?controller=vehicles&action=index");
        exit;
    }

    public function edit() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = (int)($_POST["id"] ?? 0);
            $brand = trim($_POST["brand"] ?? "");
            $model = trim($_POST["model"] ?? "");
            $plate = trim($_POST["plate_number"] ?? "");
            $type = trim($_POST["type"] ?? "Sedan");
            $year = (int)($_POST["year"] ?? date("Y"));
            $rate = (float)($_POST["daily_rate"] ?? 0);
            $status = trim($_POST["status"] ?? "available");
            
            $existing = $this->vehicleModel->getById($id);
            $image = !empty($_POST["image_path"]) ? trim($_POST["image_path"]) : ($existing["image_path"] ?? "uploads/premio.jpg");

            if ($id > 0 && !empty($brand) && !empty($model) && !empty($plate) && $rate > 0) {
                $this->vehicleModel->update($id, $plate, $brand, $model, $type, $year, $rate, $status, $image);
            }
        }
        header("Location: index.php?controller=vehicles&action=index");
        exit;
    }

    public function delete() {
        $id = (int)($_GET["id"] ?? 0);
        if ($id > 0) {
            $this->vehicleModel->delete($id);
        }
        header("Location: index.php?controller=vehicles&action=index");
        exit;
    }
}
?>
