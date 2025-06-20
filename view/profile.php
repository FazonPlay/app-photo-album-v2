<?php
/**
 * @var array|string $profileData
 * @var array $errors
 * @var bool $success
 */
require("_partials/errors.php");
?>

<div class="dashboard-container">
    <?php require "_partials/sidebar.php"; ?>

    <main class="main-content">
        <div class="dashboard-header">
            <h1>My Profile</h1>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">Profile updated successfully!</div>
        <?php endif; ?>

        <div class="card mt-4">
            <div class="card-body">
                <div class="user-form-container">
                    <form action="" id="profile-form" method="post" enctype="multipart/form-data" autocomplete="off">
                        <!-- Account Information -->
                        <h4 class="section-title">Account Information</h4>
                        <div class="form-group">
                            <label for="username">Username *</label>
                            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($profileData['username'] ?? '', ENT_QUOTES); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($profileData['email'] ?? '', ENT_QUOTES); ?>" required>
                        </div>

                        <!-- Personal Information -->
                        <h4 class="section-title mt-4">Personal Information</h4>
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($profileData['first_name'] ?? '', ENT_QUOTES); ?>">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($profileData['last_name'] ?? '', ENT_QUOTES); ?>">
                        </div>
                        <div class="form-group">
                            <label for="bio">Bio</label>
                            <textarea id="bio" name="bio" rows="4"><?php echo htmlspecialchars($profileData['bio'] ?? '', ENT_QUOTES); ?></textarea>
                        </div>

                        <!-- Profile Picture -->
                        <h4 class="section-title mt-4">Profile Picture</h4>
                        <?php if (!empty($profileData['profile_picture'])): ?>
                            <div class="current-profile-picture mb-3">
                                <img src="<?php echo htmlspecialchars($profileData['profile_picture'], ENT_QUOTES); ?>"
                                     alt="Current profile picture" class="img-thumbnail" style="max-width: 150px;">
                                <p class="small text-muted mt-1">Current profile picture</p>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="profile_picture">Upload New Picture</label>
                            <input type="file" id="profile_picture" name="profile_picture" class="form-control">
                            <small class="form-text text-muted">Maximum size: 2MB. Formats: JPG, PNG, GIF</small>
                        </div>

                        <!-- Password Change -->
                        <h4 class="section-title mt-4">Change Password</h4>
                        <div class="form-group">
                            <label for="password">New Password (leave blank to keep current)</label>
                            <input type="password" id="password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="confirmation">Confirm New Password</label>
                            <input type="password" id="confirmation" name="confirmation">
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn-primary">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>