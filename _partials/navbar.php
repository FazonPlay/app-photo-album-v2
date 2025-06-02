<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">PhotoGallery</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto">
                <?php if (!empty($_SESSION['auth'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?component=landing">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?component=albums">Albums</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?component=about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?component=contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?disconnect=true">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?component=landing">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?component=login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?component=about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?component=contact">Contact</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
