<?php
require_once __DIR__ . "/../models/UserModel.php";

class UserController {
    private $userModel;

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
        $this->userModel = new UserModel();
    }

    public function index() {
        $users = $this->userModel->getAll();
        require __DIR__ . "/../views/users/index.php";
    }

    public function create() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $name = trim($_POST["name"] ?? "");
            $email = trim($_POST["email"] ?? "");
            $phone = trim($_POST["phone"] ?? "");
            $address = trim($_POST["address"] ?? "");
            $role = trim($_POST["role"] ?? "staff");
            $password = $_POST["password"] ?? "";

            if (!empty($name) && !empty($email) && !empty($password) && !$this->userModel->emailExists($email)) {
                $this->userModel->create($name, $email, $password, $phone, $address, $role);
            }
        }
        header("Location: index.php?controller=users&action=index");
        exit;
    }

    public function toggleStatus() {
        $id = (int)($_GET["id"] ?? 0);
        $user = $this->userModel->findById($id);
        if ($user) {
            $newStatus = ($user["status"] === "active") ? "inactive" : "active";
            $this->userModel->updateStatus($id, $newStatus);
        }
        header("Location: index.php?controller=users&action=index");
        exit;
    }

    public function delete() {
        $id = (int)($_GET["id"] ?? 0);
        if ($id > 0 && $id != $_SESSION["user_id"]) {
            $this->userModel->delete($id);
        }
        header("Location: index.php?controller=users&action=index");
        exit;
    }
}
?>
