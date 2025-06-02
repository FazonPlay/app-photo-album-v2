export const login = async (email, password) => {
    const formData = new URLSearchParams();
    formData.append('email', email);
    formData.append('password', password);

    console.log('Sending data:', {email, password}); // Debug what's being sent

    const response = await fetch('index.php?component=login', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        method: 'POST',
        body: formData
    })
    return await response.json()

}