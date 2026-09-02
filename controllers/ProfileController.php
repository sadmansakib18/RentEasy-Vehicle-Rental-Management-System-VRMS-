<?php
require_once __DIR__ . "/../models/UserModel.php";

class ProfileController {
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION["user_id"])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }
        $this->userModel = new UserModel();
    }

    public function index() {
        $userId = $_SESSION["user_id"];
        $user = $this->userModel->findById($userId);
        $profile_msg = $_GET["profile_msg"] ?? "";
        $password_msg = $_GET["password_msg"] ?? "";
        $password_error = $_GET["password_error"] ?? "";
        require __DIR__ . "/../views/profile/index.php";
    }

    public function update() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $userId = $_SESSION["user_id"];
            $name = trim($_POST["name"] ?? "");
            $phone = trim($_POST["phone"] ?? "");
            $address = trim($_POST["address"] ?? "");

            if (!empty($name)) {
                $this->userModel->update($userId, $name, $phone, $address);
                $_SESSION["user_name"] = $name;
                header("Location: index.php?controller=profile&action=index&profile_msg=Profile updated successfully");
                exit;
            }
        }
        header("Location: index.php?controller=profile&action=index");
        exit;
    }

    public function updatePassword() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $userId = $_SESSION["user_id"];
            $oldPassword = $_POST["old_password"] ?? "";
            $newPassword = $_POST["new_password"] ?? "";
            $confirmPassword = $_POST["confirm_password"] ?? "";

            $user = $this->userModel->findById($userId);
            if ($user && password_verify($oldPassword, $user["password"])) {
                if (strlen($newPassword) >= 6 && $newPassword === $confirmPassword) {
                    $this->userModel->updatePassword($userId, $newPassword);
                    header("Location: index.php?controller=profile&action=index&password_msg=Password changed successfully");
                    exit;
                } else {
                    header("Location: index.php?controller=profile&action=index&password_error=New passwords must be at least 6 characters and match");
                    exit;
                }
            } else {
                header("Location: index.php?controller=profile&action=index&password_error=Incorrect current password");
                exit;
            }
        }
        header("Location: index.php?controller=profile&action=index");
        exit;
    }
}
?>
