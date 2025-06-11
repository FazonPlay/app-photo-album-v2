<?php
/**
 * @var PDO $pdo
 */
registerCss("assets/css/album.css");
require_once "model/album.php";
require_once "model/photos.php";

$errors = [];
$albumId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = $albumId > 0 ? 'edit' : 'create';
$albumData = $albumId > 0 ? getAlbum($pdo, $albumId) : [];
$allPhotos = $albumId > 0 ? getPhotosByAlbum($pdo, $albumId) : [];
$allAvailablePhotos = getAllPhotos($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $visibility = $_POST['visibility'] ?? 'private';
    $selectedPhotos = $_POST['photos'] ?? [];

    // Validate form data
    if (empty($title)) $errors[] = "Title is required.";
    if (empty($description)) $errors[] = "Description is required.";

    if (empty($errors)) {
        if ($albumId > 0) {
            // Update existing album
            $result = updateAlbum($pdo, $albumId, $title, $description, $visibility, $selectedPhotos);
            if ($result === true) {
                header("Location: index.php?component=albums");
                exit;
            } else {
                $errors[] = $result;
            }
        } else {
            // Create new album
            $coverPhotoId = !empty($selectedPhotos) ? $selectedPhotos[0] : null;
            $newAlbumId = addAlbum($pdo, $title, $description, $coverPhotoId);

            // Update the album with visibility and associate photos
            if ($newAlbumId) {
                $pdo->prepare("UPDATE albums SET visibility = ? WHERE album_id = ?")->execute([$visibility, $newAlbumId]);

                // Associate selected photos with the new album
                if (!empty($selectedPhotos)) {
                    foreach ($selectedPhotos as $photoId) {
                        addPhotoToAlbum($pdo, $newAlbumId, $photoId);
                    }
                }

                header("Location: index.php?component=albums");
                exit;
            } else {
                $errors[] = "Failed to create album";
            }
        }
    }

    // Repopulate form fields on error
    $albumData['title'] = $title;
    $albumData['description'] = $description;
    $albumData['visibility'] = $visibility;
}

require "view/album.php";