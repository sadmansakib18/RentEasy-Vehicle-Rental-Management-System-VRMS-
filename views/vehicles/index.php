<?php
$pageTitle = "Manage Vehicles - RentEasy";
require_once __DIR__ . "/../layouts/header.php";
require_once __DIR__ . "/../layouts/sidebar.php";
?>

<div class="main-content">
    <div class="page-title-row">
        <h2>Manage Vehicles</h2>
        <div>
            <a href="index.php?controller=vehicles&action=add" class="btn btn-primary">Add New Vehicle</a>
        </div>
    </div>

    <div class="card-grid" id="admin-vehicles-grid">
        <?php foreach ($vehicles as $veh) { ?>
            <div class="card" id="vehicle-card-<?php echo (int)$veh['id']; ?>">
                <img src="<?php echo htmlspecialchars($veh['image_path'] ?? 'uploads/premio.jpg'); ?>" class="card-img" alt="Vehicle">
                <h3><?php echo htmlspecialchars($veh["brand"] . " " . $veh["model"]); ?></h3>
                <p>Plate: <strong><?php echo htmlspecialchars($veh["plate_number"]); ?></strong></p>
                <p>Type: <?php echo htmlspecialchars($veh["type"]); ?></p>
                <p>Year: <?php echo htmlspecialchars($veh["year"]); ?></p>
                <p>Rate: <?php echo htmlspecialchars(number_format($veh["daily_rate"], 2)); ?> BDT/day</p>
                <p>
                    <span class="badge <?php echo ($veh['status'] === 'available') ? 'badge-available' : (($veh['status'] === 'rented') ? 'badge-rented' : 'badge-maintenance'); ?>" id="status-badge-<?php echo (int)$veh['id']; ?>">
                        <?php echo htmlspecialchars(ucfirst($veh["status"])); ?>
                    </span>
                </p>
                <div class="flex-row flex-gap-5 margin-top-15">
                    <a href="index.php?controller=vehicles&action=edit&id=<?php echo (int)$veh['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
                    <a href="index.php?controller=vehicles&action=delete&id=<?php echo (int)$veh['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this vehicle?')">Delete</a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php require_once __DIR__ . "/../layouts/footer.php"; ?>
