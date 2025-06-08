<?php
/**
 * @var PDO $pdo
 */
registerCss("assets/css/album.css");
require "model/album.php";
require "model/photo.php";
$allPhotos = getPhotos($pdo, 1, 1000)['photos'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    require "model/albums.php";
    $title = $_POST['title'];
    $description = $_POST['description'] ?? '';
    $coverPhotoId = null;
    $photoIds = $_POST['photos'] ?? [];

    // Automatically use the first selected photo as the cover photo
    $coverPhotoId = !empty($photoIds) ? (int)$photoIds[0] : null;

    $albumId = addAlbum($pdo, $title, $description, $coverPhotoId);
    if ($albumId && !empty($photoIds)) {
        foreach ($photoIds as $pid) {
            addPhotoToAlbum($pdo, $albumId, (int)$pid);
        }
    }
    header("Location: index.php?component=albums");
    exit();
}

require "view/album.php";