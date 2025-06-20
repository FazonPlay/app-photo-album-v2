<div class="dashboard-container">
    <?php require "_partials/sidebar.php"; ?>
    <div class="photo-page">
        <div class="photo-header">
            <h1>My Favorite Photos</h1>
        </div>
        <div class="photo-gallery" id="favorite-list"></div>
        <nav>
            <ul class="pagination" id="favorite-pagination"></ul>
        </nav>
    </div>
</div>
<link rel="stylesheet" href="assets/css/photos_custom.css">
<script src="./assets/js/services/photo.js" type="module"></script>
<script src="./assets/js/components/favorites.js" type="module"></script>
<script type="module">
    import { refreshFavoriteList } from './assets/js/components/favorites.js';
    document.addEventListener('DOMContentLoaded', () => {
        refreshFavoriteList(1);
    });
</script>