<div class="dashboard-container">
    <?php require "_partials/sidebar.php"; ?>
    <main class="main-content">
        <div class="dashboard-header" style="display: flex; align-items: center; justify-content: space-between;">
            <h1>Albums</h1>
            <a href="index.php?component=album" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Album
            </a>
        </div>
        <div class="album-grid" id="album-list"></div>
        <nav>
            <ul class="pagination" id="album-pagination"></ul>
        </nav>
    </main>
</div>
<script src="./assets/js/components/albums.js" type="module"></script>
<script type="module">
    import { refreshAlbumList } from './assets/js/components/albums.js';
    document.addEventListener('DOMContentLoaded', () => {
        refreshAlbumList(1);
    });
</script>