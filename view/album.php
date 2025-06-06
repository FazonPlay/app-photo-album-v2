<div class="album-form-container">
    <form id="add-album-form" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="album-title">Album Title *</label>
            <input type="text" id="album-title" name="title" required>
        </div>
        <div class="form-group">
            <label for="album-description">Description</label>
            <textarea id="album-description" name="description"></textarea>
        </div>
        <div class="form-group">
            <label for="album-cover">Cover Photo</label>
            <input type="file" id="album-cover" name="cover_photo" accept="image/*">
        </div>
        <div class="form-group">
            <label>Select Photos</label>
            <div id="photo-checkbox-list">
                <?php foreach ($allPhotos as $photo): ?>
                    <label>
                        <input type="checkbox" name="photos[]" value="<?php echo $photo['photo_id']; ?>">
                        <?php echo htmlspecialchars($photo['title']); ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        <button type="submit" class="btn-primary">Create Album</button>
    </form>
</div>
