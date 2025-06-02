import { getPhotos, addPhoto, removePhoto } from "../services/photo.js";
import { showToast } from "./shared/toast.js";

export const refreshPhotoList = async (page = 1) => {
    const photoList = document.getElementById('photo-list');
    const userSelect = document.getElementById('user-select');
    const pagination = document.getElementById('photo-pagination');
    const userId = userSelect ? userSelect.value : '';
    let urlPage = page || 1;

    let url = `index.php?component=photo&page=${urlPage}`;
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
        <img src="${photo.thumbnail_path || photo.file_path}" alt="${photo.title}">
        <div class="card-body">
            <div class="card-title">${photo.title}</div>
            <button class="delete-photo-btn" data-id="${photo.photo_id}">Delete</button>
        </div>
    </div>
`).join('');

    setupDeletePhotoButtons();

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