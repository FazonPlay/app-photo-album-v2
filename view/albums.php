<div class="dashboard-container">
    <?php require "_partials/sidebar.php"; ?>
    <main class="main-content">
        <div class="dashboard-header" style="display: flex; align-items: center; justify-content: space-between;">
            <h1>Albums</h1>
            <a href="index.php?component=album" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Album
            </a>
        </div>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <select id="user-select" class="user-select">
                <option value="">All Users</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['user_id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <form id="album-search-form" class="mb-3">
            <input type="text" name="tag" placeholder="Search by tag">
            <input type="text" name="title" placeholder="Search by title">
            <button type="submit" class="btn btn-secondary">Search</button>
        </form>
        <!-- ...existing album grid... -->
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
        refreshAlbumList();

        const userSelect = document.getElementById('user-select');
        if (userSelect) {
            userSelect.addEventListener('change', () => {
                refreshAlbumList(1);
            });
        }
    });
</script>