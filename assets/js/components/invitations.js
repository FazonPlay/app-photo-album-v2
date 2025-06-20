export function setupInvitationHandlers() {
    document.querySelectorAll('.accept-invitation').forEach(btn => {
        btn.addEventListener('click', handleInvitation.bind(null, 'accept'));
    });

    document.querySelectorAll('.decline-invitation').forEach(btn => {
        btn.addEventListener('click', handleInvitation.bind(null, 'decline'));
    });
}

async function handleInvitation(action, event) {
    const card = event.target.closest('.invitation-card');
    const token = card.dataset.token;

    try {
        const response = await fetch('index.php?component=invitations', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: new URLSearchParams({ action, token })
        });

        const result = await response.json();
        if (result.success) {
            card.remove();
            if (document.querySelectorAll('.invitation-card').length === 0) {
                const container = document.getElementById('invitations-container');
                if (container) { // Check if the container exists before updating
                    container.innerHTML = '<div class="alert alert-info">You have no pending album invitations.</div>';
                }
            }
        }
    } catch (error) {
        console.error('Error handling invitation:', error);
    }
}