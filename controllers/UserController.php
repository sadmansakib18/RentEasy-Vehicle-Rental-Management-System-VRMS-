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
        $myRole = $_SESSION["user_role"] ?? "";
        if ($myRole !== "super_admin" && $myRole !== "admin") {
            header("Location: index.php?controller=users&action=index");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $name = trim($_POST["name"] ?? "");
            $email = trim($_POST["email"] ?? "");
            $phone = trim($_POST["phone"] ?? "");
            $address = trim($_POST["address"] ?? "");
            $role = trim($_POST["role"] ?? "staff");
            $password = $_POST["password"] ?? "";

            if ($myRole === "admin" && $role !== "staff") {
                $role = "staff";
            }

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
        $myRole = $_SESSION["user_role"] ?? "";
        $myId = $_SESSION["user_id"] ?? 0;

        if ($user && $id != $myId) {
            $canManage = false;
            if ($myRole === "super_admin" && $user["role"] !== "super_admin") {
                $canManage = true;
            } elseif ($myRole === "admin" && in_array($user["role"], ["staff", "customer"])) {
                $canManage = true;
            } elseif ($myRole === "staff" && $user["role"] === "customer") {
                $canManage = true;
            }

            if ($canManage) {
                $newStatus = ($user["status"] === "active") ? "inactive" : "active";
                $this->userModel->updateStatus($id, $newStatus);
            }
        }
        header("Location: index.php?controller=users&action=index");
        exit;
    }

    public function delete() {
        $id = (int)($_GET["id"] ?? 0);
        $myRole = $_SESSION["user_role"] ?? "";
        $myId = $_SESSION["user_id"] ?? 0;

        if ($id > 0 && $id != $myId && $myRole === "super_admin") {
            $this->userModel->delete($id);
        }
        header("Location: index.php?controller=users&action=index");
        exit;
    }
}
?>
