


<div class="dashboard-container">
    <?php require "_partials/sidebar.php"; ?>
    <main class="main-content">
        <div class="dashboard-header">
            <h1>Albums</h1>
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