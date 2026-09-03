<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../../models/SettingModel.php";

$systemName = get_system_setting("system_name", "RentEasy VRMS");
$pageTitle = isset($pageTitle) ? ($pageTitle . " - " . $systemName) : $systemName;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
