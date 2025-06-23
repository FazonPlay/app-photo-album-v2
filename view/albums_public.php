
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 sidebar-container">
            <?php require "_partials/sidebar.php"; ?>
        </div>

        <div class="col-md-9 col-lg-10 ms-sm-auto px-md-4">
            <div class="container mt-4">
                <h1>Public Photo Albums</h1>
                <p class="lead">Browse public photo albums shared by our community</p>

                <div class="row">
                    <div class="col-12">
                        <div id="album-list" class="album-grid"></div>
                        <nav aria-label="Album pagination">
                            <ul id="album-pagination" class="pagination justify-content-center mt-4"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module">
    import { refreshPublicAlbumList } from "./assets/js/components/albums_public.js";

    document.addEventListener('DOMContentLoaded', () => {
        refreshPublicAlbumList();
    });
</script>