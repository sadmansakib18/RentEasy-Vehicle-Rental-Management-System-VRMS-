<?php
$pageTitle = "Add New Vehicle - RentEasy";
require_once __DIR__ . "/../layouts/header.php";
require_once __DIR__ . "/../layouts/sidebar.php";
?>

<div class="main-content">
    <div class="page-title-row">
        <h2>Add New Vehicle</h2>
        <div>
            <a href="index.php?controller=vehicles&action=index" class="btn btn-secondary">Back to Fleet</a>
        </div>
    </div>

    <div class="card w-max-600">
        <form action="index.php?controller=vehicles&action=add" method="POST" enctype="multipart/form-data">
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
                <label for="veh_year">Manufacturing Year</label>
                <input type="number" name="year" id="veh_year" class="form-control" placeholder="2020" value="<?php echo date('Y'); ?>" required>
            </div>

            <div class="form-group">
                <label for="veh_rate">Daily Rental Rate (BDT)</label>
                <input type="number" step="0.01" name="daily_rate" id="veh_rate" class="form-control" placeholder="3500" required>
            </div>

            <div class="form-group">
                <label for="veh_image">Vehicle Image File</label>
                <input type="file" name="vehicle_image" id="veh_image" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
                <label for="veh_status">Availability Status</label>
                <select name="status" id="veh_status" class="form-control">
                    <option value="available">Available</option>
                    <option value="rented">Rented</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>

            <div class="flex-row flex-gap-10 margin-top-20">
                <button type="submit" class="btn btn-primary">Save Vehicle</button>
                <a href="index.php?controller=vehicles&action=index" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . "/../layouts/footer.php"; ?>
