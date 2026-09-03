<?php
$pageTitle = "Manage Vehicles - RentEasy";
require_once __DIR__ . "/../layouts/header.php";
require_once __DIR__ . "/../layouts/sidebar.php";
?>

<div class="main-content">
    <div class="page-title-row">
        <h2>Manage Vehicles</h2>
        <div>
            <button class="btn btn-primary" onclick="openAddVehicleModal()">Add New Vehicle</button>
        </div>
    </div>

    <div class="card-grid" id="admin-vehicles-grid">
        <?php foreach ($vehicles as $veh) { ?>
            <div class="card" id="vehicle-card-<?php echo (int)$veh['id']; ?>">
                <img src="<?php echo htmlspecialchars($veh['image_path'] ?? 'assets/images/r15.webp'); ?>" class="card-img" alt="Vehicle">
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
                    <button class="btn btn-secondary btn-sm" onclick='openEditVehicleModal(<?php echo json_encode($veh); ?>)'>Edit</button>
                    <button class="btn btn-danger btn-sm" onclick="if(confirm('Are you sure you want to delete this vehicle?')) location.href='index.php?controller=vehicles&action=delete&id=<?php echo (int)$veh['id']; ?>';">Delete</button>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<div class="modal-bg" id="vehicle-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="vehicle-modal-title">Add Vehicle Record</h3>
            <button class="modal-close-btn" onclick="closeModal('vehicle-modal')">&times;</button>
        </div>
        <form action="index.php?controller=vehicles&action=add" method="POST" id="vehicleForm">
            <input type="hidden" name="id" id="veh_id" value="">
            
            <div class="form-group">
                <label for="veh_brand">Brand</label>
                <input type="text" name="brand" id="veh_brand" class="form-control" placeholder="Toyota" required>
            </div>
            <div class="form-group">
                <label for="veh_model">Model</label>
                <input type="text" name="model" id="veh_model" class="form-control" placeholder="Premio" required>
            </div>
            <div class="form-group">
                <label for="veh_plate">Plate Number</label>
                <input type="text" name="plate_number" id="veh_plate" class="form-control" placeholder="DHAKA-METRO-KA-1122" required>
            </div>
            <div class="form-group">
                <label for="veh_type">Vehicle Type</label>
                <select name="type" id="veh_type" class="form-control" required>
                    <option value="Sedan">Sedan</option>
                    <option value="SUV">SUV</option>
                    <option value="Motorcycle">Motorcycle</option>
                    <option value="Van">Van</option>
                </select>
            </div>
            <div class="form-group">
                <label for="veh_year">Year</label>
                <input type="number" name="year" id="veh_year" class="form-control" placeholder="2020" value="<?php echo date('Y'); ?>" required>
            </div>
            <div class="form-group">
                <label for="veh_rate">Daily Rate (BDT)</label>
                <input type="number" step="0.01" name="daily_rate" id="veh_rate" class="form-control" placeholder="3500" required>
            </div>
            <div class="form-group">
                <label for="veh_image">Image Path</label>
                <input type="text" name="image_path" id="veh_image" class="form-control" placeholder="uploads/premio.jpg">
            </div>
            <div class="form-group">
                <label for="veh_status">Status</label>
                <select name="status" id="veh_status" class="form-control">
                    <option value="available">Available</option>
                    <option value="rented">Rented</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-full margin-top-15" id="veh_submit_btn">Save Vehicle</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . "/../layouts/footer.php"; ?>
