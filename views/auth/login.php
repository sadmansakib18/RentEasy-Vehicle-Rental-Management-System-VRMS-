<?php
$pageTitle = "Login";
require_once __DIR__ . "/../layouts/header.php";

$systemName = get_system_setting("system_name", "RentEasy");
$allowRegistration = get_system_setting("allow_registration", "1") === "1";
?>
<div class="auth-body" style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="auth-container">
        <div class="auth-header">
            <h2><?php echo htmlspecialchars($systemName); ?> Sign In</h2>
            <p class="auth-subheader">Enter credentials to access your account</p>
        </div>

        <?php if (isset($_GET["registered"]) && $_GET["registered"] == "true") { ?>
            <div class="success-banner">
                Registration successful! Please sign in with your credentials.
            </div>
        <?php } ?>

        <?php if (!empty($general_error)) { ?>
            <div style="background-color: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; text-align: center;">
                <?php echo htmlspecialchars($general_error); ?>
            </div>
        <?php } ?>

        <form action="index.php?controller=auth&action=login" method="POST" id="loginForm">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com" value="<?php echo htmlspecialchars($email); ?>">
                <?php if (!empty($email_error)) { ?>
                    <span class="error-message"><?php echo htmlspecialchars($email_error); ?></span>
                <?php } ?>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••">
                <?php if (!empty($password_error)) { ?>
                    <span class="error-message"><?php echo htmlspecialchars($password_error); ?></span>
                <?php } ?>
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="remember" id="remember" <?php echo !empty($email) ? 'checked' : ''; ?>>
                <label for="remember" style="margin-bottom: 0; font-weight: normal; cursor: pointer;">Remember email</label>
            </div>

            <button type="submit" class="btn btn-primary btn-full margin-top-15">
                Sign In
            </button>
        </form>

        <div class="auth-footer">
            <?php if ($allowRegistration) { ?>
                New Customer? <a href="index.php?controller=auth&action=register">Create Account</a> | 
            <?php } ?>
            <a href="index.php?controller=home">Home</a>
        </div>
    </div>
</div>
<?php require_once __DIR__ . "/../layouts/footer.php"; ?>
