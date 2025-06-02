// export async function signup(username, email, password, confirmPassword) {
//     try {
//         const response = await fetch('/BigProjects/Fullstack3Month/signup', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/x-www-form-urlencoded',
//             },
//             body: `username=${encodeURIComponent(username)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&confirm_password=${encodeURIComponent(confirmPassword)}`,
//         });
//
//         if (response.redirected) {
//             return { success: true, redirectUrl: response.url };
//         }
//
//         const data = await response.text();
//
//         if (data.includes('success-message')) {
//             return { success: true };
//         }
//
//         if (data.includes('error-message')) {
//             const parser = new DOMParser();
//             const doc = parser.parseFromString(data, 'text/html');
//             const errors = [];
//
//             doc.querySelectorAll('.error').forEach(el => {
//                 if (el.textContent.trim()) {
//                     errors.push(el.textContent.trim());
//                 }
//             });
//
//             if (doc.querySelector('.error-message p')) {
//                 errors.push(doc.querySelector('.error-message p').textContent.trim());
//             }
//
//             return { errors: errors.length ? errors : ['An error occurred during signup'] };
//         }
//
//         return { success: true };
//     } catch (error) {
//         console.error('Signup error:', error);
//         return {
//             errors: ['Connection error. Please try again.']
//         };
//     }
// }