<?php
require_once __DIR__ . "/../models/ReportModel.php";
require_once __DIR__ . "/../models/SettingModel.php";

class ReportController {
    private $reportModel;
    private $settingModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION["user_id"])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        $role = $_SESSION["user_role"] ?? "";
        if ($role !== "super_admin" && $role !== "admin") {
            header("Location: index.php?controller=dashboard&action=index");
            exit;
        }
        $this->reportModel = new ReportModel();
        $this->settingModel = new SettingModel();
    }

    public function index() {
        $summary = $this->reportModel->getSummaryNumbers();
        $occupancy = $this->reportModel->getOccupancyStats();
        $topCustomers = $this->reportModel->getTopCustomers();
        $settings = $this->settingModel->getAll();
        require __DIR__ . "/../views/reports/index.php";
    }

    public function updateSettings() {
        if ($_SESSION["user_role"] !== "super_admin") {
            header("Location: index.php?controller=reports&action=index");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $updated = [
                "system_name" => trim($_POST["system_name"] ?? "RentEasy VRMS"),
                "currency" => trim($_POST["currency"] ?? "BDT"),
                "contact_email" => trim($_POST["contact_email"] ?? "support@renteasy.com"),
                "contact_phone" => trim($_POST["contact_phone"] ?? "+88029999999"),
                "allow_registration" => isset($_POST["allow_registration"]) ? "1" : "0"
            ];
            $this->settingModel->updateBatch($updated);
        }
        header("Location: index.php?controller=reports&action=index#settings");
        exit;
    }
}
?>
