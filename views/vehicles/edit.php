<?php
$pageTitle = "Edit Vehicle - RentEasy";
require_once __DIR__ . "/../layouts/header.php";
require_once __DIR__ . "/../layouts/sidebar.php";
?>

<div class="main-content">
    <div class="page-title-row">
        <h2>Edit Vehicle: <?php echo htmlspecialchars($vehicle["brand"] . " " . $vehicle["model"]); ?></h2>
        <div>
            <a href="index.php?controller=vehicles&action=index" class="btn btn-secondary">Back to Fleet</a>
        </div>
    </div>

    <div class="card w-max-600">
        <form action="index.php?controller=vehicles&action=edit" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo (int)$vehicle['id']; ?>">

            <div class="form-group">
                <label for="veh_brand">Brand</label>
                <input type="text" name="brand" id="veh_brand" class="form-control" value="<?php echo htmlspecialchars($vehicle['brand']); ?>" required>
            </div>

            <div class="form-group">
                <label for="veh_model">Model</label>
                <input type="text" name="model" id="veh_model" class="form-control" value="<?php echo htmlspecialchars($vehicle['model']); ?>" required>
            </div>

            <div class="form-group">
                <label for="veh_plate">Plate Number</label>
                <input type="text" name="plate_number" id="veh_plate" class="form-control" value="<?php echo htmlspecialchars($vehicle['plate_number']); ?>" required>
            </div>

            <div class="form-group">
                <label for="veh_type">Vehicle Type</label>
                <select name="type" id="veh_type" class="form-control" required>
                    <option value="Sedan" <?php echo ($vehicle['type'] === 'Sedan') ? 'selected' : ''; ?>>Sedan</option>
                    <option value="SUV" <?php echo ($vehicle['type'] === 'SUV') ? 'selected' : ''; ?>>SUV</option>
                    <option value="Motorcycle" <?php echo ($vehicle['type'] === 'Motorcycle') ? 'selected' : ''; ?>>Motorcycle</option>
                    <option value="Van" <?php echo ($vehicle['type'] === 'Van') ? 'selected' : ''; ?>>Van</option>
                </select>
            </div>

            <div class="form-group">
                <label for="veh_year">Manufacturing Year</label>
                <input type="number" name="year" id="veh_year" class="form-control" value="<?php echo (int)$vehicle['year']; ?>" required>
            </div>

            <div class="form-group">
                <label for="veh_rate">Daily Rental Rate (BDT)</label>
                <input type="number" step="0.01" name="daily_rate" id="veh_rate" class="form-control" value="<?php echo (float)$vehicle['daily_rate']; ?>" required>
            </div>

            <div class="form-group">
                <label>Current Image</label>
                <div>
                    <img src="<?php echo htmlspecialchars($vehicle['image_path'] ?? 'uploads/premio.jpg'); ?>" style="width: 140px; height: 90px; object-fit: cover; border-radius: 4px; border: 1px solid #cbd5e1; display: block; margin-bottom: 8px;" alt="Current Image">
                </div>
                <label for="veh_image">Upload New Image (Optional)</label>
                <input type="file" name="vehicle_image" id="veh_image" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
                <label for="veh_status">Availability Status</label>
                <select name="status" id="veh_status" class="form-control">
                    <option value="available" <?php echo ($vehicle['status'] === 'available') ? 'selected' : ''; ?>>Available</option>
                    <option value="rented" <?php echo ($vehicle['status'] === 'rented') ? 'selected' : ''; ?>>Rented</option>
                    <option value="maintenance" <?php echo ($vehicle['status'] === 'maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                </select>
            </div>

            <div class="flex-row flex-gap-10 margin-top-20">
                <button type="submit" class="btn btn-primary">Update Vehicle</button>
                <a href="index.php?controller=vehicles&action=index" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . "/../layouts/footer.php"; ?>
