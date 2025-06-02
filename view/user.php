<?php
/**
 * @var string $action
 * @var array $user
 */
require("_partials/errors.php");
require '_partials/sidebar.php';


?>
<div class="row">
    <div class="col">
        <div class="h1 pt-2 pb-2 text-center">Create / Edit User</div>
        <form action=""  id="user-form" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username'] ?? ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" <?php echo ('create' === $action) ? 'required' : ''; ?>>
            </div>
            <div class="mb-3">
                <label for="confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmation" name="confirmation" <?php echo ('create' === $action) ? 'required' : ''; ?>>
            </div>
            <div class="mb-3 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary" id="valid-form-user" name="<?php echo $action; ?>_button">Save</button>
            </div>
        </form>
    </div>
</div>

