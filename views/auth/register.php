<?php
$pageTitle = "Register - RentEasy";
require_once __DIR__ . "/../layouts/header.php";
?>
<div class="auth-body" style="min-height: 100vh; padding: 40px 20px; display: flex; align-items: center; justify-content: center;">
    <div class="auth-container w-max-480">
        <div class="auth-header">
            <h2>Create Customer Account</h2>
            <p class="auth-subheader">Register to access our vehicle rentals catalog</p>
        </div>

        <?php if (!empty($general_error)) { ?>
            <div style="background-color: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; text-align: center;">
                <?php echo htmlspecialchars($general_error); ?>
            </div>
        <?php } ?>

        <form action="index.php?controller=auth&action=register" method="POST" id="registerForm">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="John Doe" value="<?php echo htmlspecialchars($name); ?>">
                <?php if (!empty($name_error)) { ?>
                    <span class="error-message"><?php echo htmlspecialchars($name_error); ?></span>
                <?php } ?>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="john@example.com" value="<?php echo htmlspecialchars($email); ?>">
                <span id="email-ajax-feedback" style="font-size: 12px; display: block; margin-top: 4px;"></span>
                <?php if (!empty($email_error)) { ?>
                    <span class="error-message"><?php echo htmlspecialchars($email_error); ?></span>
                <?php } ?>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" id="phone" class="form-control" placeholder="+8801700000000" value="<?php echo htmlspecialchars($phone); ?>">
                <?php if (!empty($phone_error)) { ?>
                    <span class="error-message"><?php echo htmlspecialchars($phone_error); ?></span>
                <?php } ?>
            </div>

            <div class="form-group">
                <label for="address">Full Address</label>
                <input type="text" name="address" id="address" class="form-control" placeholder="House #, Road #, Area" value="<?php echo htmlspecialchars($address); ?>">
                <?php if (!empty($address_error)) { ?>
                    <span class="error-message"><?php echo htmlspecialchars($address_error); ?></span>
                <?php } ?>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••">
                <?php if (!empty($password_error)) { ?>
                    <span class="error-message"><?php echo htmlspecialchars($password_error); ?></span>
                <?php } ?>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-primary btn-full margin-top-15">
                Register Now
            </button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="index.php?controller=auth&action=login">Sign In</a> | <a href="index.php?controller=home">Home</a>
        </div>
    </div>
</div>
<?php require_once __DIR__ . "/../layouts/footer.php"; ?>
