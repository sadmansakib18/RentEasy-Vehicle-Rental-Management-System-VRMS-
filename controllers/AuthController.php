<?php
require_once __DIR__ . "/../models/UserModel.php";

class AuthController {
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new UserModel();
    }

    public function login() {
        $email_error = "";
        $password_error = "";
        $general_error = "";
        $email = $_COOKIE["user_email"] ?? "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = trim($_POST["email"] ?? "");
            $password = $_POST["password"] ?? "";
            $remember = isset($_POST["remember"]);

            if (empty($email)) {
                $email_error = "Email is required";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email_error = "Invalid email format";
            }

            if (empty($password)) {
                $password_error = "Password is required";
            }

            if (empty($email_error) && empty($password_error)) {
                $user = $this->userModel->findByEmail($email);
                if ($user && password_verify($password, $user["password"])) {
                    if ($user["status"] !== "active") {
                        $general_error = "Your account is inactive. Please contact support.";
                    } else {
                        $_SESSION["user_id"] = $user["id"];
                        $_SESSION["user_name"] = $user["name"];
                        $_SESSION["user_email"] = $user["email"];
                        $_SESSION["user_role"] = $user["role"];

                        if ($remember) {
                            setcookie("user_email", $email, time() + (86400 * 30), "/");
                        } else {
                            setcookie("user_email", "", time() - 3600, "/");
                        }

                        header("Location: index.php?controller=dashboard&action=index");
                        exit;
                    }
                } else {
                    $general_error = "Invalid email or password";
                }
            }
        }

        require __DIR__ . "/../views/auth/login.php";
    }

    public function register() {
        $name_error = "";
        $email_error = "";
        $phone_error = "";
        $address_error = "";
        $password_error = "";
        $general_error = "";

        $name = "";
        $email = "";
        $phone = "";
        $address = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $name = trim($_POST["name"] ?? "");
            $email = trim($_POST["email"] ?? "");
            $phone = trim($_POST["phone"] ?? "");
            $address = trim($_POST["address"] ?? "");
            $password = $_POST["password"] ?? "";
            $confirm_password = $_POST["confirm_password"] ?? "";

            if (empty($name)) {
                $name_error = "Full Name is required";
            }

            if (empty($email)) {
                $email_error = "Email address is required";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email_error = "Invalid email address format";
            } elseif ($this->userModel->emailExists($email)) {
                $email_error = "This email is already registered";
            }

            if (empty($phone)) {
                $phone_error = "Phone number is required";
            }

            if (empty($address)) {
                $address_error = "Address is required";
            }

            if (empty($password)) {
                $password_error = "Password is required";
            } elseif (strlen($password) < 6) {
                $password_error = "Password must be at least 6 characters";
            } elseif ($password !== $confirm_password) {
                $password_error = "Passwords do not match";
            }

            if (empty($name_error) && empty($email_error) && empty($phone_error) && empty($address_error) && empty($password_error)) {
                $created = $this->userModel->create($name, $email, $password, $phone, $address, "customer");
                if ($created) {
                    header("Location: index.php?controller=auth&action=login&registered=true");
                    exit;
                } else {
                    $general_error = "Registration failed. Please try again.";
                }
            }
        }

        require __DIR__ . "/../views/auth/register.php";
    }

    public function logout() {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        session_destroy();
        setcookie("user_email", "", time() - 3600, "/");
        header("Location: index.php?controller=auth&action=login");
        exit;
    }
}
?>
