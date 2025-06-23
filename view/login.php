<div class="auth-container">
    <div class="auth-form">
        <h1>Log In to Your Account</h1>
        <p>Welcome back! Log in to access your photos and albums.</p>

        <div id="errors" style="display: none;"></div>

        <form method="POST" autocomplete="off" id="login-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="valid-login-btn">Log In</button>
            </div>
        </form>

        <div class="auth-links">
            <p>Don't have an account? <a href="index.php?component=create_user">Sign Up</a></p>
            <p><a href="index.php?component=forgot-password">Forgot Password?</a></p>
        </div>
    </div>
</div>

<script type="module">
    import { login } from "./assets/js/services/login.js";

    document.addEventListener('DOMContentLoaded', () => {
        const validLoginBtn = document.querySelector('#valid-login-btn');
        const loginForm = document.querySelector('#login-form');
        const errorElement = document.querySelector('#errors');

        validLoginBtn.addEventListener('click', async () => {
            if (!loginForm.checkValidity()) {
                loginForm.reportValidity();
                return false;
            }

            const email = loginForm.elements['email'].value;
            const password = loginForm.elements['password'].value;
            const loginResult = await login(email, password);

            if (loginResult.hasOwnProperty('authentication')) {
                document.location.href = 'index.php';
            } else if (loginResult.hasOwnProperty('errors')) {
                const errors = loginResult.errors.map(
                    err => `<div class="alert alert-danger" role="alert">${err}</div>`
                );
                errorElement.innerHTML = errors.join('');
                errorElement.style.display = 'block';
            }
        });
    });
</script>

