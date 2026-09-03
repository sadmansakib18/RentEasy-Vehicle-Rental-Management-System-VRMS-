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
            $image = "uploads/premio.jpg";

            if (isset($_FILES["vehicle_image"]) && $_FILES["vehicle_image"]["error"] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . "/../uploads/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileExt = pathinfo($_FILES["vehicle_image"]["name"], PATHINFO_EXTENSION);
                $newFilename = time() . "_" . uniqid() . "." . $fileExt;
                $targetFile = $uploadDir . $newFilename;
                if (move_uploaded_file($_FILES["vehicle_image"]["tmp_name"], $targetFile)) {
                    $image = "uploads/" . $newFilename;
                }
            }

            if (!empty($brand) && !empty($model) && !empty($plate) && $rate > 0) {
                $this->vehicleModel->create($plate, $brand, $model, $type, $year, $rate, $status, $image);
            }
            header("Location: index.php?controller=vehicles&action=index");
            exit;
        }

        require __DIR__ . "/../views/vehicles/add.php";
    }

    public function edit() {
        $id = (int)($_GET["id"] ?? $_POST["id"] ?? 0);
        $vehicle = $this->vehicleModel->getById($id);

        if (!$vehicle) {
            header("Location: index.php?controller=vehicles&action=index");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $brand = trim($_POST["brand"] ?? "");
            $model = trim($_POST["model"] ?? "");
            $plate = trim($_POST["plate_number"] ?? "");
            $type = trim($_POST["type"] ?? "Sedan");
            $year = (int)($_POST["year"] ?? date("Y"));
            $rate = (float)($_POST["daily_rate"] ?? 0);
            $status = trim($_POST["status"] ?? "available");
            $image = $vehicle["image_path"] ?? "uploads/premio.jpg";

            if (isset($_FILES["vehicle_image"]) && $_FILES["vehicle_image"]["error"] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . "/../uploads/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileExt = pathinfo($_FILES["vehicle_image"]["name"], PATHINFO_EXTENSION);
                $newFilename = time() . "_" . uniqid() . "." . $fileExt;
                $targetFile = $uploadDir . $newFilename;
                if (move_uploaded_file($_FILES["vehicle_image"]["tmp_name"], $targetFile)) {
                    $image = "uploads/" . $newFilename;
                }
            }

            if (!empty($brand) && !empty($model) && !empty($plate) && $rate > 0) {
                $this->vehicleModel->update($id, $plate, $brand, $model, $type, $year, $rate, $status, $image);
            }
            header("Location: index.php?controller=vehicles&action=index");
            exit;
        }

        require __DIR__ . "/../views/vehicles/edit.php";
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
