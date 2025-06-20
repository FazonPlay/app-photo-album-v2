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
    <!-- Left column: Album form -->
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

        <!-- Hidden input for selected photos (populated by JS) -->
        <input type="hidden" name="photos_selected" id="photos-selected-input" value="<?php echo htmlspecialchars(implode(',', $selectedIds)); ?>">

        <div class="form-group" style="display: flex; gap: 10px;">
            <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php?component=albums'">Return</button>
            <button type="submit" class="btn-primary">Create/Update Album</button>
        </div>
    </form>

    <!-- Right column: Photos grid -->
    <div class="photos-column">
        <h3>Photos in Album</h3>
        <div id="photo-grid">
            <?php foreach ($allAvailablePhotos as $photo): ?>
                <div class="photo-item <?php echo in_array($photo['photo_id'], $selectedIds) ? 'selected' : ''; ?>"
                     data-photo-id="<?php echo $photo['photo_id']; ?>">
                    <img src="<?php echo htmlspecialchars($photo['thumbnail_path'] ?? $photo['file_path']); ?>"
                         alt="<?php echo htmlspecialchars($photo['title']); ?>">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Invite user form (outside main form!) -->
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

    // JS for toggling photo selection
    const photoGrid = document.getElementById('photo-grid');
    const inputField = document.getElementById('photos-selected-input');
    let selectedIds = new Set(inputField.value ? inputField.value.split(',') : []);

    photoGrid?.addEventListener('click', (e) => {
        const item = e.target.closest('.photo-item');
        if (!item) return;

        const photoId = item.dataset.photoId;
        if (item.classList.toggle('selected')) {
            selectedIds.add(photoId);
        } else {
            selectedIds.delete(photoId);
        }
        inputField.value = Array.from(selectedIds).join(',');
    });
</script>
