<?php
$pageTitle = "My Profile - RentEasy";
require_once __DIR__ . "/../layouts/header.php";
require_once __DIR__ . "/../layouts/sidebar.php";
?>

<div class="main-content">
    <div class="page-title-row">
        <h2>My Account Profile</h2>
    </div>

    <div class="card-grid">
        <div class="card">
            <h3>Update Profile</h3>
            <?php if (!empty($profile_msg)) { ?>
                <div class="success-banner"><?php echo htmlspecialchars($profile_msg); ?></div>
            <?php } ?>
            <form action="index.php?controller=profile&action=update" method="POST">
                <div class="form-group">
                    <label for="p_name">Full Name</label>
                    <input type="text" name="name" id="p_name" class="form-control" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="p_email">Email Address</label>
                    <input type="email" id="p_email" class="form-control disabled-input" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="p_phone">Phone Number</label>
                    <input type="text" name="phone" id="p_phone" class="form-control" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="p_address">Postal Address</label>
                    <input type="text" name="address" id="p_address" class="form-control" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>

        <div class="card">
            <h3>Change Password</h3>
            <?php if (!empty($password_msg)) { ?>
                <div class="success-banner"><?php echo htmlspecialchars($password_msg); ?></div>
            <?php } ?>
            <?php if (!empty($password_error)) { ?>
                <div style="background-color: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px;">
                    <?php echo htmlspecialchars($password_error); ?>
                </div>
            <?php } ?>
            <form action="index.php?controller=profile&action=updatePassword" method="POST">
                <div class="form-group">
                    <label for="p_old_pass">Current Password</label>
                    <input type="password" name="old_password" id="p_old_pass" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="form-group">
                    <label for="p_new_pass">New Password</label>
                    <input type="password" name="new_password" id="p_new_pass" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="form-group">
                    <label for="p_conf_pass">Confirm New Password</label>
                    <input type="password" name="confirm_password" id="p_conf_pass" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-secondary">Update Password</button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/../layouts/footer.php"; ?>
