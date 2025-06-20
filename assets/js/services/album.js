export async function getAlbums(page = 1, limit = 10, userId = null) {
    let url = `index.php?component=albums&page=${page}&limit=${limit}`;
    if (userId) {
        url += `&user_id=${userId}`;
    }
    const response = await fetch(url, {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    return await response.json();
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

export async function deleteAlbum(id) {
    try {
        const data = new URLSearchParams({ action: 'delete', id });
        const response = await fetch('index.php?component=albums', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: data
        });
        return await response.json();
    } catch (error) {
        console.error('Error deleting album:', error);
        return { success: false };
    }
}