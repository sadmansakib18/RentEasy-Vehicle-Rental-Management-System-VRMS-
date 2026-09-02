<?php
$pageTitle = "My Bookings - RentEasy";
require_once __DIR__ . "/../layouts/header.php";
require_once __DIR__ . "/../layouts/sidebar.php";
?>

<div class="main-content">
    <div class="page-title-row">
        <h2>My Rental Bookings</h2>
        <div>
            <a href="index.php?controller=browse&action=index" class="btn btn-primary">Book New Vehicle</a>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Vehicle</th>
                    <th>Pickup Date</th>
                    <th>Return Date</th>
                    <th>Total Cost</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($myRentals)) { ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #94a3b8; padding: 20px;">
                            You have no rental bookings yet. <a href="index.php?controller=browse&action=index" style="color: #3498db; font-weight: bold;">Browse our fleet</a>
                        </td>
                    </tr>
                <?php } else { ?>
                    <?php foreach ($myRentals as $rent) { ?>
                        <tr>
                            <td>#RL-<?php echo str_pad($rent["id"], 4, "0", STR_PAD_LEFT); ?></td>
                            <td><strong><?php echo htmlspecialchars($rent["brand"] . " " . $rent["model"]); ?></strong> (<?php echo htmlspecialchars($rent["plate_number"]); ?>)</td>
                            <td><?php echo htmlspecialchars($rent["start_date"]); ?></td>
                            <td><?php echo htmlspecialchars($rent["end_date"]); ?></td>
                            <td><strong><?php echo htmlspecialchars(number_format($rent["total_cost"], 2)); ?> BDT</strong></td>
                            <td>
                                <?php
                                $badgeClass = "badge-pending";
                                if ($rent["status"] === "rented") $badgeClass = "badge-rented";
                                if ($rent["status"] === "approved") $badgeClass = "badge-available";
                                if ($rent["status"] === "returned") $badgeClass = "badge-available";
                                if ($rent["status"] === "cancelled") $badgeClass = "badge-maintenance";
                                ?>
                                <span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars(ucfirst($rent["status"])); ?></span>
                            </td>
                            <td>
                                <?php if ($rent["status"] === "pending") { ?>
                                    <a href="index.php?controller=rentals&action=cancel&id=<?php echo (int)$rent['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Cancel this booking request?')">Cancel</a>
                                <?php } else { ?>
                                    <span class="text-muted">-</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . "/../layouts/footer.php"; ?>
