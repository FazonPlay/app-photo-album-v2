<div class="auth-container">
    <div class="auth-form">
        <h1>Create an Account</h1>
        <p>Join PhotoGallery to start uploading and sharing your photos.</p>

        <div id="errors" class="error-message" style="display: none;"></div>

        <form method="POST" autocomplete="off" id="create-account-form">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="confirm-password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm-password" name="confirm-password" required autocomplete="off">
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-primary" id="submit-create-account">Create Account</button>
                <button type="button" class="btn btn-secondary ms-2" id="back-to-login">Back to Login</button>
            </div>
        </form>
    </div>
</div>

<script src="./assets/js/services/user.js" type="module"></script>
<script type="module">
    import { createAccount } from "./assets/js/services/user.js";

    document.addEventListener('DOMContentLoaded', () => {
        const createAccountForm = document.querySelector('#create-account-form');
        const submitBtn = document.querySelector('#submit-create-account');
        const backBtn = document.querySelector('#back-to-login');
        const errorElement = document.querySelector('#errors');

        submitBtn.addEventListener('click', async () => {
            if (!createAccountForm.checkValidity()) {
                createAccountForm.reportValidity();
                return;
            }

            const password = createAccountForm.elements['password'].value;
            const confirmPassword = createAccountForm.elements['confirm-password'].value;

            if (password !== confirmPassword) {
                errorElement.style.display = 'block';
                errorElement.innerHTML = '<div class="alert alert-danger">Passwords do not match!</div>';
                return;
            }

            const createResult = await createAccount(createAccountForm.elements['username'].value,
                createAccountForm.elements['email'].value,
                password
            );

            if (createResult?.success) {
                document.location.href = 'index.php?component=login';
            } else if (createResult?.errors) {
                const messages = createResult.errors.map(error =>
                    `<div class="alert alert-danger">${error}</div>`
                );
                errorElement.style.display = 'block';
                errorElement.innerHTML = messages.join('');
            }
        });

        backBtn.addEventListener('click', () => {
            document.location.href = 'index.php?component=login';
        });
    });
</script>
