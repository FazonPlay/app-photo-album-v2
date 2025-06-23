
<div class="dashboard-container">
<?php require '_partials/sidebar.php'; ?>

    <main class="main-content">
        <div class="dashboard-header">
            <h1>Admin Dashboard</h1>
            <div class="actions">
                <a href="?component=user&action=create" class="btn"><i class="fas fa-plus"></i> Add User</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-details">
                    <h3>Total Users</h3>
                    <p class="stat-number"><?php echo htmlspecialchars($totalUsers ?? '0'); ?></p>
                    <p class="stat-label">Registered users</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-images"></i></div>
                <div class="stat-details">
                    <h3>Total Albums</h3>
                    <p class="stat-number"><?php echo htmlspecialchars($totalAlbums ?? '0'); ?></p>
                    <p class="stat-label">Created albums</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-camera"></i></div>
                <div class="stat-details">
                    <h3>Total Photos</h3>
                    <p class="stat-number"><?php echo htmlspecialchars($totalPhotos ?? '0'); ?></p>
                    <p class="stat-label">Uploaded photos</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-upload"></i></div>
                <div class="stat-details">
                    <h3>Disk Usage</h3>
                    <p class="stat-number">0mb</p>
                    <p class="stat-label">Total storage used</p>
                </div>
            </div>
        </div>

        <section class="dashboard-section">
            <div class="section-header">
                <h2>Recent Users</h2>
                <a href="?component=users" class="view-all">View all</a>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Registration Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($recentUsers)): ?>
                        <?php foreach ($recentUsers as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user->id); ?></td>
                                <td><?php echo htmlspecialchars($user->username); ?></td>
                                <td><?php echo htmlspecialchars($user->email); ?></td>
                                <td><?php echo htmlspecialchars($user->registrationDate); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $user->isActive ? 'active' : 'inactive'; ?>">
                                        <?php echo $user->isActive ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td class="actions-cell">
                                    <a href="?component=users&action=view&id=<?php echo htmlspecialchars($user->id); ?>" class="btn-icon" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="?component=users&action=edit&id=<?php echo htmlspecialchars($user->id); ?>" class="btn-icon" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="btn-icon delete-btn" data-id="<?php echo htmlspecialchars($user->id); ?>" title="Delete"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">No users found</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="dashboard-section">
            <div class="section-header">
                <h2>System Activity</h2>
                <a href="?component=logs" class="view-all">View all</a>
            </div>
            <div class="activity-log">
                <?php if (!empty($recentActivity)): ?>
                    <?php foreach ($recentActivity as $activity): ?>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <?php if ($activity->type === 'user_login'): ?>
                                    <i class="fas fa-sign-in-alt"></i>
                                <?php elseif ($activity->type === 'user_register'): ?>
                                    <i class="fas fa-user-plus"></i>
                                <?php elseif ($activity->type === 'album_create'): ?>
                                    <i class="fas fa-folder-plus"></i>
                                <?php elseif ($activity->type === 'photo_upload'): ?>
                                    <i class="fas fa-upload"></i>
                                <?php else: ?>
                                    <i class="fas fa-info-circle"></i>
                                <?php endif; ?>
                            </div>
                            <div class="activity-content">
                                <p class="activity-text"><?php echo htmlspecialchars($activity->message); ?></p>
                                <p class="activity-time"><?php echo htmlspecialchars($activity->timestamp); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="activity-item">
                        <p class="text-center">No recent activity</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>
