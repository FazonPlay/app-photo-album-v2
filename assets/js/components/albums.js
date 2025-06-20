import { showToast } from "./shared/toast.js";

export const refreshAlbumList = async (page = 1) => {
    const albumList = document.getElementById('album-list');
    const pagination = document.getElementById('album-pagination');
    const userSelect = document.getElementById('user-select');
    const userId = userSelect ? userSelect.value : '';
    let urlPage = page || 1;

    let url = `index.php?component=albums&page=${urlPage}`;
    if (userId) url += `&user_id=${userId}`;



    const response = await fetch(url, {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    const data = await response.json();


    if (data.errors) {
        albumList.innerHTML = `<div class="alert alert-danger">${data.errors.join('<br>')}</div>`;
        pagination.innerHTML = '';
        return;
    }

    const albums = data.results || [];
    albumList.innerHTML = albums.map(album => `
<div class="album-card">
    <div class="album-thumbnail">
        <img src="${album.cover_path || 'assets/img/default_album.jpg'}" alt="${album.title}">
    </div>
    <div class="album-info">
        <h3>${album.title}</h3>
        <div class="album-actions">
            <a href="index.php?component=album&id=${album.album_id}" class="album-btn">
                <i class="fas fa-edit"></i> Edit
            </a>
            <button class="delete-album-btn album-btn" data-id="${album.album_id}">Delete</button>
        </div>
    </div>
</div>
`).join('');

    setupDeleteAlbumButtons();

    // Pagination
    const total = data.count || 0;
    const itemsPerPage = 20;
    const totalPages = Math.ceil(total / itemsPerPage);
    let pagHtml = '';
    for (let i = 1; i <= totalPages; i++) {
        pagHtml += `<li class="page-item${i === urlPage ? ' active' : ''}">
            <a class="page-link album-page-btn" href="#" data-page="${i}">${i}</a>
        </li>`;
    }
    pagination.innerHTML = pagHtml;

    document.querySelectorAll('.album-page-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            refreshAlbumList(Number(btn.dataset.page));
        });
    });
};

const setupDeleteAlbumButtons = () => {
    document.querySelectorAll('.delete-album-btn').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const id = btn.dataset.id;
            if (!confirm('Are you sure you want to delete this album?')) return;
            const response = await fetch('index.php?component=albums', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: new URLSearchParams({ action: 'delete', id })
            });
            const result = await response.json();
            if (result.success) {
                alert('Album deleted successfully!');
                await refreshAlbumList(1);
            } else {
                alert('Failed to delete album.');
            }
        });
    });
};

export function setupAlbumInviteForm(albumId) {
    const form = document.getElementById('invite-user-form');
    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const data = new URLSearchParams(new FormData(form));
        data.append('action', 'invite');
        data.append('album_id', albumId);

        const response = await fetch('index.php?component=albums', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: data
        });
        const result = await response.json();
        if (result.success) showToast('Invitation sent!');
    });
}