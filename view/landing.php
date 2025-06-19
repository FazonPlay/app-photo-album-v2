<?php if (!isset($_SESSION['auth'])): ?>
    <div class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Capture, Store, Share</h1>
            <p class="hero-text">Your memories deserve a beautiful home. Our photo album platform helps you organize, protect and share your precious moments with loved ones.</p>
            <div class="cta-buttons">
                <a href="?component=login" class="btn btn-primary">Sign In</a>
                <a href="?component=create_user" class="btn btn-secondary">Create Account</a>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <h2 class="section-title">Why Choose Our Platform?</h2>
            <div class="features">
                <div class="feature">
                    <i class="fas fa-lock feature-icon"></i>
                    <h3>Secure Storage</h3>
                    <p>Your photos are safely stored with bank-level encryption and privacy controls.</p>
                </div>
                <div class="feature">
                    <i class="fas fa-share-alt feature-icon"></i>
                    <h3>Easy Sharing</h3>
                    <p>Share albums with friends and family with customizable permission levels.</p>
                </div>
                <div class="feature">
                    <i class="fas fa-search feature-icon"></i>
                    <h3>Smart Organization</h3>
                    <p>Tag, categorize, and search your photos to find any memory instantly.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section testimonials">
        <div class="container">
            <h2 class="section-title">What Our Users Say</h2>
            <div class="testimonial">
                <p class="testimonial-text">"I've been using this platform for a year now, and it's transformed how I preserve family memories. The sharing features are exceptional!"</p>
                <p class="testimonial-author">- Sarah K.</p>
            </div>
        </div>
    </section>

    <section class="section text-center">
        <div class="container">
            <h2 class="section-title">Ready to start your photo journey?</h2>
            <p class="cta-section-text">Join thousands of satisfied users who trust us with their precious memories.</p>
            <a href="?component=create_user" class="btn btn-primary cta-button-large">Get Started Free</a>
        </div>
    </section>

    <?php else: ?>
        <div class="dashboard-container">
            <?php require "_partials/sidebar.php"; ?>
            <main class="main-content">
                <div class="dashboard-header">
                    <h1>Welcome back, <?php echo htmlspecialchars($user->username ?? 'User', ENT_QUOTES, 'UTF-8'); ?>!</h1>
                    <div class="actions">
                        <a href="?component=album&action=create" class="btn"><i class="fas fa-plus"></i> New Album</a>
                        <a href="?component=photo&action=upload" class="btn btn-primary"><i class="fas fa-upload"></i> Upload Photos</a>
                    </div>
                </div>

                <!-- Recent Albums Section -->
                <section class="dashboard-section">
                    <div class="section-header">
                        <h2>Recent Albums</h2>
                        <a href="?component=albums" class="view-all">View all</a>
                    </div>
                    <?php if (empty($recentAlbums)): ?>
                        <div class="empty-state">
                            <p>No recent albums</p>
                        </div>
                    <?php else: ?>
                        <div class="album-grid">
                            <?php foreach ($recentAlbums as $album): ?>
                                <div class="album-card">
                                    <div class="album-thumbnail">
                                        <?php if (!empty($album->cover_photo_id)): ?>
                                            <img src="<?php echo htmlspecialchars($album->coverPhotoUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($album->title, ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php else: ?>
                                            <div class="no-cover">
                                                <i class="fas fa-images"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="album-info">
                                        <h3><?php echo htmlspecialchars($album->title, ENT_QUOTES, 'UTF-8'); ?></h3>
                                        <p><?php echo htmlspecialchars($album->photoCount, ENT_QUOTES, 'UTF-8'); ?> photos</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- Favorite Photos Section -->
                <section class="dashboard-section">
                    <div class="section-header">
                        <h2>Favorite Photos</h2>
                        <a href="?component=favorites" class="view-all">View all</a>
                    </div>
                    <?php if (empty($favoritePhotos)): ?>
                        <div class="empty-state">
                            <p>No favorite photos</p>
                        </div>
                    <?php else: ?>
                        <div class="photo-grid">
                            <?php foreach ($favoritePhotos as $photo): ?>
                                <div class="photo-card">
                                    <div class="photo-thumbnail">
                                        <img src="<?php echo htmlspecialchars($photo->file_path, ENT_QUOTES, 'UTF-8'); ?>"
                                             alt="<?php echo htmlspecialchars($photo->title ?? 'Photo', ENT_QUOTES, 'UTF-8'); ?>">
                                        <div class="photo-actions">
                                            <button class="favorite-btn active"><i class="fas fa-heart"></i></button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- Album Invitations Section -->
                <section class="dashboard-section">
                    <div class="section-header">
                        <h2>Album Invitations</h2>
                        <a href="?component=invitations" class="view-all">View all</a>
                    </div>
                    <?php if (!empty($pendingInvitations)): ?>
                        <div class="section">
                            <h2>Pending Invitations</h2>
                            <div class="invitation-list">
                                <?php foreach ($pendingInvitations as $invitation): ?>
                                    <div class="invitation-card" data-token="<?php echo htmlspecialchars($invitation['token']); ?>">
                                        <div class="invitation-info">
                                            <h3><?php echo htmlspecialchars($invitation['album_title']); ?></h3>
                                            <p>From: <?php echo htmlspecialchars($invitation['sender_name']); ?></p>
                                            <p>Permission: <?php echo ucfirst(htmlspecialchars($invitation['permission_level'])); ?></p>
                                            <?php if (!empty($invitation['message'])): ?>
                                                <p>Message: <?php echo htmlspecialchars($invitation['message']); ?></p>
                                            <?php endif; ?>
                                            <p>Expires: <?php echo date('F j, Y', strtotime($invitation['expires_at'])); ?></p>
                                        </div>
                                        <div class="invitation-actions">
                                            <button class="btn btn-success accept-invitation">Accept</button>
                                            <button class="btn btn-danger decline-invitation">Decline</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </section>
            </main>
        </div>

        <script type="module">
            import { setupInvitationHandlers } from './assets/js/components/invitations.js';
            document.addEventListener('DOMContentLoaded', setupInvitationHandlers);
        </script>
    <?php endif; ?>