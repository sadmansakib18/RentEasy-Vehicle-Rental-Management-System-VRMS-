<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$controller = $_GET["controller"] ?? (isset($_SESSION["user_id"]) ? "dashboard" : "home");
$action = $_GET["action"] ?? "index";

switch ($controller) {
    case "auth":
        require_once __DIR__ . "/controllers/AuthController.php";
        $ctrl = new AuthController();
        break;
    case "dashboard":
        require_once __DIR__ . "/controllers/DashboardController.php";
        $ctrl = new DashboardController();
        break;
    case "browse":
        require_once __DIR__ . "/controllers/BrowseController.php";
        $ctrl = new BrowseController();
        break;
    case "vehicles":
        require_once __DIR__ . "/controllers/VehicleController.php";
        $ctrl = new VehicleController();
        break;
    case "rentals":
        require_once __DIR__ . "/controllers/RentalController.php";
        $ctrl = new RentalController();
        break;
    case "users":
        require_once __DIR__ . "/controllers/UserController.php";
        $ctrl = new UserController();
        break;
    case "reports":
        require_once __DIR__ . "/controllers/ReportController.php";
        $ctrl = new ReportController();
        break;
    case "profile":
        require_once __DIR__ . "/controllers/ProfileController.php";
        $ctrl = new ProfileController();
        break;
    case "ajax":
        require_once __DIR__ . "/controllers/AjaxController.php";
        $ctrl = new AjaxController();
        break;
    case "home":
    default:
        require_once __DIR__ . "/controllers/BrowseController.php";
        $ctrl = new BrowseController();
        $action = "home";
        break;
}

if (method_exists($ctrl, $action)) {
    $ctrl->$action();
} else {
    header("Location: index.php?controller=dashboard&action=index");
    exit;
}
?>
