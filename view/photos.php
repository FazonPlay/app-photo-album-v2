<div class="dashboard-container">
    <?php require "_partials/sidebar.php"; ?>
    <div class="photo-page">
        <div class="photo-header">
            <!-- Add this inside view/photos.php right after the photo-header div -->
            <div class="filter-container">
                <form id="photo-filter-form">
                    <div class="form-row">
                        <input type="text" id="tag-filter" name="tag" placeholder="Filter by tag" list="available-tags">
                        <datalist id="available-tags">
                            <!-- Will be populated via JavaScript -->
                        </datalist>
                        <button type="submit" class="btn-secondary">Filter</button>
                        <button type="reset" class="btn-outline" id="clear-filters">Clear</button>
                    </div>
                </form>
            </div>
            <h1>Photo Gallery</h1>
            <form id="add-photo-form" enctype="multipart/form-data" method="post" action="">
                <div class="form-row">
                    <input type="text" id="photo-title" name="title" placeholder="Photo Title" required>
                    <input type="file" id="photo-file" name="photo" accept="image/*" required>
                    <button type="submit" class="btn-primary">Add Photo</button>
                </div>
            </form>
            <div id="photo-errors" class="alert d-none"></div>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <select id="user-select" class="user-select">
                    <option value="">All Users</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user['user_id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </div>
        <div class="photo-gallery" id="photo-list"></div>
        <nav>
            <ul class="pagination" id="photo-pagination"></ul>
        </nav>
    </div>
</div>
<link rel="stylesheet" href="assets/css/photos_custom.css">
<script src="./assets/js/services/photo.js" type="module"></script>
<script src="./assets/js/components/photos.js" type="module"></script>
<script type="module">
    import { refreshPhotoList, handleAddPhoto } from './assets/js/components/photos.js';
    document.addEventListener('DOMContentLoaded', () => {
        refreshPhotoList(1);
        const userSelect = document.getElementById('user-select');
        if (userSelect) {
            userSelect.addEventListener('change', () => {
                refreshPhotoList(1);
            });
        }
        handleAddPhoto();
    });
</script>