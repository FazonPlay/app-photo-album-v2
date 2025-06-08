<?php
/**
 * @var array $photoData
 * @var string $action
 */
require("_partials/errors.php");
?>
<div class="photo-form-container">
    <form action="" id="photo-form" method="post" autocomplete="off">
        <input type="hidden" name="photo_id" value="<?php echo htmlspecialchars($photoData['photo_id'] ?? '', ENT_QUOTES); ?>">
        <div class="form-group">
            <label for="title">Photo Title *</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($photoData['title'] ?? '', ENT_QUOTES); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description"><?php echo htmlspecialchars($photoData['description'] ?? '', ENT_QUOTES); ?></textarea>
        </div>
        <div class="form-group checkbox-group">
            <input type="checkbox" id="is_favorite" name="is_favorite" <?php if(($photoData['is_favorite'] ?? 0)) echo 'checked'; ?>>
            <label for="is_favorite">Favorite</label>
        </div>
        <div class="form-group">
            <button type="submit" class="btn-primary">Update Photo</button>
        </div>
    </form>
</div>