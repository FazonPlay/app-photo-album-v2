<div class="dashboard-container">
    <?php require "_partials/sidebar.php"; ?>
    <main class="main-content">
        <div class="dashboard-header">
            <h1>Album Invitations</h1>
        </div>

        <div id="invitations-container">
            <?php if (empty($pendingInvitations)): ?>
                <div class="alert alert-info">You have no pending album invitations.</div>
            <?php else: ?>
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
            <?php endif; ?>
        </div>
    </main>
</div>

<script type="module">
    import { setupInvitationHandlers } from './assets/js/components/invitations.js';
    document.addEventListener('DOMContentLoaded', setupInvitationHandlers);
</script>