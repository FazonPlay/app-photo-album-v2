<?php
require("_partials/errors.php");
?>
<div class="dashboard-container">
<?php require '_partials/sidebar.php'; ?>
    <main class="main-content">
        <div class="dashboard-header">
            <h1>User Management</h1>
            <div class="actions">
                <a href="./index.php?component=user" class="btn"><i class="fas fa-plus"></i> Add User</a>
                <button id="refresh-users" class="btn btn-secondary"><i class="fas fa-sync"></i> Refresh</button>
            </div>
        </div>
        <section class="dashboard-section">
            <div class="section-header">
                <h2>All Users</h2>
                <div class="search-box">
                    <input type="text" id="search-users" placeholder="Search users...">
                    <button><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="row">
                <div class="col d-flex justify-content-center">
                    <div class="spinner-border text-primary d-none" role="status" id="spinner">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div id="users-message" class="message" style="display: none;"></div>
            <div class="table-responsive">
                <table class="admin-table" id="users-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Registration Date</th>
                        <th>Last Login</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody id="users-list">
                    <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
            <div class="row">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center" id="pagination">
                        <!-- Populated by JS -->
                    </ul>
                </nav>
            </div>
        </section>
    </main>
</div>
<script src="./assets/js/services/user.js" type="module"></script>
<script src="./assets/js/components/users.js" type="module"></script>
<script type="module">
    import { refreshList } from './assets/js/components/users.js';
    document.addEventListener('DOMContentLoaded', async () => {
        refreshList(1);
    });
</script>