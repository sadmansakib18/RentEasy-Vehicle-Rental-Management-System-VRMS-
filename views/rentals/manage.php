<?php
$currency = get_system_setting("currency", "BDT");
$pageTitle = "Manage Rentals";
require_once __DIR__ . "/../layouts/header.php";
require_once __DIR__ . "/../layouts/sidebar.php";
?>

<div class="main-content">
    <div class="page-title-row">
        <h2>Rental Operations Log</h2>
        <div>
            <button class="btn btn-primary" onclick="openModal('walkin-modal')">Process Walk-In Booking</button>
        </div>
    </div>

    <div class="table-container">
        <h3>Master Transactions History</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Vehicle</th>
                    <th>Dates</th>
                    <th>Cost</th>
                    <th>Status</th>
                    <th>Processed By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($allRentals)) { ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: #94a3b8; padding: 20px;">No rental records found.</td>
                    </tr>
                <?php } else { ?>
                    <?php foreach ($allRentals as $rent) { ?>
                        <tr id="rental-row-<?php echo (int)$rent['id']; ?>">
                            <td>#<?php echo (int)$rent["id"]; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($rent["customer_name"]); ?></strong><br>
                                <span class="text-muted"><?php echo htmlspecialchars($rent["customer_email"]); ?></span>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($rent["brand"] . " " . $rent["model"]); ?></strong><br>
                                <span class="text-muted"><?php echo htmlspecialchars($rent["plate_number"]); ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($rent["start_date"] . " to " . $rent["end_date"]); ?></td>
                            <td><strong><?php echo htmlspecialchars(number_format($rent["total_cost"], 2)); ?> <?php echo htmlspecialchars($currency); ?></strong></td>
                            <td>
                                <?php
                                $badgeClass = "badge-pending";
                                if ($rent["status"] === "rented") $badgeClass = "badge-rented";
                                if ($rent["status"] === "approved") $badgeClass = "badge-available";
                                if ($rent["status"] === "returned") $badgeClass = "badge-available";
                                if ($rent["status"] === "cancelled") $badgeClass = "badge-maintenance";
                                ?>
                                <span class="badge <?php echo $badgeClass; ?>" id="rental-badge-<?php echo (int)$rent['id']; ?>">
                                    <?php echo htmlspecialchars(ucfirst($rent["status"])); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($rent["processor_name"] ?? "-"); ?></td>
                            <td id="rental-actions-<?php echo (int)$rent['id']; ?>">
                                <?php if ($rent["status"] === "pending") { ?>
                                    <button class="btn btn-success btn-sm" onclick="ajaxApproveRental(<?php echo (int)$rent['id']; ?>)">Approve</button>
                                    <a href="index.php?controller=rentals&action=cancel&id=<?php echo (int)$rent['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Cancel request?')">Reject</a>
                                <?php } elseif ($rent["status"] === "rented" || $rent["status"] === "approved") { ?>
                                    <button class="btn btn-primary btn-sm" onclick="ajaxReturnRental('<?php echo (int)$rent['id']; ?>')">Process Return</button>
                                <?php } else { ?>
                                    <span class="text-muted">Completed</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal-bg" id="walkin-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Process Walk-In Booking</h3>
            <button class="modal-close-btn" onclick="closeModal('walkin-modal')">&times;</button>
        </div>
        <form action="index.php?controller=rentals&action=walkin" method="POST" id="walkinForm">
            <div class="form-group">
                <label for="walkin_customer">Select Customer</label>
                <select name="customer_id" id="walkin_customer" class="form-control" required>
                    <option value="">-- Choose Customer --</option>
                    <?php foreach ($customers as $cust) { ?>
                        <option value="<?php echo (int)$cust['id']; ?>">
                            <?php echo htmlspecialchars($cust["name"] . " (" . $cust["email"] . ")"); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="walkin_vehicle">Select Vehicle</label>
                <select name="vehicle_id" id="walkin_vehicle" class="form-control" required>
                    <option value="">-- Choose Vehicle --</option>
                    <?php foreach ($availableVehicles as $veh) { ?>
                        <option value="<?php echo (int)$veh['id']; ?>" data-rate="<?php echo (float)$veh['daily_rate']; ?>">
                            <?php echo htmlspecialchars($veh["brand"] . " " . $veh["model"] . " - " . number_format($veh["daily_rate"], 2) . " " . $currency . "/day"); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="walkin_start_date">Start Date</label>
                <input type="date" name="start_date" id="walkin_start_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label for="walkin_end_date">End Date</label>
                <input type="date" name="end_date" id="walkin_end_date" class="form-control" required value="<?php echo date('Y-m-d', strtotime('+2 days')); ?>">
            </div>

            <button type="submit" class="btn btn-primary btn-full margin-top-15">Dispatch Keys</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . "/../layouts/footer.php"; ?>
