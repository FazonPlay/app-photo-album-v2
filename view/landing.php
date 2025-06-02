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
        <aside class="sidebar">
            <div class="user-info">
                <div class="user-avatar">
                    <?php if (!empty($user->profile_picture)): ?>
                        <img src="<?php echo htmlspecialchars($user->profile_picture, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile">
                    <?php else: ?>
                        <div class="default-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <h3><?php echo htmlspecialchars($user->username ?? '', ENT_QUOTES, 'UTF-8'); ?></h3>
            </div>

            <nav class="dashboard-nav">
                <ul>
                    <li class="active"><a href="?component=landing"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="?component=albums"><i class="fas fa-images"></i> My Albums</a></li>
                    <li><a href="?component=photo"><i class="fas fa-camera"></i> All Photos</a></li>
                    <li><a href="?component=favorites"><i class="fas fa-heart"></i> Favorites</a></li>
                    <li><a href="?component=shared"><i class="fas fa-share-alt"></i> Shared with Me</a></li>
                    <li><a href="?component=profile"><i class="fas fa-user-edit"></i> Edit Profile</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <div class="dashboard-header">
                <h1>Welcome back, <?php echo htmlspecialchars($user->username ?? 'User', ENT_QUOTES, 'UTF-8'); ?>!</h1>
                <div class="actions">
                    <a href="?component=album&action=create" class="btn"><i class="fas fa-plus"></i> New Album</a>
                    <a href="?component=photo&action=upload" class="btn btn-primary"><i class="fas fa-upload"></i> Upload Photos</a>
                </div>
            </div>

            <?php if (!empty($recentAlbums)): ?>
                <section class="dashboard-section">
                    <div class="section-header">
                        <h2>Recent Albums</h2>
                        <a href="?component=albums" class="view-all">View all</a>
                    </div>
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
                </section>
            <?php endif; ?>

            <?php if (!empty($favoritePhotos)): ?>
                <section class="dashboard-section">
                    <div class="section-header">
                        <h2>Favorite Photos</h2>
                        <a href="?component=favorites" class="view-all">View all</a>
                    </div>
                    <div class="photo-grid">
                        <?php foreach ($favoritePhotos as $photo): ?>
                            <div class="photo-card">
                                <div class="photo-thumbnail">
                                    <img src="<?php echo htmlspecialchars($photo->thumbnail_path, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($photo->title ?? 'Photo', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div class="photo-actions">
                                    <button class="favorite-btn active"><i class="fas fa-heart"></i></button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if (!empty($sharedAlbums)): ?>
                <section class="dashboard-section">
                    <div class="section-header">
                        <h2>Shared with You</h2>
                        <a href="?component=shared" class="view-all">View all</a>
                    </div>
                    <div class="album-grid">
                        <?php foreach ($sharedAlbums as $album): ?>
                            <div class="album-card shared">
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
                                    <p>Shared by <?php echo htmlspecialchars($album->ownerName, ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if (!empty($pendingInvitations)): ?>
                <section class="dashboard-section">
                    <div class="section-header">
                        <h2>Album Invitations</h2>
                    </div>
                    <div class="invitations-list">
                        <?php foreach ($pendingInvitations as $invitation): ?>
                            <div class="invitation-card">
                                <div class="invitation-details">
                                    <h3><?php echo htmlspecialchars($invitation->albumTitle, ENT_QUOTES, 'UTF-8'); ?></h3>
                                    <p><strong>From:</strong> <?php echo htmlspecialchars($invitation->senderName, ENT_QUOTES, 'UTF-8'); ?></p>
                                    <p><?php echo htmlspecialchars($invitation->message ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                                <div class="invitation-actions">
                                    <a href="?component=invitations&action=accept&id=<?php echo htmlspecialchars($invitation->invitation_id, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-small">Accept</a>
                                    <a href="?component=invitations&action=decline&id=<?php echo htmlspecialchars($invitation->invitation_id, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-small btn-outline">Decline</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        </main>
    </div>
<?php endif; ?>