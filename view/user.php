<?php
require("_partials/errors.php");
//require '_partials/sidebar.php';
?>
<div class="main-content">
    <div class="form-container">
        <h1>Add User</h1>
        <form action="" id="user-form" method="post" autocomplete="off">
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirmation">Confirm Password *</label>
                <input type="password" id="confirmation" name="confirmation" required>
            </div>
            <div class="form-group">
                <label for="roles">Role *</label>
                <select id="roles" name="roles" required>
                    <option value="user" <?php if(($_POST['roles'] ?? '') === 'user') echo 'selected'; ?>>User</option>
                    <option value="admin" <?php if(($_POST['roles'] ?? '') === 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="premium" <?php if(($_POST['roles'] ?? '') === 'premium') echo 'selected'; ?>>Premium</option>
                    <option value="lifetime" <?php if(($_POST['roles'] ?? '') === 'lifetime') echo 'selected'; ?>>Lifetime</option>
                </select>
            </div>
            <div class="form-group checkbox-group">
                <input type="checkbox" id="is_active" name="is_active" <?php if(!isset($_POST['is_active']) || $_POST['is_active']) echo 'checked'; ?>>
                <label for="is_active">Active</label>
            </div>
            <div class="form-group">
                <button type="submit" name="create_button" class="btn-primary">Add User</button>
            </div>
        </form>
    </div>
</div>