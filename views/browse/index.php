<?php
$pageTitle = "Browse Fleet - RentEasy";
require_once __DIR__ . "/../layouts/header.php";
require_once __DIR__ . "/../layouts/sidebar.php";
?>

<div class="main-content">
    <div class="page-title-row">
        <h2>Browse Available Fleet</h2>
    </div>

    <div class="card margin-bottom-30">
        <div class="flex-row flex-gap-10" style="flex-wrap: wrap;">
            <label>Filter Category:</label>
            <button class="btn btn-primary btn-sm category-filter-btn" data-category="All">All</button>
            <button class="btn btn-secondary btn-sm category-filter-btn" data-category="Sedan">Sedan</button>
            <button class="btn btn-secondary btn-sm category-filter-btn" data-category="SUV">SUV</button>
            <button class="btn btn-secondary btn-sm category-filter-btn" data-category="Motorcycle">Motorcycle</button>
            
            <div style="margin-left: auto;">
                <input type="text" id="live-search-input" class="form-control" placeholder="Search brand, model, plate..." style="width: 250px; padding: 6px 12px; font-size: 13px;">
            </div>
        </div>
    </div>

    <div class="card-grid" id="vehicles-grid-container">
        <?php if (empty($vehicles)) { ?>
            <div class="card" style="grid-column: 1 / -1; text-align: center; color: #64748b;">
                No vehicles found matching your criteria.
            </div>
        <?php } else { ?>
            <?php foreach ($vehicles as $veh) { ?>
                <div class="card vehicle-item-card" data-category="<?php echo htmlspecialchars($veh['type']); ?>" data-text="<?php echo htmlspecialchars(strtolower($veh['brand'] . ' ' . $veh['model'] . ' ' . $veh['plate_number'])); ?>">
                    <img src="<?php echo htmlspecialchars($veh['image_path'] ?? 'assets/images/r15.webp'); ?>" class="card-img" alt="Vehicle">
                    <h3><?php echo htmlspecialchars($veh["brand"] . " " . $veh["model"]); ?></h3>
                    <p>Category: <?php echo htmlspecialchars($veh["type"]); ?></p>
                    <p>Year: <?php echo htmlspecialchars($veh["year"]); ?></p>
                    <p>Plate: <?php echo htmlspecialchars($veh["plate_number"]); ?></p>
                    <p>Rate: <strong><?php echo htmlspecialchars(number_format($veh["daily_rate"], 2)); ?> BDT/day</strong></p>
                    <p>
                        <?php if ($veh["status"] === "available") { ?>
                            <span class="badge badge-available">Available</span>
                        <?php } else { ?>
                            <span class="badge badge-rented"><?php echo htmlspecialchars(ucfirst($veh["status"])); ?></span>
                        <?php } ?>
                    </p>
                    
                    <?php if ($veh["status"] === "available") { ?>
                        <button class="btn btn-primary btn-full margin-top-15" 
                                onclick="openBookingModal(<?php echo (int)$veh['id']; ?>, '<?php echo htmlspecialchars($veh['brand'] . ' ' . $veh['model']); ?>', <?php echo (float)$veh['daily_rate']; ?>)">
                            Rent Vehicle
                        </button>
                    <?php } else { ?>
                        <button class="btn btn-secondary btn-full margin-top-15 disabled-input" disabled>
                            Currently Unavailable
                        </button>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<div class="modal-bg" id="booking-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Vehicle Rental Request</h3>
            <button class="modal-close-btn" onclick="closeModal('booking-modal')">&times;</button>
        </div>
        <form action="index.php?controller=rentals&action=request" method="POST" id="bookingForm" onsubmit="return handleBookingSubmit(event)">
            <input type="hidden" name="vehicle_id" id="modal_vehicle_id">
            
            <div class="modal-summary-box">
                <label class="text-muted">Vehicle Selected:</label>
                <h3 id="modal_veh_title">Toyota Premio</h3>
                <div class="modal-summary-item">
                    <span>Daily Price:</span>
                    <strong id="daily_rate_val" data-rate="3500">3,500 BDT</strong>
                </div>
            </div>

            <div class="form-group">
                <label for="start_date">Pickup Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label for="end_date">Return Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="card modal-calc-box margin-bottom-30 hidden" id="total_cost_calc">
                <div class="modal-summary-item">
                    <span>Duration:</span>
                    <strong><span id="rental_days">1</span> Days</strong>
                </div>
                <div class="modal-summary-item">
                    <span>Total Cost:</span>
                    <strong id="total_cost_val">3,500 BDT</strong>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Submit Rental Request</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . "/../layouts/footer.php"; ?>
