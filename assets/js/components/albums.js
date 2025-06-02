// assets/js/components/albums.js
import { getAlbums } from '../services/album.js';

export async function refreshAlbumList(page = 1) {
    const spinner = document.getElementById('spinner');
    const albumsTable = document.getElementById('list-albums').querySelector('tbody');
    const paginationElement = document.getElementById('pagination');

    spinner.classList.remove('d-none');

    try {
        const data = await getAlbums(page);

        // Clear existing content
        albumsTable.innerHTML = '';

        if (data.albums && data.albums.length > 0) {
            data.albums.forEach(album => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${album.id}</td>
                    <td>${album.title}</td>
                    <td>${album.username}</td>
                    <td>${album.photo_count}</td>
                    <td>${album.visibility}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="index.php?component=album&id=${album.id}" class="btn btn-sm btn-primary">View</a>
                            <a href="index.php?component=album&id=${album.id}&action=edit" class="btn btn-sm btn-secondary">Edit</a>
                            <button data-id="${album.id}" class="btn btn-sm btn-danger delete-album">Delete</button>
                        </div>
                    </td>
                `;
                albumsTable.appendChild(row);
            });

            // Add event listeners for delete buttons
            document.querySelectorAll('.delete-album').forEach(button => {
                button.addEventListener('click', handleAlbumDelete);
            });

            // Generate pagination
            generatePagination(paginationElement, data.pagination, page);

        } else {
            albumsTable.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center">No albums found</td>
                </tr>
            `;
        }
    } catch (error) {
        console.error('Error:', error);
        albumsTable.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-danger">Error loading albums</td>
            </tr>
        `;
    } finally {
        spinner.classList.add('d-none');
    }
}

function generatePagination(element, pagination, currentPage) {
    element.innerHTML = '';

    if (pagination.pages <= 1) return;

    // Previous button
    const prevLi = document.createElement('li');
    prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
    prevLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>`;
    element.appendChild(prevLi);

    // Page numbers
    for (let i = 1; i <= pagination.pages; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${i === currentPage ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
        element.appendChild(li);
    }

    // Next button
    const nextLi = document.createElement('li');
    nextLi.className = `page-item ${currentPage === pagination.pages ? 'disabled' : ''}`;
    nextLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>`;
    element.appendChild(nextLi);

    // Add event listeners
    element.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const page = parseInt(e.target.dataset.page);
            if (page && page !== currentPage) {
                refreshAlbumList(page);
            }
        });
    });
}

async function handleAlbumDelete(e) {
    if (confirm('Are you sure you want to delete this album?')) {
        const albumId = e.target.dataset.id;
        // Implement delete functionality
    }
}