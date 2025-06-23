<?php
$currentComponent = $_GET['component'] ?? 'landing';
$user = null;

if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("
            SELECT u.username, u.email, u.roles, up.first_name, up.last_name, up.bio, up.profile_picture
            FROM users u
            LEFT JOIN user_profiles up ON u.user_id = up.user_id
            WHERE u.user_id = :user_id
        ");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);
    } catch (Exception $e) {
        error_log('Error fetching user data: ' . $e->getMessage());
    }
}

$displayName = $user ? (trim($user->first_name . ' ' . $user->last_name) ?: $user->username) : 'Guest';
?>
<aside class="sidebar">
    <div class="user-info">
        <div class="user-avatar">
            <?php if ($user && !empty($user->profile_picture)): ?>
                <img src="<?php echo htmlspecialchars($user->profile_picture, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile">
            <?php else: ?>
                <div class="default-avatar">
                    <i class="fas fa-user"></i>
                </div>
            <?php endif; ?>
        </div>
        <h3><?php echo htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?></h3>
        <?php if ($user && !empty($user->email)): ?>
            <p class="user-email"><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
    </div>
    <nav class="dashboard-nav">
        <ul>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li class="<?php echo $currentComponent === 'admin' ? 'active' : ''; ?>">
                    <a href="?component=admin"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                </li>
                <li class="<?php echo $currentComponent === 'albums' ? 'active' : ''; ?>">
                    <a href="?component=albums"><i class="fas fa-images"></i> All Albums</a>
                </li>
                <li class="<?php echo $currentComponent === 'users' ? 'active' : ''; ?>">
                    <a href="?component=users"><i class="fas fa-users"></i> Manage Users</a>
                </li>
                <li class="<?php echo $currentComponent === 'photos' ? 'active' : ''; ?>">
                    <a href="?component=photos"><i class="fas fa-camera"></i> All Photos</a>
                </li>
                <li class="<?php echo $currentComponent === 'landing' ? 'active' : ''; ?>">
                    <a href="?component=landing"><i class="fas fa-cog"></i> User Dashboard</a>
                </li>
                <li class="<?php echo $currentComponent === 'favorites' ? 'active' : ''; ?>">
                    <a href="?component=favorites"><i class="fas fa-heart"></i> Favorites</a>
                </li>
                <li class="<?php echo $currentComponent === 'shared' ? 'active' : ''; ?>">
                    <a href="?component=shared"><i class="fas fa-share-alt"></i> Shared with Me</a>
                </li>
                <li class="<?php echo $currentComponent === 'invitations' ? 'active' : ''; ?>">
                    <a href="?component=invitations"><i class="fas fa-share-alt"></i> Invitations</a>
                </li>
                <li class="<?php echo $currentComponent === 'profile' ? 'active' : ''; ?>">
                    <a href="?component=profile"><i class="fas fa-share-alt"></i> Edit Profile</a>
                </li>
                <li class="<?php echo $currentComponent === 'albums_public' ? 'active' : ''; ?>">
                    <a href="?component=albums_public"><i class="fas fa-images"></i> Public Albums</a>
                </li>
                <li class="<?php echo $currentComponent === 'logs' ? 'active' : ''; ?>">
                    <a href="?component=logs"><i class="fas fa-images"></i> Logs</a>
                </li>
            <?php else: ?>
                <li class="<?php echo $currentComponent === 'landing' ? 'active' : ''; ?>">
                    <a href="?component=landing"><i class="fas fa-home"></i> Dashboard</a>
                </li>
                <li class="<?php echo $currentComponent === 'albums' ? 'active' : ''; ?>">
                    <a href="?component=albums"><i class="fas fa-images"></i> My Albums</a>
                </li>
                <li class="<?php echo $currentComponent === 'photos' ? 'active' : ''; ?>">
                    <a href="?component=photos"><i class="fas fa-camera"></i> All Photos</a>
                </li>
                <li class="<?php echo $currentComponent === 'favorites' ? 'active' : ''; ?>">
                    <a href="?component=favorites"><i class="fas fa-heart"></i> Favorites</a>
                </li>
                <li class="<?php echo $currentComponent === 'shared' ? 'active' : ''; ?>">
                    <a href="?component=shared"><i class="fas fa-share-alt"></i> Shared with Me</a>
                </li>
                <li class="<?php echo $currentComponent === 'invitations' ? 'active' : ''; ?>">
                    <a href="?component=invitations"><i class="fas fa-share-alt"></i> Invitations</a>
                </li>
                <li class="<?php echo $currentComponent === 'profile' ? 'active' : ''; ?>">
                    <a href="?component=profile"><i class="fas fa-share-alt"></i> Edit Profile</a>
                </li>
                <li class="<?php echo $currentComponent === 'albums_public' ? 'active' : ''; ?>">
                    <a href="?component=albums_public"><i class="fas fa-images"></i> Public Albums</a>
                </li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li>
                        <a href="?component=admin"><i class="fas fa-user-shield"></i> Admin Dashboard</a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </nav>
</aside>

