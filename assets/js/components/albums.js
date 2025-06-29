import { showToast } from "./shared/toast.js";
import { fetchAlbumPhotos } from "../services/album.js";

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
<div class="album-card" data-id="${album.album_id}">
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
    document.querySelectorAll('.album-card').forEach(card => {
        card.addEventListener('click', async () => {
            const albumId = card.dataset.id;
            createPhotoModal();

            const photos = await fetchAlbumPhotos(albumId);
            const photoGrid = document.getElementById('photo-grid');

            // Updated grid with consistent thumbnail sizes
            photoGrid.innerHTML = photos.map(photo => `
            <div class="photo-item">
                <img src="${photo.file_path}"
                     alt=""
                     class="thumbnail-photo"
                     data-full-src="${photo.file_path}" />
            </div>
        `).join('');

            // Open album modal
            const modal = new bootstrap.Modal(document.getElementById('photo-modal'));
            modal.show();

            // Add click handler for individual photos to open in full size
            document.querySelectorAll('.thumbnail-photo').forEach(img => {
                img.addEventListener('click', (e) => {
                    e.stopPropagation();
                    openFullSizePhotoModal(img.dataset.fullSrc);
                });
            });
        });
    });

// New function to create/open a modal for full-size photos
    const openFullSizePhotoModal = (imageSrc) => {
        // Remove existing full-size modal if any
        let existingModal = document.getElementById('full-photo-modal');
        if (existingModal) {
            existingModal.remove();
        }

        // Create new modal for full-size photo
        const fullSizeModal = document.createElement('div');
        fullSizeModal.id = 'full-photo-modal';
        fullSizeModal.className = 'modal fade';
        fullSizeModal.tabIndex = -1;

        fullSizeModal.innerHTML = `
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            <img src="${imageSrc}" class="img-fluid full-size-photo" alt="">
          </div>
        </div>
      </div>
    `;

        document.body.appendChild(fullSizeModal);

        // Open the modal
        const modal = new bootstrap.Modal(fullSizeModal);
        modal.show();
    };

// Add this CSS to your stylesheet
    const style = document.createElement('style');
    style.textContent = `
    #photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
    }
    
    .photo-item {
        width: 100%;
        aspect-ratio: 1/1;
        overflow: hidden;
    }
    
    .thumbnail-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    
    .thumbnail-photo:hover {
        transform: scale(1.05);
    }
    
    .full-size-photo {
        max-height: 80vh;
    }
`;
    document.head.appendChild(style);




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

const createPhotoModal = () => {
    let modal = document.getElementById('photo-modal');
    if (modal) return; // Already created

    modal = document.createElement('div');
    modal.id = 'photo-modal';
    modal.className = 'modal fade';
    modal.tabIndex = -1;
    modal.innerHTML = `
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Album Photos</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="photo-grid"></div>
          </div>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
};