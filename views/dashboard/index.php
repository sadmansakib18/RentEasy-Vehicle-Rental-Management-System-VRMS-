<?php
$pageTitle = "Dashboard - RentEasy";
require_once __DIR__ . "/../layouts/header.php";
require_once __DIR__ . "/../layouts/sidebar.php";
?>

<div class="main-content">
    <div class="page-title-row">
        <div>
            <h2 class="page-title-text">RentEasy Dashboard</h2>
            <div class="page-subtitle-text">
                Welcome, <strong><?php echo htmlspecialchars($_SESSION["user_name"] ?? "User"); ?></strong> (Role: <?php echo htmlspecialchars(ucfirst(str_replace("_", " ", $_SESSION["user_role"] ?? "guest"))); ?>)
            </div>
        </div>
        <div>
            <?php if (($_SESSION["user_role"] ?? "") === "customer") { ?>
                <a href="index.php?controller=browse&action=index" class="btn btn-primary">Book Car</a>
            <?php } else { ?>
                <a href="index.php?controller=vehicles&action=index" class="btn btn-primary">Add Vehicle</a>
            <?php } ?>
        </div>
    </div>

    <div class="card-grid">
        <div class="card">
            <h3>Total Vehicles</h3>
            <h1 class="card-stat-val-primary"><?php echo $totalVehicles; ?></h1>
            <p class="card-stat-desc"><?php echo $availableVehicles; ?> ready to dispatch</p>
        </div>
        <div class="card">
            <h3>Active Rentals</h3>
            <h1 class="card-stat-val-danger"><?php echo $rentedVehicles; ?></h1>
            <p class="card-stat-desc">Vehicles currently rented</p>
        </div>
        <div class="card">
            <h3>Pending Approvals</h3>
            <h1 class="card-stat-val-warning"><?php echo $pendingApprovals; ?></h1>
            <p class="card-stat-desc">Awaiting staff response</p>
        </div>
        <div class="card">
            <h3>Total Revenue</h3>
            <h1 class="card-stat-val-success"><?php echo number_format($totalRevenue, 2); ?> ৳</h1>
            <p class="card-stat-desc">Recorded transactions</p>
        </div>
    </div>

    <div class="table-container margin-top-30">
        <h3><?php echo (($_SESSION["user_role"] ?? "") === "customer") ? "My Recent Bookings" : "Recent Bookings Queue"; ?></h3>
        <table>
            <thead>
                <tr>
                    <?php if (($_SESSION["user_role"] ?? "") !== "customer") { ?>
                        <th>Customer</th>
                    <?php } ?>
                    <th>Vehicle</th>
                    <th>Dates</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentRentals)) { ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: #94a3b8;">No bookings found.</td>
                    </tr>
                <?php } else { ?>
                    <?php foreach (array_slice($recentRentals, 0, 5) as $rent) { ?>
                        <tr>
                            <?php if (($_SESSION["user_role"] ?? "") !== "customer") { ?>
                                <td><?php echo htmlspecialchars($rent["customer_name"] ?? ""); ?></td>
                            <?php } ?>
                            <td><?php echo htmlspecialchars($rent["brand"] . " " . $rent["model"] . " (" . $rent["plate_number"] . ")"); ?></td>
                            <td><?php echo htmlspecialchars($rent["start_date"] . " to " . $rent["end_date"]); ?></td>
                            <td><?php echo htmlspecialchars(number_format($rent["total_cost"], 2)); ?> BDT</td>
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
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

<?php require_once __DIR__ . "/../layouts/footer.php"; ?>
