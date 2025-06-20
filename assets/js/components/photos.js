import { getPhotos, addPhoto, removePhoto, toggleFavorite, updatePhoto } from "../services/photo.js";
import { showToast } from "./shared/toast.js";

export const refreshPhotoList = async (page = 1) => {
    const photoList = document.getElementById('photo-list');
    const userSelect = document.getElementById('user-select');
    const pagination = document.getElementById('photo-pagination');
    const userId = userSelect ? userSelect.value : '';
    let urlPage = page || 1;

    let url = `index.php?component=photos&page=${urlPage}`;
    if (userId) url += `&user_id=${userId}`;

    const response = await fetch(url, {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    const data = await response.json();

    if (data.errors) {
        photoList.innerHTML = `<div class="alert alert-danger">${data.errors.join('<br>')}</div>`;
        pagination.innerHTML = '';
        return;
    }

    const photos = data.results || [];
    photoList.innerHTML = photos.map(photo => `
<div class="photo-card">
    <div class="photo-fav-icon" data-id="${photo.photo_id}" data-fav="${photo.is_favorite ? 1 : 0}" title="${photo.is_favorite ? 'Unfavorite' : 'Favorite'}" style="position:absolute;top:8px;right:8px;cursor:pointer;font-size:1.5em;">
        ${photo.is_favorite ? '❤️' : '🤍'}
    </div>
    <img src="${photo.thumbnail_path || photo.file_path}" alt="${photo.title}">
    <div class="card-body">
        <div class="card-title">${photo.title}</div>
        <div style="display: flex; gap: 5px; margin-top: 8px;">
            <button class="edit-photo-btn" data-id="${photo.photo_id}" data-title="${photo.title}" data-description="${photo.description || ''}">Edit</button>
            <button class="delete-photo-btn" data-id="${photo.photo_id}">Delete</button>
        </div>
    </div>
</div>
`).join('');

    setupDeletePhotoButtons();
    setupFavoritePhotoButtons();
    setupEditPhotoButtons();


    // Pagination
    const total = data.count || 0;
    const itemsPerPage = 20;
    const totalPages = Math.ceil(total / itemsPerPage);
    let pagHtml = '';
    for (let i = 1; i <= totalPages; i++) {
        pagHtml += `<li class="page-item${i === urlPage ? ' active' : ''}">
            <a class="page-link photo-page-btn" href="#" data-page="${i}">${i}</a>
        </li>`;
    }
    pagination.innerHTML = pagHtml;

    document.querySelectorAll('.photo-page-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            refreshPhotoList(Number(btn.dataset.page));
        });
    });
};

const setupDeletePhotoButtons = () => {
    document.querySelectorAll('.delete-photo-btn').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const id = btn.dataset.id;
            if (!confirm('Are you sure you want to delete this photo?')) return;
            const result = await removePhoto(id);
            if (result.success) {
                showToast('Photo deleted successfully!', 'bg-success');
                await refreshPhotoList(1);
            } else {
                showToast('Failed to delete photo.', 'bg-danger');
            }
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
                }
            }
        });
    });
};

export const handleAddPhoto = () => {
    const form = document.getElementById('add-photo-form');
    const errorDiv = document.getElementById('photo-errors');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        errorDiv.classList.add('d-none');
        errorDiv.innerHTML = '';

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        const result = await addPhoto(formData);

        if (result.success) {
            showToast('Photo added successfully!', 'bg-success');
            form.reset();
            await refreshPhotoList(1);
        } else if (result.errors) {
            errorDiv.innerHTML = result.errors.map(err => `<div>${err}</div>`).join('');
            errorDiv.classList.remove('d-none');
        }
    });
};
const showPhotoEditModal = (id, title, description) => {
    // Create modal HTML
    const modalHtml = `
    <div class="modal fade" id="editPhotoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-photo-form">
                        <input type="hidden" id="edit-photo-id" value="${id}">
                        <div class="mb-3">
                            <label for="edit-photo-title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="edit-photo-title" value="${title || ''}">
                        </div>
                        <div class="mb-3">
                            <label for="edit-photo-description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit-photo-description" rows="3">${description || ''}</textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-photo-edit">Save changes</button>
                </div>
            </div>
        </div>
    </div>`;

    // Add modal to document
    const existingModal = document.getElementById('editPhotoModal');
    if (existingModal) existingModal.remove();
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('editPhotoModal'));
    modal.show();

    // Set up save button
    document.getElementById('save-photo-edit').addEventListener('click', async () => {
        const photoId = document.getElementById('edit-photo-id').value;
        const newTitle = document.getElementById('edit-photo-title').value;
        const newDescription = document.getElementById('edit-photo-description').value;

        const result = await updatePhoto(photoId, newTitle, newDescription);
        if (result.success) {
            modal.hide();
            refreshPhotoList(1);
        }
    });
};

const setupEditPhotoButtons = () => {
    document.querySelectorAll('.edit-photo-btn').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const id = btn.dataset.id;
            const title = btn.dataset.title;
            const description = btn.dataset.description;
            showPhotoEditModal(id, title, description);
        });
    });
};