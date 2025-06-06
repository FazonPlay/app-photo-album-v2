<?php
require("_partials/errors.php");
//require '_partials/sidebar.php';
?>
<div class="user-form-container">
    <form action="" id="user-form" method="post" autocomplete="off">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userData['user_id'] ?? '', ENT_QUOTES); ?>">
        <div class="form-group">
            <label for="username">Username *</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($userData['username'] ?? '', ENT_QUOTES); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['email'] ?? '', ENT_QUOTES); ?>" required>
        </div>
        <!-- Password fields: only require for create, optional for edit -->
        <div class="form-group">
            <label for="password">Password <?php echo $action === 'edit' ? '(leave blank to keep current)' : '*'; ?></label>
            <input type="password" id="password" name="password" <?php echo $action === 'create' ? 'required' : ''; ?>>
        </div>
        <div class="form-group">
            <label for="confirmation">Confirm Password <?php echo $action === 'edit' ? '(leave blank to keep current)' : '*'; ?></label>
            <input type="password" id="confirmation" name="confirmation" <?php echo $action === 'create' ? 'required' : ''; ?>>
        </div>
        <div class="form-group">
            <label for="roles">Role *</label>
            <select id="roles" name="roles" required>
                <option value="user" <?php if(($userData['roles'] ?? '') === 'user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if(($userData['roles'] ?? '') === 'admin') echo 'selected'; ?>>Admin</option>
                <option value="premium" <?php if(($userData['roles'] ?? '') === 'premium') echo 'selected'; ?>>Premium</option>
                <option value="lifetime" <?php if(($userData['roles'] ?? '') === 'lifetime') echo 'selected'; ?>>Lifetime</option>
            </select>
        </div>
        <div class="form-group checkbox-group">
            <input type="checkbox" id="is_active" name="is_active" <?php if(($userData['is_active'] ?? 1)) echo 'checked'; ?>>
            <label for="is_active">Active</label>
        </div>
        <div class="form-group">
            <button type="submit" name="<?php echo $action === 'edit' ? 'edit_button' : 'create_button'; ?>" class="btn-primary">
                <?php echo $action === 'edit' ? 'Update User' : 'Add User'; ?>
            </button>
        </div>
    </form>
</div>
