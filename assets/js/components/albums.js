import { toggleFavorite } from "../services/photo.js";
import { showToast } from "./shared/toast.js";

export const refreshAlbumList = async (page = 1) => {
    const albumList = document.getElementById('album-list');
    const pagination = document.getElementById('album-pagination');
    let urlPage = page || 1;

    const response = await fetch(`index.php?component=albums&page=${urlPage}`, {
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
                ${album.cover_photo_id ? `<div class="album-fav-icon" data-id="${album.cover_photo_id}" data-fav="${album.is_favorite ? 1 : 0}" style="position:absolute;top:8px;right:8px;cursor:pointer;font-size:1.5em;">
                    ${album.is_favorite ? '❤️' : '🤍'}
                </div>` : ''}
            </div>
            <div class="album-info">
                <h3>${album.title}</h3>
                <button class="delete-album-btn" data-id="${album.album_id}">Delete</button>
            </div>
        </div>
    `).join('');

    setupDeleteAlbumButtons();
    setupFavoriteAlbumCoverButtons();

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

const setupFavoriteAlbumCoverButtons = () => {
    document.querySelectorAll('.album-fav-icon').forEach(icon => {
        icon.addEventListener('click', async (e) => {
            e.preventDefault();
            const id = icon.dataset.id;
            const wasFav = icon.dataset.fav === "1";
            const result = await toggleFavorite(id);
            if (result.success) {
                icon.innerHTML = result.is_favorite ? '❤️' : '🤍';
                icon.dataset.fav = result.is_favorite ? "1" : "0";
                if (!result.is_favorite) {
                    showToast('Photo removed from favorites', 'bg-danger');
                }
            }
        });
    });
};