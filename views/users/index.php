<?php
$pageTitle = "User Registry - RentEasy";
require_once __DIR__ . "/../layouts/header.php";
require_once __DIR__ . "/../layouts/sidebar.php";

$myRole = $_SESSION["user_role"] ?? "";
$myId = $_SESSION["user_id"] ?? 0;
?>

<div class="main-content">
    <div class="page-title-row">
        <h2>User Registry Directory</h2>
        <div>
            <?php if ($myRole === "super_admin" || $myRole === "admin") { ?>
                <button class="btn btn-primary" onclick="openModal('user-modal')">Add User Account</button>
            <?php } ?>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u) { 
                    $canManage = false;
                    if ($u["id"] != $myId) {
                        if ($myRole === "super_admin" && $u["role"] !== "super_admin") {
                            $canManage = true;
                        } elseif ($myRole === "admin" && in_array($u["role"], ["staff", "customer"])) {
                            $canManage = true;
                        } elseif ($myRole === "staff" && $u["role"] === "customer") {
                            $canManage = true;
                        }
                    }
                ?>
                    <tr>
                        <td>#<?php echo (int)$u["id"]; ?></td>
                        <td><strong><?php echo htmlspecialchars($u["name"]); ?></strong></td>
                        <td><?php echo htmlspecialchars($u["email"]); ?></td>
                        <td><?php echo htmlspecialchars($u["phone"] ?? "-"); ?></td>
                        <td><?php echo htmlspecialchars($u["address"] ?? "-"); ?></td>
                        <td>
                            <span class="badge <?php echo ($u['role'] === 'super_admin' || $u['role'] === 'admin') ? 'badge-rented' : (($u['role'] === 'staff') ? 'badge-available' : 'badge-pending'); ?>">
                                <?php echo htmlspecialchars(ucfirst(str_replace("_", " ", $u["role"]))); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?php echo ($u['status'] === 'active') ? 'badge-available' : 'badge-maintenance'; ?>">
                                <?php echo htmlspecialchars(ucfirst($u["status"])); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($canManage) { ?>
                                <a href="index.php?controller=users&action=toggleStatus&id=<?php echo (int)$u['id']; ?>" class="btn btn-secondary btn-sm">
                                    <?php echo ($u["status"] === "active") ? "Deactivate" : "Activate"; ?>
                                </a>
                                <?php if ($myRole === "super_admin") { ?>
                                    <a href="index.php?controller=users&action=delete&id=<?php echo (int)$u['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete user account?')">Delete</a>
                                <?php } ?>
                            <?php } else { ?>
                                <span class="text-muted"><?php echo ($u["id"] == $myId) ? "Current Account" : "Restricted"; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($myRole === "super_admin" || $myRole === "admin") { ?>
    <div class="modal-bg" id="user-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Administrative Account</h3>
                <button class="modal-close-btn" onclick="closeModal('user-modal')">&times;</button>
            </div>
            <form action="index.php?controller=users&action=create" method="POST" id="userForm">
                <div class="form-group">
                    <label for="u_name">Full Name</label>
                    <input type="text" name="name" id="u_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="u_email">Email Address</label>
                    <input type="email" name="email" id="u_email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="u_phone">Phone</label>
                    <input type="text" name="phone" id="u_phone" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="u_address">Address</label>
                    <input type="text" name="address" id="u_address" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="u_role">Role</label>
                    <select name="role" id="u_role" class="form-control">
                        <option value="staff">Staff/Manager</option>
                        <?php if ($myRole === "super_admin") { ?>
                            <option value="admin">Admin</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="u_password">Password</label>
                    <input type="password" name="password" id="u_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-full margin-top-15">Save User</button>
            </form>
        </div>
    </div>
<?php } ?>

<?php require_once __DIR__ . "/../layouts/footer.php"; ?>
