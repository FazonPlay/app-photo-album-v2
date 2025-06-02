export async function getAlbums(page = 1, limit = 10) {
    try {
        const response = await fetch(`index.php?component=albums&page=${page}&limit=${limit}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        return await response.json();
    } catch (error) {
        console.error('Error fetching albums:', error);
        return { error: 'Failed to fetch albums' };
    }
}

export async function createAlbum(title, description, visibility) {
    try {
        const formData = new FormData();
        formData.append('title', title);
        formData.append('description', description);
        formData.append('visibility', visibility);

        const response = await fetch('index.php?component=album', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        return await response.json();
    } catch (error) {
        console.error('Error creating album:', error);
        return { error: 'Failed to create album' };
    }
}