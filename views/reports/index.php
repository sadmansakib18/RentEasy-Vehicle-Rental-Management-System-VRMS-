<?php
$pageTitle = "Reports & Settings - RentEasy";
require_once __DIR__ . "/../layouts/header.php";
require_once __DIR__ . "/../layouts/sidebar.php";
?>

<div class="main-content">
    <div class="page-title-row">
        <h2>Reports & Analytics</h2>
        <div>
            <button class="btn btn-secondary" onclick="window.print()">Print Summary</button>
        </div>
    </div>

    <div class="card-grid">
        <div class="card">
            <h3>Total Revenue Logged</h3>
            <h1 class="card-stat-val-success"><?php echo number_format($summary["revenue"], 2); ?> BDT</h1>
            <p class="card-stat-desc">From completed/active rentals</p>
        </div>
        <div class="card">
            <h3>Total Rental Days</h3>
            <h1 class="card-stat-val-primary"><?php echo (int)$summary["total_days"]; ?> Days</h1>
            <p class="card-stat-desc">Cumulative vehicle usage</p>
        </div>
        <div class="card">
            <h3>Total Vehicles</h3>
            <h1 class="card-stat-val-warning"><?php echo (int)$summary["total_vehicles"]; ?></h1>
            <p class="card-stat-desc"><?php echo (int)$summary["rented_vehicles"]; ?> currently rented</p>
        </div>
    </div>

    <div class="table-container margin-top-30">
        <h3>Occupancy Statistics by Vehicle Category</h3>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Total Count</th>
                    <th>Active Rented</th>
                    <th>Occupancy Rate</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($occupancy as $occ) { ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($occ["type"]); ?></strong></td>
                        <td><?php echo (int)$occ["total_count"]; ?></td>
                        <td><?php echo (int)$occ["rented_count"]; ?></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="flex: 1; background: #e2e8f0; height: 8px; border-radius: 4px; overflow: hidden;">
                                    <div style="width: <?php echo (int)$occ['rate']; ?>%; background: #3498db; height: 100%;"></div>
                                </div>
                                <span><?php echo (int)$occ["rate"]; ?>%</span>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="table-container margin-top-30">
        <h3>Top Customer Spenders</h3>
        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Rentals Count</th>
                    <th>Total Spent</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($topCustomers)) { ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: #94a3b8; padding: 15px;">No customer transactions recorded yet.</td>
                    </tr>
                <?php } else { ?>
                    <?php foreach ($topCustomers as $tc) { ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($tc["name"]); ?></strong></td>
                            <td><?php echo htmlspecialchars($tc["email"]); ?></td>
                            <td><?php echo (int)$tc["rental_count"]; ?></td>
                            <td><strong><?php echo number_format($tc["total_spent"], 2); ?> BDT</strong></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php if (($_SESSION["user_role"] ?? "") === "super_admin") { ?>
        <div id="settings" class="margin-top-40">
            <div class="page-title-row margin-bottom-20">
                <h2>Global System Settings</h2>
            </div>

            <div class="card w-max-600">
                <form action="index.php?controller=reports&action=updateSettings" method="POST">
                    <div class="form-group">
                        <label for="set_name">Business / System Name</label>
                        <input type="text" name="system_name" id="set_name" class="form-control" value="<?php echo htmlspecialchars($settings['system_name'] ?? 'RentEasy VRMS'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="set_currency">Base Currency Code</label>
                        <input type="text" name="currency" id="set_currency" class="form-control" value="<?php echo htmlspecialchars($settings['currency'] ?? 'BDT'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="set_email">Contact Support Email</label>
                        <input type="email" name="contact_email" id="set_email" class="form-control" value="<?php echo htmlspecialchars($settings['contact_email'] ?? 'support@renteasy.com'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="set_phone">Contact Phone</label>
                        <input type="text" name="contact_phone" id="set_phone" class="form-control" value="<?php echo htmlspecialchars($settings['contact_phone'] ?? '+88029999999'); ?>" required>
                    </div>
                    <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="allow_registration" id="set_reg" <?php echo (($settings['allow_registration'] ?? '1') === '1') ? 'checked' : ''; ?>>
                        <label for="set_reg" style="margin-bottom: 0; font-weight: normal;">Allow public customer registrations</label>
                    </div>
                    <button type="submit" class="btn btn-primary margin-top-10">Save Settings</button>
                </form>
            </div>
        </div>
    <?php } ?>

</div>

<?php require_once __DIR__ . "/../layouts/footer.php"; ?>
