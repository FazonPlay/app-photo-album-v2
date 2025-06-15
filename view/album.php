<?php
/**
 * @var array $albumData
 * @var array $allPhotos
 * @var array $allAvailablePhotos
 * @var string $action
 */
require("_partials/errors.php");
?>
<div class="album-form-container">
    <form action="" id="add-album-form" method="post" autocomplete="off">
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
        <div class="form-group">
            <label>Photos in Album</label>
            <div id="photo-checkbox-list">
                <?php
                $selectedIds = array_column($allPhotos, 'photo_id');
                foreach ($allAvailablePhotos as $photo): ?>
                    <label>
                        <input type="checkbox" name="photos[]" value="<?php echo $photo['photo_id']; ?>"
                            <?php if (in_array($photo['photo_id'], $selectedIds)) echo 'checked'; ?>>
                        <img src="<?php echo htmlspecialchars($photo['thumbnail_path'] ?? $photo['file_path']); ?>" alt="" style="width:40px;height:40px;object-fit:cover;">
                        <?php echo htmlspecialchars($photo['title']); ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="form-group" style="display: flex; gap: 10px;">
            <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php?component=albums'">Return</button>
            <button type="submit" class="btn-primary">Create/Update Album</button>
        </div>
    </form>
    <!-- Album sharing link -->
    <button onclick="navigator.clipboard.writeText(window.location.href);alert('Link copied!')">Share Album</button>


        <!-- Invitation form (only for owner/admin) -->
        <?php if (isAdmin() || isAlbumOwner($albumData, $_SESSION['user_id'])): ?>
            <form id="invite-user-form">
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
</div>
<script type="module">
    import { setupAlbumInviteForm } from './assets/js/components/albums.js';
    setupAlbumInviteForm(<?= json_encode($albumData['album_id'] ?? 0) ?>);
</script>

                                                            <!--// make this shit into javascript, seperate it bro plzzzzz-->