document.addEventListener('DOMContentLoaded', function() {
    // State variables
    let currentPage = 1;
    let totalPages = 1;
    let usersPerPage = 10;
    let searchQuery = '';
    let sortBy = 'username';
    let sortOrder = 'asc';

    // DOM elements
    const usersList = document.getElementById('users-list');
    const paginationContainer = document.getElementById('pagination');
    const searchInput = document.getElementById('search-users');
    const refreshButton = document.getElementById('refresh-users');
    const messageContainer = document.getElementById('users-message');
    const deleteModal = document.getElementById('delete-modal');
    const deleteUsername = document.getElementById('delete-username');
    const confirmDeleteBtn = document.getElementById('confirm-delete');
    const cancelDeleteBtn = document.getElementById('cancel-delete');
    const closeModalBtn = document.querySelector('#delete-modal .close');

    // Store user ID for deletion
    let userIdToDelete = null;
    loadUsers();
    if (refreshButton) {
        refreshButton.addEventListener('click', function() {
            loadUsers();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', debounce(function() {
            searchQuery = this.value;
            currentPage = 1; // Reset to first page when searching
            loadUsers();
        }, 500));
    }

    // Handle sort column clicks
    document.querySelectorAll('#users-table th').forEach(th => {
        if (th.dataset.sort) {
            th.addEventListener('click', function() {
                const newSortBy = this.dataset.sort;

                // If clicking the same column, toggle sort order
                if (newSortBy === sortBy) {
                    sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
                } else {
                    sortBy = newSortBy;
                    sortOrder = 'asc';
                }

                // Update UI to show sort direction
                document.querySelectorAll('#users-table th').forEach(header => {
                    header.classList.remove('sort-asc', 'sort-desc');
                });

                this.classList.add(sortOrder === 'asc' ? 'sort-asc' : 'sort-desc');

                loadUsers();
            });
        }
    });

    // Handle delete button clicks using event delegation
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-user')) {
            const button = e.target.closest('.delete-user');
            userIdToDelete = button.dataset.id;
            const username = button.dataset.username;

            // Update modal and show it
            deleteUsername.textContent = username;
            deleteModal.style.display = 'block';
        }
    });

    // Handle modal actions
    confirmDeleteBtn.addEventListener('click', function() {
        if (userIdToDelete) {
            deleteUser(userIdToDelete);
            deleteModal.style.display = 'none';
        }
    });

    cancelDeleteBtn.addEventListener('click', function() {
        deleteModal.style.display = 'none';
    });

    closeModalBtn.addEventListener('click', function() {
        deleteModal.style.display = 'none';
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            deleteModal.style.display = 'none';
        }
    });

    // Functions
    function loadUsers() {
        showMessage('Loading users...', 'info');

        // In a real app, this would be an AJAX call to your server
        fetch(`/BigProjects/Fullstack3Month/users?page=${currentPage}&limit=${usersPerPage}&search=${searchQuery}&sort=${sortBy}&order=${sortOrder}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                renderUsers(data.users);
                renderPagination(data.totalPages);
                hideMessage();
            })
            .catch(error => {
                console.error('Error fetching users:', error);
                showMessage('Failed to load users. Please try again later.', 'error');
            });
    }

    function renderUsers(users) {
        if (!usersList) return;

        if (users && users.length > 0) {
            usersList.innerHTML = users.map(user => `
                <tr>
                    <td>${escapeHtml(user.id)}</td>
                    <td>${escapeHtml(user.username)}</td>
                    <td>${escapeHtml(user.email)}</td>
                    <td>${escapeHtml(user.registrationDate)}</td>
                    <td>${escapeHtml(user.lastLogin || 'Never')}</td>
                    <td>${escapeHtml(user.roles)}</td>
                    <td>
                        ${user.isActive ?
                '<span class="status-badge active">Active</span>' :
                '<span class="status-badge inactive">Inactive</span>'}
                    </td>
                    <td class="actions-cell">
                        <a href="/BigProjects/Fullstack3Month/admin/users/${user.id}" class="btn-icon" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="/BigProjects/Fullstack3Month/admin/users/${user.id}/edit" class="btn-icon" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn-icon delete-user" data-id="${user.id}" data-username="${escapeHtml(user.username)}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        } else {
            usersList.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center">No users found</td>
                </tr>
            `;
        }
    }

    function renderPagination(totalPages) {
        if (!paginationContainer) return;

        if (totalPages <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }

        let pagination = '<div class="pagination">';

        // Previous button
        pagination += `
            <button class="page-btn prev-btn" ${currentPage === 1 ? 'disabled' : ''}>
                <i class="fas fa-chevron-left"></i> Previous
            </button>
        `;

        // Page numbers
        pagination += '<div class="page-numbers">';

        // Always show first page
        if (currentPage > 3) {
            pagination += `<button class="page-num" data-page="1">1</button>`;
            if (currentPage > 4) {
                pagination += '<span class="page-ellipsis">...</span>';
            }
        }

        // Show current page and neighbors
        for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
            pagination += `
                <button class="page-num ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>
            `;
        }

        // Always show last page
        if (currentPage < totalPages - 2) {
            if (currentPage < totalPages - 3) {
                pagination += '<span class="page-ellipsis">...</span>';
            }
            pagination += `<button class="page-num" data-page="${totalPages}">${totalPages}</button>`;
        }

        pagination += '</div>';

        // Next button
        pagination += `
            <button class="page-btn next-btn" ${currentPage === totalPages ? 'disabled' : ''}>
                Next <i class="fas fa-chevron-right"></i>
            </button>
        `;

        pagination += '</div>';
        paginationContainer.innerHTML = pagination;

        // Add event listeners to pagination buttons
        document.querySelectorAll('.page-num').forEach(button => {
            button.addEventListener('click', function() {
                currentPage = parseInt(this.dataset.page);
                loadUsers();
            });
        });

        document.querySelector('.prev-btn')?.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                loadUsers();
            }
        });

        document.querySelector('.next-btn')?.addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                loadUsers();
            }
        });
    }

    function deleteUser(userId) {
        showMessage('Deleting user...', 'info');

        fetch(`/BigProjects/Fullstack3Month/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to delete user');
                }
                return response.json();
            })
            .then(data => {
                showMessage('User deleted successfully!', 'success');
                setTimeout(() => {
                    loadUsers();
                }, 1000);
            })
            .catch(error => {
                console.error('Error deleting user:', error);
                showMessage('Failed to delete user. Please try again.', 'error');
            });
    }

    // Helper functions
    function showMessage(message, type = 'info') {
        if (!messageContainer) return;

        messageContainer.textContent = message;
        messageContainer.className = `message ${type}`;
        messageContainer.style.display = 'block';
    }

    function hideMessage() {
        if (messageContainer) {
            messageContainer.style.display = 'none';
        }
    }

    function escapeHtml(unsafe) {
        return unsafe
            .toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

});