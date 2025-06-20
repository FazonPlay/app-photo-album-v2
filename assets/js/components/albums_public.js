import { fetchAlbumPhotos } from "../services/album.js";

export const refreshPublicAlbumList = async (page = 1) => {
    const albumList = document.getElementById('album-list');
    const pagination = document.getElementById('album-pagination');
    let urlPage = page || 1;

    let url = `index.php?component=albums_public&page=${urlPage}`;

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
                <p class="album-description">${album.description || ''}</p>
                <div class="album-actions">
                    <button class="view-album-btn album-btn" data-id="${album.album_id}">
                        <i class="fas fa-eye"></i> View Photos
                    </button>
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

            photoGrid.innerHTML = photos.map(photo => `
            <div class="photo-item">
                <img src="${photo.file_path}"
                     alt=""
                     class="thumbnail-photo"
                     data-full-src="${photo.file_path}" />
            </div>
            `).join('');

            const modal = new bootstrap.Modal(document.getElementById('photo-modal'));
            modal.show();

            document.querySelectorAll('.thumbnail-photo').forEach(img => {
                img.addEventListener('click', (e) => {
                    e.stopPropagation();
                    openFullSizePhotoModal(img.dataset.fullSrc);
                });
            });
        });
    });

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
            refreshPublicAlbumList(Number(btn.dataset.page));
        });
    });
};

export const openFullSizePhotoModal = (imageSrc) => {
    // Remove existing full-size modal if any
    let existingModal = document.getElementById('full-photo-modal');
    if (existingModal) {
        existingModal.remove();
    }

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

export const createPhotoModal = () => {
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