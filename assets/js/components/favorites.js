import { getPhotos, toggleFavorite } from "../services/photo.js";
import { showToast } from "./shared/toast.js";

export const refreshFavoriteList = async (page = 1) => {
    const favoriteList = document.getElementById('favorite-list');
    const pagination = document.getElementById('favorite-pagination');
    let urlPage = page || 1;

    let url = `index.php?component=favorites&page=${urlPage}`;

    const response = await fetch(url, {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    const data = await response.json();

    if (data.errors) {
        favoriteList.innerHTML = `<div class="alert alert-danger">${data.errors.join('<br>')}</div>`;
        pagination.innerHTML = '';
        return;
    }

    const photos = data.results || [];
    favoriteList.innerHTML = photos.map(photo => `
<div class="photo-card">
    <div class="photo-fav-icon" data-id="${photo.photo_id}" data-fav="${photo.is_favorite ? 1 : 0}" title="${photo.is_favorite ? 'Unfavorite' : 'Favorite'}" style="position:absolute;top:8px;right:8px;cursor:pointer;font-size:1.5em;">
        ${photo.is_favorite ? '❤️' : '🤍'}
    </div>
    <img src="${photo.thumbnail_path || photo.file_path}" alt="${photo.title}">
    <div class="card-body">
        <div class="card-title">${photo.title}</div>
        <div class="card-description">${photo.description || ''}</div>
    </div>
</div>
`).join('');

    setupFavoritePhotoButtons();

    const total = data.count || 0;
    const itemsPerPage = 20;
    const totalPages = Math.ceil(total / itemsPerPage);
    let pagHtml = '';
    for (let i = 1; i <= totalPages; i++) {
        pagHtml += `<li class="page-item${i === urlPage ? ' active' : ''}">
            <a class="page-link favorite-page-btn" href="#" data-page="${i}">${i}</a>
        </li>`;
    }
    pagination.innerHTML = pagHtml;

    document.querySelectorAll('.favorite-page-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            refreshFavoriteList(Number(btn.dataset.page));
        });
    });
};

const setupFavoritePhotoButtons = () => {
    document.querySelectorAll('.photo-fav-icon').forEach(icon => {
        icon.addEventListener('click', async (e) => {
            e.preventDefault();
            const id = icon.dataset.id;
            const wasFav = icon.dataset.fav === "1";
            const result = await toggleFavorite(id);
            if (result.success) {
                icon.innerHTML = result.is_favorite ? '❤️' : '🤍';
                icon.dataset.fav = result.is_favorite ? "1" : "0";
                icon.title = result.is_favorite ? 'Unfavorite' : 'Favorite';
                if (!result.is_favorite) {
                    showToast('Photo removed from favorites', 'bg-danger');
                    icon.closest('.photo-card').remove();
                }
            }
        });
    });
};