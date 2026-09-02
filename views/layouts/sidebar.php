<?php
$currentRole = $_SESSION["user_role"] ?? "guest";
$currentAction = $_GET["controller"] ?? "dashboard";
?>
<div class="sidebar">
    <div class="sidebar-brand">RentEasy</div>
    
    <div>
        <a href="index.php?controller=dashboard&action=index" class="<?php echo ($currentAction === 'dashboard') ? 'active' : ''; ?>">Dashboard</a>
        
        <?php if ($currentRole === "customer" || $currentRole === "guest") { ?>
            <a href="index.php?controller=browse&action=index" class="<?php echo ($currentAction === 'browse') ? 'active' : ''; ?>">Browse Fleet</a>
            <a href="index.php?controller=rentals&action=index" class="<?php echo ($currentAction === 'rentals') ? 'active' : ''; ?>">My Bookings</a>
        <?php } ?>

        <?php if ($currentRole === "super_admin" || $currentRole === "admin") { ?>
            <a href="index.php?controller=vehicles&action=index" class="<?php echo ($currentAction === 'vehicles') ? 'active' : ''; ?>">Manage Fleet</a>
        <?php } ?>

        <?php if ($currentRole === "super_admin" || $currentRole === "admin" || $currentRole === "staff") { ?>
            <a href="index.php?controller=rentals&action=manage" class="<?php echo ($currentAction === 'rentals') ? 'active' : ''; ?>">Manage Rentals</a>
            <a href="index.php?controller=users&action=index" class="<?php echo ($currentAction === 'users') ? 'active' : ''; ?>">User Registry</a>
        <?php } ?>

        <?php if ($currentRole === "super_admin" || $currentRole === "admin") { ?>
            <a href="index.php?controller=reports&action=index" class="<?php echo ($currentAction === 'reports') ? 'active' : ''; ?>">Reports</a>
        <?php } ?>

        <?php if ($currentRole === "super_admin") { ?>
            <a href="index.php?controller=reports&action=index#settings">Settings</a>
        <?php } ?>

        <?php if (isset($_SESSION["user_id"])) { ?>
            <a href="index.php?controller=profile&action=index" class="<?php echo ($currentAction === 'profile') ? 'active' : ''; ?>">My Account</a>
            <a href="index.php?controller=auth&action=logout" class="sidebar-logout">Log Out</a>
        <?php } else { ?>
            <a href="index.php?controller=auth&action=login">Sign In</a>
            <a href="index.php?controller=auth&action=register">Register</a>
        <?php } ?>
    </div>
</div>
