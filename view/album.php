<?php
/**
 * @var array $albumData
 * @var array $allPhotos
 * @var array $allAvailablePhotos
 * @var string $action
 */
require("_partials/errors.php");
$selectedIds = array_column($allPhotos, 'photo_id');
?>
<div class="album-form-container">
    <!-- Form Column -->
    <form action="" id="add-album-form" method="post" autocomplete="off" class="form-column">
        <input type="hidden" name="album_id" value="<?php echo htmlspecialchars($albumData['album_id'] ?? '', ENT_QUOTES); ?>">

        <div class="form-group">
            <label for="title">Album Title *</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($albumData['title'] ?? '', ENT_QUOTES); ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($albumData['description'] ?? '', ENT_QUOTES); ?></textarea>
        </div>

        <div class="form-group">
            <label for="visibility">Visibility *</label>
            <select id="visibility" name="visibility" required>
                <option value="private" <?php if(($albumData['visibility'] ?? '') === 'private') echo 'selected'; ?>>Private</option>
                <option value="public" <?php if(($albumData['visibility'] ?? '') === 'public') echo 'selected'; ?>>Public</option>
                <option value="restricted" <?php if(($albumData['visibility'] ?? '') === 'restricted') echo 'selected'; ?>>Restricted</option>
            </select>
        </div>

        <!-- Hidden checkboxes for photo selection -->
        <div id="photo-checkboxes" style="display:none;">
            <?php foreach ($allAvailablePhotos as $photo): ?>
                <input
                        type="checkbox"
                        name="photos[]"
                        value="<?= $photo['photo_id'] ?>"
                        id="photo-checkbox-<?= $photo['photo_id'] ?>"
                    <?= in_array($photo['photo_id'], $selectedIds) ? 'checked' : '' ?>>
            <?php endforeach; ?>
        </div>

        <div class="form-group" style="display: flex; gap: 10px;">
            <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php?component=albums'">Return</button>
            <button type="submit" class="btn-primary">Create/Update Album</button>
        </div>
    </form>

    <!-- Photo Grid Column -->
    <div class="photos-column">
        <h3>Select Photos</h3>
        <div id="photo-grid">
            <?php foreach ($allAvailablePhotos as $photo):
                $id = $photo['photo_id'];
                $selected = in_array($id, $selectedIds);
                ?>
                <div class="photo-item <?= $selected ? 'selected' : '' ?>" data-photo-id="<?= $id ?>">
                    <img src="<?= htmlspecialchars($photo['thumbnail_path'] ?? $photo['file_path']) ?>"
                         alt="<?= htmlspecialchars($photo['title']) ?>">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ✅ Invite form moved OUTSIDE the album form -->
<?php if (($albumData['album_id'] ?? 0) > 0 && (isAdmin() || isAlbumOwner($albumData, $_SESSION['user_id']))): ?>
    <form id="invite-user-form" style="margin: 24px auto; max-width: 600px;">
        <input type="email" name="email" placeholder="Invite by email" required>
        <select name="permission">
            <option value="view">View</option>
            <option value="comment">Comment</option>
            <option value="contribute">Contribute</option>
        </select>
        <input type="text" name="message" placeholder="Message (optional)">
        <button type="submit">Send Invite</button>
    </form>
<?php endif; ?>

<script type="module">
    import { setupAlbumInviteForm } from './assets/js/components/albums.js';
    setupAlbumInviteForm(<?= json_encode($albumData['album_id'] ?? 0) ?>);

    // Toggle photo selection
    const photoGrid = document.getElementById('photo-grid');
    photoGrid?.addEventListener('click', (e) => {
        const item = e.target.closest('.photo-item');
        if (!item) return;

        const photoId = item.dataset.photoId;
        const checkbox = document.getElementById('photo-checkbox-' + photoId);
        item.classList.toggle('selected');
        if (checkbox) checkbox.checked = !checkbox.checked;
    });
</script>
