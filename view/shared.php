<div class="dashboard-container">
    <?php require "_partials/sidebar.php"; ?>
    <main class="main-content">
        <div class="dashboard-header">
            <h1>Shared With Me</h1>
        </div>
        <?php if (empty($sharedAlbums)): ?>
            <div class="alert alert-info">No albums have been shared with you yet.</div>
        <?php else: ?>
            <div class="album-grid">
                <?php foreach ($sharedAlbums as $album): ?>
                    <div class="album-card">
                        <div class="album-thumbnail">
                            <img src="<?php echo htmlspecialchars($album['cover_path'] ?? 'assets/img/default_album.jpg'); ?>" alt="<?php echo htmlspecialchars($album['title']); ?>">
                        </div>
                        <div class="album-info">
                            <h3><?php echo htmlspecialchars($album['title']); ?></h3>
                            <p><?php echo htmlspecialchars($album['description']); ?></p>
                            <span class="badge bg-secondary"><?php echo ucfirst($album['permission_level']); ?></span>
                            <div class="album-actions">
                                <a href="index.php?component=album&id=<?php echo $album['album_id']; ?>" class="album-btn">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div><?php
