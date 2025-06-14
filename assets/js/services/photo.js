export const getPhotos = async (page = 1, userId = null) => {
    let url = `index.php?component=photos&page=${page}`;
    if (userId) url += `&user_id=${userId}`;

    const response = await fetch(url, {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    return await response.json();
};

export const addPhoto = async (formData) => {
    formData.append('action', 'add');
    const response = await fetch('index.php?component=photos', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    });
    return await response.json();
};

export const removePhoto = async (id) => {
    const data = new URLSearchParams({ action: 'delete', id });
    const response = await fetch('index.php?component=photos', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: data
    });
    return await response.json();
};

export const toggleFavorite = async (id) => {
    const data = new URLSearchParams({ action: 'toggle_favorite', id });
    const response = await fetch('index.php?component=photos', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: data
    });
    return await response.json();
};