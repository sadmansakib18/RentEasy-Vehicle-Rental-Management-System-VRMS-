<?php
$pageTitle = "RentEasy - Vehicle Rental Management System";
require_once __DIR__ . "/layouts/header.php";
?>

<nav class="navbar">
    <div class="logo">RentEasy</div>
    <div>
        <a href="index.php?controller=home">Home</a>
        <?php if (isset($_SESSION["user_id"])) { ?>
            <a href="index.php?controller=dashboard&action=index" class="btn btn-primary btn-nav-primary">Dashboard</a>
            <a href="index.php?controller=auth&action=logout" class="btn btn-secondary btn-nav-secondary">Log Out</a>
        <?php } else { ?>
            <a href="index.php?controller=auth&action=login" class="btn btn-primary btn-nav-primary">Sign In</a>
            <a href="index.php?controller=auth&action=register" class="btn btn-secondary btn-nav-secondary">Register</a>
        <?php } ?>
    </div>
</nav>

<div class="hero">
    <div class="container">
        <h1>Rent Vehicles Made Easy</h1>
        <p>Select your favorite car, book instantly, and drive away without any manual hassle.</p>
        <div class="margin-top-20">
            <a href="index.php?controller=browse&action=index" class="btn btn-primary btn-lg">Browse Fleet Now</a>
        </div>
    </div>
</div>

<div class="container margin-top-40">
    <h2 class="text-center margin-bottom-30">Featured Fleet</h2>
    
    <div class="card-grid">
        <?php foreach (array_slice($vehicles, 0, 6) as $veh) { ?>
            <div class="card">
                <img src="<?php echo htmlspecialchars($veh['image_path'] ?? 'assets/images/r15.webp'); ?>" class="card-img" alt="Vehicle">
                <h3><?php echo htmlspecialchars($veh["brand"] . " " . $veh["model"]); ?></h3>
                <p>Type: <?php echo htmlspecialchars($veh["type"]); ?></p>
                <p>Year: <?php echo htmlspecialchars($veh["year"]); ?></p>
                <p>Rate: <strong><?php echo htmlspecialchars(number_format($veh["daily_rate"], 2)); ?> BDT / day</strong></p>
                <a href="index.php?controller=browse&action=index" class="btn btn-primary btn-card-block">Book Car</a>
            </div>
        <?php } ?>
    </div>
</div>

<div class="footer">
    <p>&copy; 2026 RentEasy VRMS. All rights reserved.</p>
</div>

<?php require_once __DIR__ . "/layouts/footer.php"; ?>
