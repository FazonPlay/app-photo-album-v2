<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 sidebar-container">
            <?php require "_partials/sidebar.php"; ?>
        </div>

        <div class="col-md-9 col-lg-10 ms-sm-auto px-md-4 pt-4">

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">System Activity Logs</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <form method="get" action="">
                            <input type="hidden" name="component" value="logs">
                            <div class="row g-3 mb-3">
                                <div class="col-md-2">
                                    <label for="filter-date" class="form-label">Date:</label>
                                    <input type="date" id="filter-date" name="filter[date]" class="form-control"
                                           value="<?= isset($_GET['filter']['date']) ? htmlspecialchars($_GET['filter']['date']) : '' ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="filter-username" class="form-label">Username:</label>
                                    <select id="filter-username" name="filter[username]" class="form-select">
                                        <option value="">All Users</option>
                                        <?php foreach ($allUsernames as $username): ?>
                                            <option value="<?= htmlspecialchars($username) ?>" <?= (isset($_GET['filter']['username']) && $_GET['filter']['username'] === $username) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($username) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="filter-action" class="form-label">Action:</label>
                                    <select id="filter-action" name="filter[action]" class="form-select">
                                        <option value="">All Actions</option>
                                        <option value="login" <?= (isset($_GET['filter']['action']) && $_GET['filter']['action'] === 'login') ? 'selected' : '' ?>>login</option>
                                        <option value="logout" <?= (isset($_GET['filter']['action']) && $_GET['filter']['action'] === 'logout') ? 'selected' : '' ?>>logout</option>
                                        <option value="update" <?= (isset($_GET['filter']['action']) && $_GET['filter']['action'] === 'update') ? 'selected' : '' ?>>update</option>
                                        <option value="delete" <?= (isset($_GET['filter']['action']) && $_GET['filter']['action'] === 'delete') ? 'selected' : '' ?>>delete</option>
                                        <option value="register" <?= (isset($_GET['filter']['action']) && $_GET['filter']['action'] === 'register') ? 'selected' : '' ?>>register</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="filter-entity" class="form-label">Entity:</label>
                                    <select id="filter-entity" name="filter[entity]" class="form-select">
                                        <option value="">All Entities</option>
                                        <option value="user" <?= (isset($_GET['filter']['entity']) && $_GET['filter']['entity'] === 'user') ? 'selected' : '' ?>>user</option>
                                        <option value="admin" <?= (isset($_GET['filter']['entity']) && $_GET['filter']['entity'] === 'admin') ? 'selected' : '' ?>>admin</option>
                                        <option value="album" <?= (isset($_GET['filter']['entity']) && $_GET['filter']['entity'] === 'album') ? 'selected' : '' ?>>album</option>
                                        <option value="photo" <?= (isset($_GET['filter']['entity']) && $_GET['filter']['entity'] === 'photo') ? 'selected' : '' ?>>photo</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="per-page" class="form-label">Per Page:</label>
                                    <select id="per-page" name="per_page" class="form-select">
                                        <option value="10" <?= (isset($_GET['per_page']) && $_GET['per_page'] == 10) ? 'selected' : '' ?>>10</option>
                                        <option value="20" <?= (!isset($_GET['per_page']) || $_GET['per_page'] == 20) ? 'selected' : '' ?>>20</option>
                                        <option value="50" <?= (isset($_GET['per_page']) && $_GET['per_page'] == 50) ? 'selected' : '' ?>>50</option>
                                        <option value="100" <?= (isset($_GET['per_page']) && $_GET['per_page'] == 100) ? 'selected' : '' ?>>100</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="?component=logs" class="btn btn-secondary ms-2">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Timestamp</th>
                                <th>User ID</th>
                                <th>Username</th>
                                <th>Action</th>
                                <th>Entity</th>
                                <th>ID</th>
                                <th>Details</th>
                                <th>IP Address</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($logs)): ?>
                                <tr>
                                    <td colspan="8" class="text-center">No logs found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($log['timestamp']); ?></td>
                                        <td><?php echo isset($log['user id']) ? htmlspecialchars($log['user id']) : ''; ?></td>
                                        <td><?php echo isset($log['username']) ? htmlspecialchars($log['username']) : ''; ?></td>
                                        <td><?php echo isset($log['action']) ? htmlspecialchars($log['action']) : ''; ?></td>
                                        <td><?php echo isset($log['entity']) ? htmlspecialchars($log['entity']) : ''; ?></td>
                                        <td><?php echo isset($log['id']) ? htmlspecialchars($log['id']) : ''; ?></td>
                                        <td><?php echo isset($log['details']) ? htmlspecialchars($log['details']) : ''; ?></td>
                                        <td><?php echo isset($log['ip']) ? htmlspecialchars($log['ip']) : ''; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($totalPages > 1): ?>
                        <nav aria-label="Log pagination">
                            <ul class="pagination justify-content-center mt-4">
                                <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?component=logs<?= http_build_query(array_merge($_GET, ['page' => $currentPage - 1])) ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>

                                <?php
                                $startPage = max(1, $currentPage - 2);
                                $endPage = min($totalPages, $currentPage + 2);

                                if ($startPage > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="?component=logs&' .
                                        http_build_query(array_merge($_GET, ['page' => 1])) . '">1</a></li>';
                                    if ($startPage > 2) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                }

                                for ($i = $startPage; $i <= $endPage; $i++) {
                                    echo '<li class="page-item ' . ($i == $currentPage ? 'active' : '') . '">';
                                    echo '<a class="page-link" href="?component=logs&' .
                                        http_build_query(array_merge($_GET, ['page' => $i])) . '">' . $i . '</a>';
                                    echo '</li>';
                                }

                                if ($endPage < $totalPages) {
                                    if ($endPage < $totalPages - 1) {
                                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="?component=logs&' .
                                        http_build_query(array_merge($_GET, ['page' => $totalPages])) . '">' . $totalPages . '</a></li>';
                                }
                                ?>

                                <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?component=logs&<?= http_build_query(array_merge($_GET, ['page' => $currentPage + 1])) ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>

                        <div class="text-center mt-2">
                            <small>Showing <?= count($logs) ?> of <?= $totalLogs ?> logs (Page <?= $currentPage ?> of <?= $totalPages ?>)</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>