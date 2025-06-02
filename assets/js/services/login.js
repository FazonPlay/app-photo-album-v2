// assets/js/services/login.js
export async function login(email, password) {
    try {
        const response = await fetch('/BigProjects/Fullstack3Month/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`,
        });

        if (response.redirected) {
            return { authentication: true, redirectUrl: response.url };
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Login error:', error);
        return {
            errors: ['Connection error. Please try again.']
        };
    }
}